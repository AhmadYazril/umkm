<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class KasirController extends Controller
{
    public const KASIR_COOKIE = 'nucomu_kasir_auth_v2';

    public const COOKIE_LIFETIME_MINUTES = 525600; // 365 days (1 year)

    /**
     * Ensure default Kasir account exists in the database so login works out of the box.
     */
    public static function ensureDefaultKasirUser(): void
    {
        try {
            if (! User::where('email', 'kasir@nucomu.com')->exists()) {
                User::create([
                    'name' => 'Kasir Nucomu',
                    'email' => 'kasir@nucomu.com',
                    'password' => Hash::make('password123'),
                    'role' => 'kasir',
                ]);
            }
        } catch (\Throwable $e) {
            // Gracefully ignore if database table is not ready
        }
    }

    /**
     * Generate a deterministic HMAC token for the persistent Kasir login cookie.
     */
    public static function generateCookieToken(User $user): string
    {
        $secret = (string) config('app.key', 'nucomu-kasir-secret');

        return hash_hmac('sha256', $user->id.'|'.$user->email.'|'.$user->password, $secret);
    }

    /**
     * Queue the persistent Kasir authentication cookie (1 year) so returning users do not need to log in again.
     */
    public static function queueAuthCookie(User $user): string
    {
        $cookieValue = $user->id.'|'.self::generateCookieToken($user);

        Cookie::queue(
            Cookie::make(
                self::KASIR_COOKIE,
                $cookieValue,
                self::COOKIE_LIFETIME_MINUTES,
                '/',
                null,
                false,
                true,
                false,
                'lax'
            )
        );

        return $cookieValue;
    }

    /**
     * Check if the user has logged in through Kasir (/kasir/login) or restore from the persistent Kasir cookie.
     * Being logged in to /admin alone without the Kasir cookie/session flag will still require /kasir/login first.
     */
    public static function checkOrRestoreKasirAuth(Request $request): bool
    {
        $cookieValue = (string) ($request->cookie(self::KASIR_COOKIE) ?? '');
        if ($cookieValue !== '' && str_contains($cookieValue, '|')) {
            [$userId, $token] = explode('|', $cookieValue, 2);
            if (is_numeric($userId) && $token !== '') {
                try {
                    $user = User::find((int) $userId);
                    if ($user && $user->isKasir() && hash_equals(self::generateCookieToken($user), $token)) {
                        if (! Auth::check() || Auth::id() !== $user->id) {
                            Auth::login($user, false);
                        }
                        $request->session()->put('kasir_authenticated', true);

                        return true;
                    }
                } catch (\Throwable $e) {
                    return false;
                }
            }
        }

        if ($request->session()->get('kasir_authenticated') === true && Auth::check()) {
            /** @var User $currentUser */
            $currentUser = Auth::user();
            if ($currentUser->isKasir()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Display the Kasir login page, or redirect straight to /kasir if already authenticated via session/cookie.
     */
    public function showLogin(Request $request): View|RedirectResponse
    {
        self::ensureDefaultKasirUser();

        if (self::checkOrRestoreKasirAuth($request)) {
            return redirect()->route('kasir.index');
        }

        $kasirAccount = null;
        try {
            $kasirAccount = User::where('email', 'kasir@nucomu.com')->first()
                ?? User::whereIn('role', ['kasir', 'admin'])->first();
        } catch (\Throwable $e) {
            $kasirAccount = null;
        }

        return view('kasir.login', compact('kasirAccount'));
    }

    /**
     * Handle Kasir login (password-only with automatic Kasir account) and create the persistent authentication cookie.
     */
    public function login(Request $request): RedirectResponse
    {
        self::ensureDefaultKasirUser();

        $validated = $request->validate([
            'email' => ['nullable', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = trim((string) ($validated['email'] ?? '')) ?: 'kasir@nucomu.com';
        $password = (string) $validated['password'];

        $authenticated = Auth::attempt([
            'email' => $email,
            'password' => $password,
        ], false);

        if (! $authenticated) {
            try {
                $fallbackUser = User::whereIn('role', ['kasir', 'admin'])
                    ->get()
                    ->first(fn (User $u) => Hash::check($password, $u->password));

                if ($fallbackUser) {
                    Auth::login($fallbackUser, false);
                    $authenticated = true;
                }
            } catch (\Throwable $e) {
                $authenticated = false;
            }
        }

        if ($authenticated) {
            /** @var User $user */
            $user = Auth::user();

            if (! $user->isKasir()) {
                Auth::logout();

                return back()->withErrors([
                    'password' => 'Akun Anda tidak memiliki akses ke halaman Kasir.',
                ]);
            }

            $request->session()->regenerate();
            $request->session()->put('kasir_authenticated', true);
            self::queueAuthCookie($user);

            return redirect()->route('kasir.index');
        }

        return back()->withErrors([
            'password' => 'Kata sandi kasir yang Anda masukkan salah.',
        ]);
    }

    /**
     * Log out from Kasir and clear the persistent cookie.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('kasir_authenticated');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget(self::KASIR_COOKIE));
        Cookie::queue(Cookie::forget('nucomu_kasir_session'));

        return redirect()->route('kasir.login');
    }

    /**
     * Ensure database schema (menus.stock column) and menu records are synchronized between DB, Admin, and Kasir.
     */
    public static function ensureDatabaseSchemaAndSync(): void
    {
        try {
            if (Schema::hasTable('menus') && ! Schema::hasColumn('menus', 'stock')) {
                Schema::table('menus', function (Blueprint $table) {
                    $table->integer('stock')->default(25)->after('price');
                });
            }

            $instance = app(self::class);
            $stockMap = $instance->getStockMap();
            $configMenu = collect(config('nucomu.all_menu', []));
            $configImageMap = [];
            foreach ($configMenu as $cm) {
                if (! empty($cm['image'])) {
                    $configImageMap[strtolower((string) ($cm['id'] ?? ''))] = $cm['image'];
                    $configImageMap[strtolower((string) ($cm['name'] ?? ''))] = $cm['image'];
                }
            }

            $dbMenus = Menu::with('category')->get();
            $mapChanged = false;

            foreach ($dbMenus as $m) {
                $idKey = (string) $m->id;
                $rawCatName = $m->category?->name ?? 'Minuman';
                $rawCatSlug = strtolower($m->category?->slug ?? $rawCatName);
                $categorization = $instance->mapCategoryGroup($rawCatSlug, $rawCatName, $m->name);

                $updates = [];

                if (empty($m->image)) {
                    $updates['image'] = $instance->resolveMenuImage(null, $m->slug, $m->name, $categorization['group_key'], $configImageMap);
                }

                $dbStock = $m->stock !== null ? (int) $m->stock : ($m->is_available ? 25 : 0);
                if (! $m->is_available && $dbStock > 0 && ! array_key_exists($idKey, $stockMap)) {
                    $dbStock = 0;
                    $updates['stock'] = 0;
                } elseif (array_key_exists($idKey, $stockMap) && $m->stock === 25 && (int) $stockMap[$idKey] !== 25) {
                    $dbStock = max(0, (int) $stockMap[$idKey]);
                    $updates['stock'] = $dbStock;
                    $updates['is_available'] = $dbStock > 0;
                }

                if (! empty($updates)) {
                    $m->update($updates);
                }

                if (! array_key_exists($idKey, $stockMap) || (int) $stockMap[$idKey] !== $dbStock) {
                    $stockMap[$idKey] = $dbStock;
                    $mapChanged = true;
                }
            }

            if ($mapChanged) {
                $instance->saveStockMap($stockMap);
            }
        } catch (\Throwable $e) {
            // Gracefully ignore if database table is not ready
        }
    }

    /**
     * Synchronize a single Menu model's stock and availability across both the database and Kasir settings.
     */
    public static function syncSingleMenuStock(Menu $menu, ?int $explicitStock = null): int
    {
        try {
            self::ensureDatabaseSchemaAndSync();
        } catch (\Throwable $e) {
        }

        if ($explicitStock !== null) {
            $newStock = max(0, $explicitStock);
        } elseif (! $menu->is_available) {
            $newStock = 0;
        } else {
            $newStock = ($menu->stock !== null && (int) $menu->stock > 0) ? (int) $menu->stock : 25;
        }

        $isAvailable = $newStock > 0;

        try {
            $menu->update([
                'stock' => $newStock,
                'is_available' => $isAvailable,
            ]);
        } catch (\Throwable $e) {
        }

        try {
            $instance = app(self::class);
            $stockMap = $instance->getStockMap();
            $stockMap[(string) $menu->id] = $newStock;
            $instance->saveStockMap($stockMap);
        } catch (\Throwable $e) {
        }

        return $newStock;
    }

    /**
     * Deduct ordered quantities from both the database Menu table and Kasir stock map.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, int>
     */
    public static function deductStockForOrderItems(array $items): array
    {
        $instance = app(self::class);
        $stockMap = $instance->getStockMap();
        $updatedStocks = [];

        foreach ($items as $item) {
            $midRaw = $item['menu_id'] ?? null;
            $menuName = trim((string) ($item['menu_name'] ?? ''));
            $qty = max(1, (int) ($item['qty'] ?? 1));

            $menuModel = null;
            try {
                if (is_numeric($midRaw)) {
                    $menuModel = Menu::find((int) $midRaw);
                }
                if (! $menuModel && is_string($midRaw) && $midRaw !== '') {
                    $menuModel = Menu::where('slug', $midRaw)->first();
                }
                if (! $menuModel && $menuName !== '') {
                    $menuModel = Menu::whereRaw('LOWER(name) = ?', [strtolower($menuName)])->first();
                }
            } catch (\Throwable $e) {
                $menuModel = null;
            }

            if ($menuModel) {
                $idKey = (string) $menuModel->id;
                $currentStock = $menuModel->stock !== null
                    ? (int) $menuModel->stock
                    : (isset($stockMap[$idKey]) ? (int) $stockMap[$idKey] : ($menuModel->is_available ? 25 : 0));
                $newStock = max(0, $currentStock - $qty);

                $stockMap[$idKey] = $newStock;
                $updatedStocks[$idKey] = $newStock;

                try {
                    $menuModel->update([
                        'stock' => $newStock,
                        'is_available' => $newStock > 0,
                    ]);
                } catch (\Throwable $e) {
                }
            } elseif ($midRaw !== null && (string) $midRaw !== '') {
                $mid = (string) $midRaw;
                $currentStock = isset($stockMap[$mid]) ? (int) $stockMap[$mid] : 25;
                $newStock = max(0, $currentStock - $qty);
                $stockMap[$mid] = $newStock;
                $updatedStocks[$mid] = $newStock;
            }
        }

        $instance->saveStockMap($stockMap);

        return $updatedStocks;
    }

    /**
     * Main Kasir POS, QR Scanner, and Order Workspace (/kasir).
     */
    public function index(Request $request): View
    {
        self::ensureDatabaseSchemaAndSync();

        $catalogItems = $this->resolveKasirCatalog();
        $groupedCategories = $this->groupCatalogByCategory($catalogItems);

        $scannedOrder = null;
        $rawCode = trim((string) $request->query('code', ''));
        if ($rawCode !== '') {
            $cleanCode = $this->extractOrderCode($rawCode);
            $scannedOrder = Order::where('code', $cleanCode)->with(['items', 'table'])->first();
        }

        $recentOrders = collect();
        $tables = collect();
        $todayOrdersCount = 0;
        $todayRevenue = 0;
        $pendingOrdersCount = 0;

        try {
            $recentOrders = Order::with(['items', 'table'])->latest()->take(25)->get();
            $tables = Table::orderBy('number')->get();
            $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
            $todayRevenue = (int) Order::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            $pendingOrdersCount = Order::where('status', 'pending')->count();
        } catch (\Throwable $e) {
            // Fallback gracefully if tables are empty
        }

        $host = $request->getHost();
        $port = $request->getPort();
        $scheme = $request->getScheme();

        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            $detectedLanIp = $this->detectLocalNetworkIp();
            if ($detectedLanIp) {
                $host = $detectedLanIp;
            }
        }

        $portSuffix = ($port && ! in_array($port, [80, 443], true)) ? ":{$port}" : '';
        $baseQrUrl = "{$scheme}://{$host}{$portSuffix}/qr/pesanan/";

        $activeTab = in_array($request->query('tab'), ['pos', 'stock', 'scan', 'orders'], true)
            ? (string) $request->query('tab')
            : ($rawCode !== '' ? 'scan' : 'pos');

        return view('kasir.index', compact(
            'catalogItems',
            'groupedCategories',
            'scannedOrder',
            'recentOrders',
            'tables',
            'todayOrdersCount',
            'todayRevenue',
            'pendingOrdersCount',
            'host',
            'portSuffix',
            'scheme',
            'baseQrUrl',
            'activeTab'
        ));
    }

    /**
     * Store a new order directly from the Kasir POS workspace and return QR + receipt data.
     */
    public function storeOrder(Request $request): JsonResponse
    {
        self::ensureDatabaseSchemaAndSync();

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'order_type' => ['required', 'string', 'in:dine_in,take_away'],
            'table_no' => ['nullable', 'string', 'max:50'],
            'payment_method' => ['required', 'string', 'in:cash,qris,transfer'],
            'payment_status' => ['nullable', 'string', 'in:paid,unpaid'],
            'cash_received' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['nullable'],
            'items.*.menu_name' => ['required', 'string', 'max:255'],
            'items.*.unit_price' => ['required', 'integer', 'min:0'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $customerName = trim((string) ($validated['customer_name'] ?? '')) ?: 'Pelanggan Kasir';
        $customerPhone = trim((string) ($validated['customer_phone'] ?? '')) ?: '-';
        $orderType = $validated['order_type'];
        $tableNo = trim((string) ($validated['table_no'] ?? ''));
        $paymentMethod = $validated['payment_method'];
        $paymentStatus = $validated['payment_status'] ?? 'paid';
        $rawNotes = trim((string) ($validated['notes'] ?? ''));

        $subtotal = 0;
        $normalizedItems = [];
        foreach ($validated['items'] as $item) {
            $unitPrice = (int) $item['unit_price'];
            $qty = (int) $item['qty'];
            $lineTotal = $unitPrice * $qty;
            $subtotal += $lineTotal;

            $normalizedItems[] = [
                'menu_id' => $item['menu_id'] ?? null,
                'menu_name' => $item['menu_name'],
                'unit_price' => $unitPrice,
                'qty' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        $grandTotal = $subtotal;
        $cashReceived = isset($validated['cash_received']) && $validated['cash_received'] > 0
            ? (int) $validated['cash_received']
            : $grandTotal;
        $changeAmount = max(0, $cashReceived - $grandTotal);

        $noteParts = [];
        if ($orderType === 'dine_in' && $tableNo !== '') {
            $noteParts[] = "[Nomor Meja: {$tableNo}]";
        } elseif ($orderType === 'take_away') {
            $noteParts[] = '[Bawa Pulang / Take Away]';
        }
        if ($rawNotes !== '') {
            $noteParts[] = $rawNotes;
        }
        $storedNotes = count($noteParts) > 0 ? implode(' | ', $noteParts) : null;

        $order = DB::transaction(function () use (
            $customerName,
            $customerPhone,
            $orderType,
            $storedNotes,
            $subtotal,
            $grandTotal,
            $paymentMethod,
            $paymentStatus,
            $normalizedItems
        ) {
            $date = Carbon::now()->format('Ymd');
            $count = Order::whereDate('created_at', today())->count() + 1;
            $code = 'NCM-'.$date.'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);

            while (Order::where('code', $code)->exists()) {
                $count++;
                $code = 'NCM-'.$date.'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
            }

            $order = Order::create([
                'code' => $code,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'order_type' => $orderType,
                'notes' => $storedNotes,
                'subtotal' => $subtotal,
                'total' => $grandTotal,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'status' => $paymentStatus === 'paid' ? 'processing' : 'pending',
            ]);

            foreach ($normalizedItems as $item) {
                $menuIdVal = $item['menu_id'];
                $validMenuId = is_numeric($menuIdVal) && Menu::whereKey($menuIdVal)->exists()
                    ? (int) $menuIdVal
                    : null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $validMenuId,
                    'menu_name' => $item['menu_name'],
                    'unit_price' => $item['unit_price'],
                    'qty' => $item['qty'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order->load('items');
        });

        // Automatically deduct ordered quantities from DB Menu & Kasir stock map
        $updatedStocks = self::deductStockForOrderItems($normalizedItems);

        $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
        $todayRevenue = (int) Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total');
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        $host = $request->getHost();
        $port = $request->getPort();
        $scheme = $request->getScheme();
        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            $detectedLanIp = $this->detectLocalNetworkIp();
            if ($detectedLanIp) {
                $host = $detectedLanIp;
            }
        }
        $portSuffix = ($port && ! in_array($port, [80, 443], true)) ? ":{$port}" : '';
        $qrUrl = "{$scheme}://{$host}{$portSuffix}/qr/pesanan/{$order->code}";

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan ke Database & Admin.',
            'order' => [
                'id' => $order->id,
                'code' => $order->code,
                'customer_name' => $order->customer_name,
                'order_type' => $order->order_type,
                'table_no' => $tableNo,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'subtotal' => $order->subtotal,
                'total' => $order->total,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
                'notes' => $order->notes,
                'created_at' => $order->created_at?->format('d/m/Y H:i'),
                'created_at_time' => $order->created_at?->format('H:i'),
                'items' => $order->items->map(fn (OrderItem $i) => [
                    'menu_name' => $i->menu_name,
                    'qty' => $i->qty,
                    'unit_price' => $i->unit_price,
                    'line_total' => $i->line_total,
                ])->values()->all(),
            ],
            'qr_url' => $qrUrl,
            'slip_url' => route('nucomu.order.slip', ['code' => $order->code, 'method' => 'kasir']),
            'receipt_url' => route('kasir.orders.receipt', $order->id),
            'stocks' => $updatedStocks,
            'stats' => [
                'today_orders_count' => $todayOrdersCount,
                'today_revenue' => $todayRevenue,
                'pending_orders_count' => $pendingOrdersCount,
            ],
        ]);
    }

    /**
     * Control and update menu item stock & availability directly from Kasir (/kasir/stok) and sync to DB + Admin.
     */
    public function updateStock(Request $request): JsonResponse
    {
        self::ensureDatabaseSchemaAndSync();

        $validated = $request->validate([
            'menu_id' => ['required'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'delta' => ['nullable', 'integer', 'min:-999', 'max:999'],
        ]);

        $menuId = (string) $validated['menu_id'];
        $stockMap = $this->getStockMap();
        $menuModel = is_numeric($menuId) ? Menu::find((int) $menuId) : null;

        $currentStock = $menuModel && $menuModel->stock !== null
            ? (int) $menuModel->stock
            : (isset($stockMap[$menuId]) ? (int) $stockMap[$menuId] : 25);

        if (array_key_exists('stock', $validated) && $validated['stock'] !== null) {
            $newStock = max(0, (int) $validated['stock']);
        } elseif (array_key_exists('delta', $validated) && $validated['delta'] !== null) {
            $newStock = max(0, $currentStock + (int) $validated['delta']);
        } else {
            $newStock = $currentStock;
        }

        $stockMap[$menuId] = $newStock;
        $this->saveStockMap($stockMap);

        $isAvailable = $newStock > 0;
        $menuName = 'Menu';

        if ($menuModel) {
            $menuModel->update([
                'stock' => $newStock,
                'is_available' => $isAvailable,
            ]);
            $menuName = $menuModel->name;
        }

        return response()->json([
            'success' => true,
            'message' => $isAvailable
                ? "Stok {$menuName} diperbarui menjadi {$newStock} porsi (Tersinkron ke DB & Admin)."
                : "Stok {$menuName} diset HABIS (0) di DB & Admin.",
            'menu_id' => (int) $menuId,
            'stock' => $newStock,
            'is_available' => $isAvailable,
        ]);
    }

    /**
     * Live DB synchronization endpoint (/kasir/sinkron) so Kasir stays 100% synced with Admin & Customer Web orders.
     */
    public function syncData(): JsonResponse
    {
        self::ensureDatabaseSchemaAndSync();

        $catalogItems = $this->resolveKasirCatalog();
        $stocks = [];
        foreach ($catalogItems as $item) {
            $stocks[(string) $item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'stock' => $item['stock'],
                'is_available' => $item['is_available'],
            ];
        }

        $todayOrdersCount = 0;
        $todayRevenue = 0;
        $pendingOrdersCount = 0;
        $recentOrders = [];

        try {
            $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
            $todayRevenue = (int) Order::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            $pendingOrdersCount = Order::where('status', 'pending')->count();

            $recentOrders = Order::with(['items', 'table'])->latest()->take(25)->get()->map(fn (Order $o) => [
                'id' => $o->id,
                'code' => $o->code,
                'customer_name' => $o->customer_name,
                'order_type' => $o->order_type,
                'status' => $o->status,
                'status_label' => $o->status_label,
                'payment_method' => $o->payment_method ?? 'cash',
                'payment_status' => $o->payment_status ?? 'unpaid',
                'total' => $o->total,
                'notes' => $o->notes,
                'created_at_time' => $o->created_at?->format('H:i'),
                'receipt_url' => route('kasir.orders.receipt', $o->id),
                'items' => $o->items->map(fn (OrderItem $i) => [
                    'menu_name' => $i->menu_name,
                    'qty' => $i->qty,
                    'unit_price' => $i->unit_price,
                    'line_total' => $i->line_total,
                ])->values()->all(),
            ])->values()->all();
        } catch (\Throwable $e) {
        }

        return response()->json([
            'success' => true,
            'stats' => [
                'today_orders_count' => $todayOrdersCount,
                'today_revenue' => $todayRevenue,
                'pending_orders_count' => $pendingOrdersCount,
            ],
            'stocks' => $stocks,
            'recent_orders' => $recentOrders,
        ]);
    }

    /**
     * Lookup an order by QR code string or order code (AJAX or GET redirect).
     */
    public function lookupOrder(Request $request): JsonResponse|RedirectResponse
    {
        $rawCode = trim((string) $request->input('code', ''));
        $cleanCode = $this->extractOrderCode($rawCode);

        if ($cleanCode === '') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Masukkan atau scan kode pesanan terlebih dahulu.',
                ], 422);
            }

            return redirect()->route('kasir.index', ['tab' => 'scan']);
        }

        $order = Order::where('code', $cleanCode)->with(['items', 'table'])->first();

        if (! $order) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Pesanan dengan kode {$cleanCode} tidak ditemukan.",
                ], 404);
            }

            return redirect()->route('kasir.index', ['tab' => 'scan', 'code' => $cleanCode]);
        }

        if ($request->expectsJson()) {
            $host = $request->getHost();
            $port = $request->getPort();
            $scheme = $request->getScheme();
            $portSuffix = ($port && ! in_array($port, [80, 443], true)) ? ":{$port}" : '';

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'code' => $order->code,
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'order_type' => $order->order_type,
                    'table_number' => $order->table?->number,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'payment_method' => $order->payment_method ?? 'cash',
                    'payment_status' => $order->payment_status ?? 'unpaid',
                    'subtotal' => $order->subtotal,
                    'total' => $order->total,
                    'notes' => $order->notes,
                    'created_at' => $order->created_at?->format('d M Y, H:i'),
                    'items' => $order->items->map(fn (OrderItem $i) => [
                        'menu_name' => $i->menu_name,
                        'qty' => $i->qty,
                        'unit_price' => $i->unit_price,
                        'line_total' => $i->line_total,
                    ])->values()->all(),
                ],
                'qr_url' => "{$scheme}://{$host}{$portSuffix}/qr/pesanan/{$order->code}",
                'receipt_url' => route('kasir.orders.receipt', $order->id),
                'status_url' => route('kasir.orders.status', $order->id),
            ]);
        }

        return redirect()->route('kasir.index', ['tab' => 'scan', 'code' => $order->code]);
    }

    /**
     * Update an order's status and payment_status from the Kasir workspace.
     */
    public function updateOrderStatus(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,ready,completed,cancelled'],
            'payment_status' => ['nullable', 'string', 'in:unpaid,paid'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer'],
        ]);

        $updateData = ['status' => $validated['status']];
        if (! empty($validated['payment_status'])) {
            $updateData['payment_status'] = $validated['payment_status'];
        }
        if (! empty($validated['payment_method'])) {
            $updateData['payment_method'] = $validated['payment_method'];
        }

        $order->update($updateData);

        if ($request->expectsJson()) {
            $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
            $todayRevenue = (int) Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total');
            $pendingOrdersCount = Order::where('status', 'pending')->count();

            return response()->json([
                'success' => true,
                'message' => "Status pesanan #{$order->code} berhasil diperbarui di Database & Admin.",
                'order' => [
                    'id' => $order->id,
                    'code' => $order->code,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                ],
                'stats' => [
                    'today_orders_count' => $todayOrdersCount,
                    'today_revenue' => $todayRevenue,
                    'pending_orders_count' => $pendingOrdersCount,
                ],
            ]);
        }

        return back()->with('success', "Status pesanan #{$order->code} berhasil diperbarui.");
    }

    /**
     * Render thermal 80mm printable receipt for Kasir.
     */
    public function receipt(Order $order): View
    {
        $order->load(['table', 'items']);

        return view('admin.orders.receipt', compact('order'));
    }

    /**
     * Extract clean order code (e.g. NCM-20261102-001) from raw input or scanned QR URL.
     */
    protected function extractOrderCode(string $raw): string
    {
        $trimmed = trim($raw);
        if ($trimmed === '') {
            return '';
        }

        if (preg_match('/(NCM-\d{8}-\d+)/i', $trimmed, $matches)) {
            return strtoupper($matches[1]);
        }

        if (str_contains($trimmed, '/')) {
            $segments = array_values(array_filter(explode('/', parse_url($trimmed, PHP_URL_PATH) ?: $trimmed)));
            $last = end($segments);
            if (is_string($last) && $last !== '') {
                return strtoupper($last);
            }
        }

        return strtoupper($trimmed);
    }

    /**
     * Load persistent menu stock map from settings.
     *
     * @return array<string, int>
     */
    protected function getStockMap(): array
    {
        try {
            $raw = Setting::get('kasir_menu_stocks');
            if ($raw) {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    return array_map('intval', $decoded);
                }
            }
        } catch (\Throwable $e) {
            // Fallback to empty array
        }

        return [];
    }

    /**
     * Save persistent menu stock map to settings.
     *
     * @param  array<string, int>  $stockMap
     */
    protected function saveStockMap(array $stockMap): void
    {
        try {
            Setting::set('kasir_menu_stocks', json_encode($stockMap));
        } catch (\Throwable $e) {
            // Gracefully ignore if settings table is not ready
        }
    }

    /**
     * Resolve all menus with stock numbers, high-clarity images, and normalized category grouping.
     *
     * @return Collection<int, array<string, mixed>>
     */
    protected function resolveKasirCatalog(): Collection
    {
        $configMenu = collect(config('nucomu.all_menu', []));
        $configImageMap = [];
        foreach ($configMenu as $cm) {
            if (! empty($cm['image'])) {
                $configImageMap[strtolower((string) ($cm['id'] ?? ''))] = $cm['image'];
                $configImageMap[strtolower((string) ($cm['name'] ?? ''))] = $cm['image'];
            }
        }

        $stockMap = $this->getStockMap();
        $items = collect();

        try {
            $dbMenus = Menu::with('category')->orderBy('sort_order')->get();

            if ($dbMenus->isNotEmpty()) {
                $items = $dbMenus->map(function (Menu $m) use ($configImageMap, $stockMap) {
                    $rawCatName = $m->category?->name ?? 'Minuman';
                    $rawCatSlug = strtolower($m->category?->slug ?? $rawCatName);
                    $categorization = $this->mapCategoryGroup($rawCatSlug, $rawCatName, $m->name);

                    $idKey = (string) $m->id;
                    $defaultStock = $m->is_available ? 25 : 0;
                    $stock = $m->stock !== null
                        ? (int) $m->stock
                        : (array_key_exists($idKey, $stockMap) ? (int) $stockMap[$idKey] : $defaultStock);
                    if (! $m->is_available) {
                        $stock = 0;
                    }
                    $isAvailable = $stock > 0 && (bool) $m->is_available;

                    return [
                        'id' => $m->id,
                        'name' => $m->name,
                        'slug' => $m->slug ?: 'menu-'.$m->id,
                        'price' => (int) $m->price,
                        'price_formatted' => 'Rp '.number_format((int) $m->price, 0, ',', '.'),
                        'description' => $m->description ?: 'Racikan pilihan Nucomu Cafe.',
                        'image' => $this->resolveMenuImage($m->image, $m->slug, $m->name, $categorization['group_key'], $configImageMap),
                        'group_key' => $categorization['group_key'],
                        'group_label' => $categorization['group_label'],
                        'sub_category' => $categorization['sub_category'],
                        'stock' => $stock,
                        'is_available' => $isAvailable,
                    ];
                });
            }
        } catch (\Throwable $e) {
            // Fallback to config menu below
        }

        if ($items->isEmpty()) {
            $items = $configMenu->values()->map(function (array $m, int $index) use ($configImageMap, $stockMap) {
                $rawCatSlug = strtolower((string) ($m['category'] ?? 'coffee'));
                $rawCatName = (string) ($m['category_label'] ?? ucfirst($rawCatSlug));
                $name = (string) ($m['name'] ?? 'Menu Nucomu');
                $slug = (string) ($m['id'] ?? 'menu-'.($index + 1));
                $categorization = $this->mapCategoryGroup($rawCatSlug, $rawCatName, $name);
                $id = $index + 1;
                $idKey = (string) $id;
                $stock = array_key_exists($idKey, $stockMap) ? (int) $stockMap[$idKey] : 25;

                return [
                    'id' => $id,
                    'name' => $name,
                    'slug' => $slug,
                    'price' => (int) ($m['price'] ?? 0),
                    'price_formatted' => 'Rp '.number_format((int) ($m['price'] ?? 0), 0, ',', '.'),
                    'description' => (string) ($m['short_info'] ?? 'Racikan pilihan Nucomu Cafe.'),
                    'image' => $this->resolveMenuImage($m['image'] ?? null, $slug, $name, $categorization['group_key'], $configImageMap),
                    'group_key' => $categorization['group_key'],
                    'group_label' => $categorization['group_label'],
                    'sub_category' => $categorization['sub_category'],
                    'stock' => $stock,
                    'is_available' => $stock > 0,
                ];
            });
        }

        return $items;
    }

    /**
     * Map any category slug/name into clean, consistent Kasir categories.
     * Example: Kopi, Non-Kopi, Coffee, Beverages -> grouped under "Minuman" so all drinks are in Minuman.
     *
     * @return array{group_key: string, group_label: string, sub_category: string}
     */
    protected function mapCategoryGroup(string $catSlug, string $catName, string $menuName = ''): array
    {
        $slug = strtolower(trim($catSlug));
        $nameLower = strtolower(trim($catName));
        $menuLower = strtolower(trim($menuName));

        // 1. Minuman (Kopi, Non-Kopi, Coffee, Non-Coffee, Tea, Matcha, Milk, Beverage, Minuman)
        if (
            str_contains($slug, 'kopi') ||
            str_contains($slug, 'coffee') ||
            str_contains($slug, 'minum') ||
            str_contains($slug, 'beverage') ||
            str_contains($slug, 'drink') ||
            str_contains($slug, 'tea') ||
            str_contains($nameLower, 'kopi') ||
            str_contains($nameLower, 'coffee') ||
            str_contains($nameLower, 'minum') ||
            str_contains($nameLower, 'beverage')
        ) {
            $isNonKopi = str_contains($slug, 'non') || str_contains($nameLower, 'non') || str_contains($menuLower, 'tea') || str_contains($menuLower, 'matcha') || str_contains($menuLower, 'smoothie') || str_contains($menuLower, 'taro') || str_contains($menuLower, 'chocolate milk');

            return [
                'group_key' => 'minuman',
                'group_label' => 'Minuman',
                'sub_category' => $isNonKopi ? 'Non-Kopi' : 'Kopi',
            ];
        }

        // 2. Makanan Berat (Makanan, Meals, Main Course, Rice, Pasta)
        if (
            str_contains($slug, 'makan') ||
            str_contains($slug, 'meal') ||
            str_contains($slug, 'main') ||
            str_contains($nameLower, 'makan') ||
            str_contains($nameLower, 'meal') ||
            str_contains($menuLower, 'spaghetti') ||
            str_contains($menuLower, 'aglio') ||
            str_contains($menuLower, 'pasta') ||
            str_contains($menuLower, 'nasi') ||
            str_contains($menuLower, 'rice') ||
            str_contains($menuLower, 'steak')
        ) {
            return [
                'group_key' => 'makanan',
                'group_label' => 'Makanan',
                'sub_category' => 'Makanan Utama',
            ];
        }

        // 3. Snack / Kudapan
        if (
            str_contains($slug, 'snack') ||
            str_contains($slug, 'bite') ||
            str_contains($slug, 'toast') ||
            str_contains($nameLower, 'snack')
        ) {
            return [
                'group_key' => 'snack',
                'group_label' => 'Snack',
                'sub_category' => 'Snack & Camilan',
            ];
        }

        // 4. Dessert / Pastry
        if (
            str_contains($slug, 'dessert') ||
            str_contains($slug, 'pastry') ||
            str_contains($slug, 'cake') ||
            str_contains($slug, 'sweet') ||
            str_contains($nameLower, 'dessert')
        ) {
            return [
                'group_key' => 'dessert',
                'group_label' => 'Dessert',
                'sub_category' => 'Artisanal Dessert',
            ];
        }

        return [
            'group_key' => $slug !== '' ? $slug : 'lainnya',
            'group_label' => $catName !== '' ? $catName : 'Lainnya',
            'sub_category' => $catName !== '' ? $catName : 'Menu',
        ];
    }

    /**
     * Group catalog items by category in logical cashier order (Minuman, Makanan, Snack, Dessert, ...).
     *
     * @param  Collection<int, array<string, mixed>>  $catalogItems
     * @return Collection<int, array{key: string, label: string, icon: string, description: string, items: Collection<int, array<string, mixed>>}>
     */
    protected function groupCatalogByCategory(Collection $catalogItems): Collection
    {
        $categoryMeta = [
            'minuman' => [
                'label' => 'Minuman',
                'icon' => 'fa-mug-hot',
                'description' => 'Kopi Signature, Espresso Base, Matcha, Teh & Minuman Segar',
                'order' => 1,
            ],
            'makanan' => [
                'label' => 'Makanan',
                'icon' => 'fa-bowl-food',
                'description' => 'Hidangan Utama, Rice Bowl, Nasi & Pasta',
                'order' => 2,
            ],
            'snack' => [
                'label' => 'Snack',
                'icon' => 'fa-cookie-bite',
                'description' => 'Camilan Hangat, Kentang Goreng, Roti Bakar & Light Bites',
                'order' => 3,
            ],
            'dessert' => [
                'label' => 'Dessert',
                'icon' => 'fa-cake-candles',
                'description' => 'Artisanal Cake, Tiramisu, Cheesecake & Sweet Treats',
                'order' => 4,
            ],
        ];

        return $catalogItems
            ->groupBy('group_key')
            ->map(function (Collection $groupItems, string $key) use ($categoryMeta) {
                $first = $groupItems->first();
                $meta = $categoryMeta[$key] ?? [
                    'label' => $first['group_label'] ?? ucfirst($key),
                    'icon' => 'fa-utensils',
                    'description' => 'Katalog sajian '.($first['group_label'] ?? ucfirst($key)),
                    'order' => 99,
                ];

                return [
                    'key' => $key,
                    'label' => $meta['label'],
                    'icon' => $meta['icon'],
                    'description' => $meta['description'],
                    'order' => $meta['order'],
                    'items' => $groupItems->values(),
                ];
            })
            ->sortBy('order')
            ->values();
    }

    /**
     * Resolve menu image with curated fallback photos so every menu box in /kasir has a clear photo.
     *
     * @param  array<string, string>  $configImageMap
     */
    protected function resolveMenuImage(?string $rawImage, ?string $slug, string $name, string $groupKey, array $configImageMap): string
    {
        if ($rawImage) {
            if (file_exists(public_path('storage/'.$rawImage))) {
                return asset('storage/'.$rawImage);
            }
            if (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://')) {
                return $rawImage;
            }
            if (file_exists(public_path(ltrim($rawImage, '/')))) {
                return asset(ltrim($rawImage, '/'));
            }
        }

        $slugKey = strtolower(trim((string) $slug));
        $nameKey = strtolower(trim($name));

        if (isset($configImageMap[$slugKey])) {
            return $configImageMap[$slugKey];
        }
        if (isset($configImageMap[$nameKey])) {
            return $configImageMap[$nameKey];
        }

        $curatedPhotos = [
            'banoffee' => 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=600&q=80',
            'cheesecake' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=600&q=80',
            'tiramisu' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=600&q=80',
            'donut-choco-pistachio' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80',
            'brownies-fudge' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=600&q=80',
            'pudding-matcha' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80',
            'crepe-cake-berry' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=600&q=80',
            'nasi-cakalang-suwir' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=600&q=80',
            'rice-bowl-ayam-cabe-garam' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
            'nasi-goreng-kampung' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=600&q=80',
            'sandwich-club' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=600&q=80',
            'pasta-aglio-olio' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=600&q=80',
            'kentang-goreng-crispy' => 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=600&q=80',
            'roti-bakar-cokelat-keju' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=600&q=80',
            'pisang-goreng-kremes' => 'https://images.unsplash.com/photo-1621996346565-e3d5d6281298?auto=format&fit=crop&w=600&q=80',
            'chicken-nugget' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=600&q=80',
            'nucomu-coffee' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80',
            'butterscloud' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80',
            'americano' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=600&q=80',
            'cappuccino' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&w=600&q=80',
            'caramel-latte' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=600&q=80',
            'cold-brew' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=600&q=80',
            'lychee-tea' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80',
            'matcha' => 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?auto=format&fit=crop&w=600&q=80',
            'chocolate-milk' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=600&q=80',
            'strawberry-smoothie' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=600&q=80',
            'taro-milk' => 'https://images.unsplash.com/photo-1558857563-b371033873b8?auto=format&fit=crop&w=600&q=80',
            'brown-sugar-milk' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?auto=format&fit=crop&w=600&q=80',
        ];

        foreach ($curatedPhotos as $key => $url) {
            if ($slugKey === $key || str_starts_with($slugKey, $key.'-') || str_contains($nameKey, str_replace('-', ' ', $key))) {
                return $url;
            }
        }

        return match ($groupKey) {
            'minuman' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80',
            'makanan' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
            'snack' => 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=600&q=80',
            'dessert' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=600&q=80',
            default => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80',
        };
    }

    /**
     * Detect local network IP (WiFi / Ethernet) from system ipconfig for mobile QR scanning.
     */
    protected function detectLocalNetworkIp(): ?string
    {
        try {
            $lines = [];
            @exec('ipconfig', $lines);
            $lanIp = null;
            $fallbackIp = null;

            foreach ($lines as $line) {
                if (preg_match('/IPv4 Address[ .:]+([\d\.]+)/i', $line, $matches)) {
                    $ip = trim($matches[1]);
                    if ($ip === '127.0.0.1' || str_starts_with($ip, '169.254.')) {
                        continue;
                    }
                    if (str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
                        $lanIp = $ip;
                        break;
                    }
                    if (! $fallbackIp) {
                        $fallbackIp = $ip;
                    }
                }
            }

            return $lanIp ?? $fallbackIp ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
