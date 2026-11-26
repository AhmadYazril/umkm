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
                        // Do not overwrite an active Admin session when Kasir background sync runs
                        if (! Auth::check()) {
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
            Order::ensureChannelColumnsExist();

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
                $isAvailable = $newStock > 0;

                $stockMap[$idKey] = $newStock;
                $updatedStocks[$idKey] = $newStock;

                try {
                    $menuModel->update([
                        'stock' => $newStock,
                        'is_available' => $isAvailable,
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
     * Restore ordered quantities back to both the database Menu table and Kasir stock map
     * (used when editing pending order items or cancelling an order).
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, int>
     */
    public static function restoreStockForOrderItems(array $items): array
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

                $newStock = max(0, $currentStock + $qty);
                $isAvailable = $newStock > 0;

                $stockMap[$idKey] = $newStock;
                $updatedStocks[$idKey] = $newStock;

                try {
                    $menuModel->update([
                        'stock' => $newStock,
                        'is_available' => $isAvailable,
                    ]);
                } catch (\Throwable $e) {
                }
            } elseif ($midRaw !== null && (string) $midRaw !== '') {
                $mid = (string) $midRaw;
                $currentStock = isset($stockMap[$mid]) ? (int) $stockMap[$mid] : 25;
                $newStock = max(0, $currentStock + $qty);

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
        $bestSellerItems = $catalogItems
            ->filter(fn (array $item): bool => (bool) ($item['is_best_seller'] ?? false))
            ->values();

        $qrisImageUrl = null;
        try {
            $rawQris = Setting::get('qris_image');
            if (! empty($rawQris) && file_exists(public_path(ltrim($rawQris, '/')))) {
                $qrisImageUrl = asset(ltrim($rawQris, '/'));
            }
        } catch (\Throwable $e) {
            // Fallback cleanly when settings table is unavailable
        }

        $scannedOrder = null;
        $scannedOrderPayload = null;
        $rawCode = trim((string) $request->query('code', ''));
        if ($rawCode !== '') {
            $cleanCode = $this->extractOrderCode($rawCode);
            $scannedOrder = Order::where('code', $cleanCode)->with(['items', 'table', 'zendoDelivery'])->first();
            if ($scannedOrder) {
                $scannedOrderPayload = $this->formatOrderForKasir($scannedOrder);
            }
        }

        $recentOrders = collect();
        $recentOrdersPayload = [];
        $tables = collect();
        $todayOrdersCount = 0;
        $todayRevenue = 0;
        $pendingOrdersCount = 0;

        try {
<<<<<<< HEAD
            $recentOrders = Order::with(['items', 'table'])->latest()->take(60)->get();
=======
            $recentOrders = Order::with(['items', 'table', 'zendoDelivery'])->latest()->take(50)->get();
            $recentOrdersPayload = $recentOrders->map(fn (Order $o): array => $this->formatOrderForKasir($o))->values()->all();
>>>>>>> 5e565ecbf7a31fdc5f79c6a55f162c428fa8b4e9
            $tables = Table::orderBy('number')->get();
            $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
            $todayRevenue = (int) Order::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            $pendingOrdersCount = $this->countUnpaidOrders();
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
            'bestSellerItems',
            'qrisImageUrl',
            'scannedOrder',
            'scannedOrderPayload',
            'recentOrders',
            'recentOrdersPayload',
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
            'order_channel' => ['nullable', 'string', 'in:direct,zendo,grab'],
            'barcode_type' => ['nullable', 'string', 'in:qr,barcode'],
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
            'items.*.options' => ['nullable', 'array'],
            'items.*.item_notes' => ['nullable', 'string', 'max:255'],
        ]);

        $orderChannel = strtolower((string) ($validated['order_channel'] ?? Order::CHANNEL_DIRECT));
        if (! in_array($orderChannel, [Order::CHANNEL_DIRECT, Order::CHANNEL_ZENDO, Order::CHANNEL_GRAB], true)) {
            $orderChannel = Order::CHANNEL_DIRECT;
        }

        // Only direct (Di Tempat) transactions use QR/1D barcodes; Zendo & Grab never use barcodes
        $barcodeType = $orderChannel === Order::CHANNEL_DIRECT
            ? (($validated['barcode_type'] ?? Order::BARCODE_QR) === Order::BARCODE_LINEAR ? Order::BARCODE_LINEAR : Order::BARCODE_QR)
            : null;

        $defaultCustomerName = match ($orderChannel) {
            Order::CHANNEL_ZENDO => 'Pelanggan Zendo',
            Order::CHANNEL_GRAB => 'Pelanggan GrabFood',
            default => 'Pelanggan Kasir',
        };

        $customerName = trim((string) ($validated['customer_name'] ?? '')) ?: $defaultCustomerName;
        $customerPhone = trim((string) ($validated['customer_phone'] ?? '')) ?: '-';
        $orderType = $orderChannel === Order::CHANNEL_DIRECT ? $validated['order_type'] : 'take_away';
        $tableNo = $orderChannel === Order::CHANNEL_DIRECT ? trim((string) ($validated['table_no'] ?? '')) : '';
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

            $storedOptions = $this->normalizeOrderItemOptions(
                is_array($item['options'] ?? null) ? $item['options'] : [],
                (string) ($item['item_notes'] ?? '')
            );

            $normalizedItems[] = [
                'menu_id' => $item['menu_id'] ?? null,
                'menu_name' => $item['menu_name'],
                'unit_price' => $unitPrice,
                'qty' => $qty,
                'options' => $storedOptions,
                'line_total' => $lineTotal,
            ];
        }

        $grandTotal = $subtotal;
        $cashReceived = $paymentMethod === 'cash' && isset($validated['cash_received']) && $validated['cash_received'] > 0
            ? (int) $validated['cash_received']
            : $grandTotal;
        $changeAmount = $paymentMethod === 'cash' ? max(0, $cashReceived - $grandTotal) : 0;

        $noteParts = [];
        if ($orderChannel === Order::CHANNEL_ZENDO) {
            $noteParts[] = '[KURIR ZENDO] [KASIR ZENDO]';
        } elseif ($orderChannel === Order::CHANNEL_GRAB) {
            $noteParts[] = '[GRABFOOD] [KASIR GRAB]';
        } elseif ($orderType === 'dine_in' && $tableNo !== '') {
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
<<<<<<< HEAD
            $orderChannel,
            $barcodeType,
=======
>>>>>>> 5e565ecbf7a31fdc5f79c6a55f162c428fa8b4e9
            $tableNo,
            $storedNotes,
            $subtotal,
            $grandTotal,
            $paymentMethod,
            $paymentStatus,
            $cashReceived,
            $changeAmount,
            $normalizedItems
        ) {
            $date = Carbon::now()->format('Ymd');
            $count = Order::whereDate('created_at', today())->count() + 1;
            $code = 'NCM-'.$date.'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);

            while (Order::where('code', $code)->exists()) {
                $count++;
                $code = 'NCM-'.$date.'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
            }

<<<<<<< HEAD
            $orderPayload = [
=======
            $formData = array_filter([
                'table_no' => $orderType === 'dine_in' && $tableNo !== '' ? $tableNo : null,
                'cash_received' => $paymentStatus === 'paid' ? $cashReceived : null,
                'change_amount' => $paymentStatus === 'paid' ? $changeAmount : null,
            ], static fn (mixed $v): bool => $v !== null);

            $order = Order::create([
>>>>>>> 5e565ecbf7a31fdc5f79c6a55f162c428fa8b4e9
                'code' => $code,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'order_type' => $orderType,
                'form_data' => [
                    'channel' => $orderChannel,
                    'barcode_type' => $barcodeType,
                    'source' => 'kasir',
                    'table_no' => $tableNo,
                ],
                'notes' => $storedNotes,
                'form_data' => $formData !== [] ? $formData : null,
                'subtotal' => $subtotal,
                'total' => $grandTotal,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
<<<<<<< HEAD
                'status' => $paymentStatus === 'paid' ? 'processing' : 'pending',
            ];

            if (Schema::hasColumn('orders', 'order_channel')) {
                $orderPayload['order_channel'] = $orderChannel;
            }
            if (Schema::hasColumn('orders', 'barcode_type')) {
                $orderPayload['barcode_type'] = $barcodeType;
            }

            $order = Order::create($orderPayload);
=======
                'status' => $paymentStatus === 'paid' ? 'completed' : 'pending',
            ]);
>>>>>>> 5e565ecbf7a31fdc5f79c6a55f162c428fa8b4e9

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
                    'options' => $item['options'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order->load('items');
        });

        // Automatically deduct ordered quantities from DB Menu & Kasir stock map
        $updatedStocks = self::deductStockForOrderItems($normalizedItems);

        $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
        $todayRevenue = (int) Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total');
        $pendingOrdersCount = $this->countUnpaidOrders();

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
        $qrUrl = $order->uses_barcode ? "{$scheme}://{$host}{$portSuffix}/qr/pesanan/{$order->code}" : null;

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan ke Database & Admin.',
            'order' => [
                'id' => $order->id,
                'code' => $order->code,
                'customer_name' => $order->customer_name,
                'order_type' => $order->order_type,
                'order_channel' => $order->channel,
                'channel_label' => $order->channel_label,
                'channel_logo' => $order->channel_logo,
                'uses_barcode' => $order->uses_barcode,
                'barcode_type' => $order->effective_barcode_type,
                'table_no' => $tableNo,
                'payment_method' => $order->payment_method,
                'payment_method_label' => $order->payment_method_label,
                'payment_status' => $order->payment_status,
                'status' => $order->status,
                'kasir_bucket' => $order->status === 'completed' ? 'completed' : 'unpaid',
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
                    'options' => $i->options ?? [],
                    'line_total' => $i->line_total,
                ])->values()->all(),
            ],
            'qr_url' => $qrUrl,
            'slip_url' => $order->uses_barcode
                ? route('nucomu.order.slip', [
                    'code' => $order->code,
                    'method' => 'kasir',
                    'barcode_mode' => $order->effective_barcode_type ?? Order::BARCODE_QR,
                ])
                : null,
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
            'is_available' => ['nullable', 'boolean'],
            'toggle' => ['nullable', 'boolean'],
        ]);

        $menuId = (string) $validated['menu_id'];
        $stockMap = $this->getStockMap();
        $menuModel = is_numeric($menuId) ? Menu::find((int) $menuId) : null;

        $currentAvailable = $menuModel
            ? (bool) $menuModel->is_available
            : ((int) ($stockMap[$menuId] ?? 25) > 0);

        if (array_key_exists('stock', $validated) && $validated['stock'] !== null) {
            $newStock = max(0, (int) $validated['stock']);
            $isAvailable = $newStock > 0;
        } elseif (! empty($validated['toggle'])) {
            $isAvailable = ! $currentAvailable;
            $newStock = $isAvailable ? 25 : 0;
        } elseif (array_key_exists('is_available', $validated) && $validated['is_available'] !== null) {
            $isAvailable = (bool) $validated['is_available'];
            $newStock = $isAvailable ? 25 : 0;
        } elseif (array_key_exists('delta', $validated) && $validated['delta'] !== null) {
            $isAvailable = ($currentAvailable ? 25 : 0) + (int) $validated['delta'] > 0;
            $newStock = $isAvailable ? 25 : 0;
        } else {
            $isAvailable = $currentAvailable;
            $newStock = $isAvailable ? 25 : 0;
        }

        $stockMap[$menuId] = $newStock;
        $this->saveStockMap($stockMap);

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
                ? "Status {$menuName} diubah menjadi TERSEDIA (Tersinkron ke DB & Admin)."
                : "Status {$menuName} diubah menjadi HABIS (Tersinkron ke DB & Admin).",
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
            $isAvail = (bool) $item['is_available'] && (int) $item['stock'] > 0;
            $stocks[(string) $item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'stock' => (int) $item['stock'],
                'is_available' => $isAvail,
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
            $pendingOrdersCount = $this->countUnpaidOrders();

<<<<<<< HEAD
            $recentOrders = Order::with(['items', 'table'])->latest()->take(60)->get()->map(fn (Order $o) => [
                'id' => $o->id,
                'code' => $o->code,
                'customer_name' => $o->customer_name,
                'order_type' => $o->order_type,
                'order_channel' => $o->channel,
                'channel_label' => $o->channel_label,
                'channel_logo' => $o->channel_logo,
                'uses_barcode' => $o->uses_barcode,
                'barcode_type' => $o->effective_barcode_type,
                'status' => $o->status,
                'status_label' => $o->status_label,
                'payment_method' => $o->payment_method ?? 'cash',
                'payment_status' => $o->payment_status ?? 'unpaid',
                'total' => $o->total,
                'notes' => $o->notes,
                'created_at_display' => $o->created_at?->format('d M H:i'),
                'created_at_time' => $o->created_at?->format('H:i'),
                'receipt_url' => route('kasir.orders.receipt', $o->id),
                'items' => $o->items->map(fn (OrderItem $i) => [
                    'menu_name' => $i->menu_name,
                    'qty' => $i->qty,
                    'unit_price' => $i->unit_price,
                    'options' => $i->options ?? [],
                    'line_total' => $i->line_total,
                ])->values()->all(),
            ])->values()->all();
=======
            $recentOrders = Order::with(['items', 'table', 'zendoDelivery'])
                ->latest()
                ->take(50)
                ->get()
                ->map(fn (Order $o): array => $this->formatOrderForKasir($o))
                ->values()
                ->all();
>>>>>>> 5e565ecbf7a31fdc5f79c6a55f162c428fa8b4e9
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

        $order = Order::where('code', $cleanCode)->with(['items', 'table', 'zendoDelivery'])->first();

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
<<<<<<< HEAD
                'order' => [
                    'id' => $order->id,
                    'code' => $order->code,
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'order_type' => $order->order_type,
                    'order_channel' => $order->channel,
                    'channel_label' => $order->channel_label,
                    'channel_logo' => $order->channel_logo,
                    'uses_barcode' => $order->uses_barcode,
                    'barcode_type' => $order->effective_barcode_type,
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
                        'options' => $i->options ?? [],
                        'line_total' => $i->line_total,
                    ])->values()->all(),
                ],
                'qr_url' => $order->uses_barcode ? "{$scheme}://{$host}{$portSuffix}/qr/pesanan/{$order->code}" : null,
=======
                'order' => $this->formatOrderForKasir($order),
                'qr_url' => "{$scheme}://{$host}{$portSuffix}/qr/pesanan/{$order->code}",
>>>>>>> 5e565ecbf7a31fdc5f79c6a55f162c428fa8b4e9
                'receipt_url' => route('kasir.orders.receipt', $order->id),
                'status_url' => route('kasir.orders.status', $order->id),
                'update_items_url' => route('kasir.orders.updateItems', $order->id),
            ]);
        }

        return redirect()->route('kasir.index', ['tab' => 'scan', 'code' => $order->code]);
    }

    /**
     * Edit items of a pending, unpaid order directly from Kasir (e.g., when a Zendo courier arrives with customer revisions).
     */
    public function updateOrderItems(Request $request, Order $order): JsonResponse
    {
        self::ensureDatabaseSchemaAndSync();
        $order->load(['items', 'table', 'zendoDelivery']);

        if (! $order->canBeEditedAtKasir()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan yang sudah dibayar, selesai, atau dibatalkan tidak dapat diubah.',
            ], 422);
        }

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['nullable'],
            'items.*.menu_name' => ['required', 'string', 'max:255'],
            'items.*.unit_price' => ['required', 'integer', 'min:0'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.options' => ['nullable', 'array'],
            'items.*.item_notes' => ['nullable', 'string', 'max:255'],
            'mark_completed_paid' => ['nullable', 'boolean'],
            'mark_processing_paid' => ['nullable', 'boolean'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer'],
        ], [
            'items.required' => 'Pesanan harus memiliki minimal 1 item menu.',
            'items.min' => 'Pesanan harus memiliki minimal 1 item menu.',
        ]);

        $oldQtyByMenuId = [];
        $oldItemsPayload = [];
        foreach ($order->items as $existingItem) {
            if ($existingItem->menu_id !== null) {
                $midKey = (string) $existingItem->menu_id;
                $oldQtyByMenuId[$midKey] = ($oldQtyByMenuId[$midKey] ?? 0) + (int) $existingItem->qty;
            }
            $oldItemsPayload[] = [
                'menu_id' => $existingItem->menu_id,
                'menu_name' => $existingItem->menu_name,
                'qty' => (int) $existingItem->qty,
            ];
        }

        $subtotal = 0;
        $normalizedItems = [];

        foreach ($validated['items'] as $item) {
            $menuIdVal = $item['menu_id'] ?? null;
            $menuName = trim((string) $item['menu_name']);
            $qty = (int) $item['qty'];
            $unitPrice = (int) $item['unit_price'];

            $menuModel = null;
            if (is_numeric($menuIdVal)) {
                $menuModel = Menu::find((int) $menuIdVal);
            }
            if (! $menuModel && $menuName !== '') {
                $menuModel = Menu::whereRaw('LOWER(name) = ?', [strtolower($menuName)])->first();
            }

            if ($menuModel) {
                $midKey = (string) $menuModel->id;
                $oldQtyForMenu = $oldQtyByMenuId[$midKey] ?? 0;
                $currentStock = $menuModel->stock !== null ? (int) $menuModel->stock : ($menuModel->is_available ? 25 : 0);

                if ($oldQtyForMenu === 0 && (! $menuModel->is_available || $currentStock <= 0)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Menu '{$menuModel->name}' sedang habis dan tidak dapat ditambahkan.",
                    ], 422);
                }

                $validMenuId = $menuModel->id;
                $menuName = $menuModel->name;
                if ($unitPrice <= 0 && (int) $menuModel->price > 0) {
                    $unitPrice = (int) $menuModel->price;
                }
            } else {
                $validMenuId = is_numeric($menuIdVal) && Menu::whereKey($menuIdVal)->exists()
                    ? (int) $menuIdVal
                    : null;
            }

            $storedOptions = $this->normalizeOrderItemOptions(
                is_array($item['options'] ?? null) ? $item['options'] : [],
                (string) ($item['item_notes'] ?? '')
            );

            $lineTotal = $unitPrice * $qty;
            $subtotal += $lineTotal;

            $normalizedItems[] = [
                'menu_id' => $validMenuId,
                'menu_name' => $menuName,
                'unit_price' => $unitPrice,
                'qty' => $qty,
                'options' => $storedOptions,
                'line_total' => $lineTotal,
            ];
        }

        $deliveryFee = $order->effective_delivery_fee;
        $grandTotal = $subtotal + $deliveryFee;
        $markCompletedPaid = $request->boolean('mark_completed_paid') || $request->boolean('mark_processing_paid');

        DB::transaction(function () use ($order, $normalizedItems, $subtotal, $deliveryFee, $grandTotal, $markCompletedPaid, $validated): void {
            $order->items()->delete();

            foreach ($normalizedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'menu_name' => $item['menu_name'],
                    'unit_price' => $item['unit_price'],
                    'qty' => $item['qty'],
                    'options' => $item['options'],
                    'line_total' => $item['line_total'],
                ]);
            }

            $orderUpdates = [
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $grandTotal,
            ];

            if ($markCompletedPaid) {
                $payableAfterEdit = $order->isZendo() ? $subtotal : $grandTotal;
                $existingFormData = is_array($order->form_data) ? $order->form_data : [];
                $existingFormData['cash_received'] = $payableAfterEdit;
                $existingFormData['change_amount'] = 0;

                $orderUpdates['status'] = 'completed';
                $orderUpdates['payment_status'] = 'paid';
                $orderUpdates['form_data'] = $existingFormData;
                if (! empty($validated['payment_method'])) {
                    $orderUpdates['payment_method'] = $validated['payment_method'];
                }
            }

            $order->update($orderUpdates);
        });

        $restoredStocks = self::restoreStockForOrderItems($oldItemsPayload);
        $deductedStocks = self::deductStockForOrderItems($normalizedItems);
        $updatedStocks = array_replace($restoredStocks, $deductedStocks);

        $order->refresh();
        $order->load(['items', 'table', 'zendoDelivery']);

        $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
        $todayRevenue = (int) Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total');
        $pendingOrdersCount = $this->countUnpaidOrders();

        return response()->json([
            'success' => true,
            'message' => $markCompletedPaid
                ? "Pesanan #{$order->code} berhasil diubah & diselesaikan (Lunas)."
                : "Pesanan #{$order->code} berhasil diperbarui.",
            'order' => $this->formatOrderForKasir($order),
            'stocks' => $updatedStocks,
            'stats' => [
                'today_orders_count' => $todayOrdersCount,
                'today_revenue' => $todayRevenue,
                'pending_orders_count' => $pendingOrdersCount,
            ],
        ]);
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
            'cash_received' => ['nullable', 'integer', 'min:0'],
        ]);

        $order->loadMissing(['items', 'table', 'zendoDelivery']);
        $previousStatus = $order->status;

        $effectivePaymentMethod = ! empty($validated['payment_method'])
            ? $validated['payment_method']
            : ($order->payment_method ?? 'cash');
        $effectivePaymentStatus = ! empty($validated['payment_status'])
            ? $validated['payment_status']
            : ($order->payment_status ?? 'unpaid');

        // Untuk Kurir Zendo, nominal yang dibayar kurir ke kafe adalah subtotal menu (ongkir milik kurir)
        $payableAmount = $order->isZendo() ? $order->effective_subtotal : (int) $order->total;
        $cashReceived = isset($validated['cash_received']) ? (int) $validated['cash_received'] : null;

        if ($effectivePaymentMethod === 'cash' && $effectivePaymentStatus === 'paid' && $cashReceived !== null && $cashReceived > 0 && $cashReceived < $payableAmount) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uang yang diterima kurang dari total tagihan.',
                ], 422);
            }

            return back()->withErrors(['cash_received' => 'Uang yang diterima kurang dari total tagihan.']);
        }

        $recordedCash = $effectivePaymentMethod === 'cash' && $cashReceived !== null && $cashReceived > 0
            ? $cashReceived
            : $payableAmount;
        $changeAmount = $effectivePaymentMethod === 'cash'
            ? max(0, $recordedCash - $payableAmount)
            : 0;

        $updateData = ['status' => $validated['status']];
        if (! empty($validated['payment_status'])) {
            $updateData['payment_status'] = $validated['payment_status'];
        }
        if (! empty($validated['payment_method'])) {
            $updateData['payment_method'] = $validated['payment_method'];
        }

        if ($effectivePaymentStatus === 'paid' && $validated['status'] !== 'cancelled') {
            $existingFormData = is_array($order->form_data) ? $order->form_data : [];
            $existingFormData['cash_received'] = $recordedCash;
            $existingFormData['change_amount'] = $changeAmount;
            $updateData['form_data'] = $existingFormData;
        }

        $order->update($updateData);

        $updatedStocks = [];
        $itemsPayload = $order->items->map(fn (OrderItem $i): array => [
            'menu_id' => $i->menu_id,
            'menu_name' => $i->menu_name,
            'qty' => (int) $i->qty,
        ])->all();

        if ($previousStatus !== 'cancelled' && $order->status === 'cancelled') {
            $updatedStocks = self::restoreStockForOrderItems($itemsPayload);
        } elseif ($previousStatus === 'cancelled' && $order->status !== 'cancelled') {
            $updatedStocks = self::deductStockForOrderItems($itemsPayload);
        }

        $order->refresh();
        $order->load(['items', 'table', 'zendoDelivery']);

        if ($request->expectsJson()) {
            $todayOrdersCount = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->count();
            $todayRevenue = (int) Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total');
            $pendingOrdersCount = $this->countUnpaidOrders();

            $message = match ($order->status) {
                'completed' => "Pembayaran pesanan #{$order->code} selesai & lunas.",
                'cancelled' => "Pesanan #{$order->code} dibatalkan dan stok dikembalikan.",
                default => "Status pesanan #{$order->code} berhasil diperbarui di Database & Admin.",
            };

            return response()->json([
                'success' => true,
                'message' => $message,
                'order' => $this->formatOrderForKasir($order),
                'payable_amount' => $payableAmount,
                'cash_received' => $recordedCash,
                'change_amount' => $changeAmount,
                'receipt_url' => route('kasir.orders.receipt', $order->id),
                'stocks' => $updatedStocks,
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
        $order->load(['table', 'items', 'zendoDelivery']);

        return view('admin.orders.receipt', compact('order'));
    }

    /**
     * Count all orders currently awaiting payment in the Kasir workspace.
     */
    protected function countUnpaidOrders(): int
    {
        return Order::whereNotIn('status', ['completed', 'cancelled'])
            ->where(function ($query): void {
                $query->where('payment_status', '!=', 'paid')
                    ->orWhereNull('payment_status');
            })
            ->count();
    }

    /**
     * Format an Order model consistently for Kasir JSON & Blade payloads, including Zendo metadata.
     *
     * @return array<string, mixed>
     */
    protected function formatOrderForKasir(Order $order): array
    {
        $isZendo = $order->isZendo();
        $serviceTypeKey = $isZendo ? 'zendo' : ($order->order_type === 'dine_in' ? 'dine_in' : 'take_away');
        $effectiveSubtotal = $order->effective_subtotal;
        $effectiveDeliveryFee = $order->effective_delivery_fee;
        $kasirBucket = match (true) {
            $order->status === 'cancelled' => 'cancelled',
            $order->status === 'completed' || (in_array($order->status, ['processing', 'ready'], true) && $order->payment_status === 'paid') => 'completed',
            default => 'unpaid',
        };

        return [
            'id' => $order->id,
            'code' => $order->code,
            'customer_name' => $order->customer_name ?: 'Pelanggan Kasir',
            'customer_phone' => $order->customer_phone,
            'order_type' => $order->order_type,
            'kind' => $order->kind,
            'kind_label' => $order->kind_label,
            'is_zendo' => $isZendo,
            'service_type_key' => $serviceTypeKey,
            'service_type_label' => $order->service_type_label,
            'table_number' => $order->table?->number ?? data_get($order->form_data, 'table_no'),
            'delivery_address' => $order->effective_delivery_address,
            'delivery_fee' => $effectiveDeliveryFee,
            'subtotal' => $effectiveSubtotal,
            'total' => (int) $order->total,
            'payable_amount' => $isZendo ? $effectiveSubtotal : (int) $order->total,
            'cash_received' => $order->cash_received_amount,
            'change_amount' => $order->change_amount,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'kasir_bucket' => $kasirBucket,
            'payment_method' => $order->payment_method ?? 'cash',
            'payment_method_label' => $order->payment_method_label,
            'payment_status' => $order->payment_status ?? 'unpaid',
            'can_edit' => $order->canBeEditedAtKasir(),
            'notes' => $order->notes,
            'created_at' => $order->created_at?->format('d M Y, H:i'),
            'created_at_short' => $order->created_at?->format('d M H:i'),
            'created_at_time' => $order->created_at?->format('H:i'),
            'receipt_url' => route('kasir.orders.receipt', $order->id),
            'status_url' => route('kasir.orders.status', $order->id),
            'update_items_url' => route('kasir.orders.updateItems', $order->id),
            'items' => $order->items->map(fn (OrderItem $i): array => [
                'id' => $i->id,
                'menu_id' => $i->menu_id,
                'menu_name' => $i->menu_name,
                'qty' => (int) $i->qty,
                'unit_price' => (int) $i->unit_price,
                'options' => $i->options ?? [],
                'line_total' => (int) $i->line_total,
            ])->values()->all(),
        ];
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
            $dbMenus = Menu::with(['category', 'options'])->orderBy('sort_order')->get();

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
                        'is_best_seller' => (bool) $m->is_best_seller,
                        'options' => $m->options->map(fn ($opt): array => [
                            'id' => $opt->id,
                            'name' => (string) $opt->name,
                            'price' => (int) $opt->price,
                            'type' => (string) ($opt->type ?: 'variant'),
                        ])->values()->all(),
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
                    'is_best_seller' => (bool) ($m['is_best_seller'] ?? false),
                    'options' => [],
                ];
            });
        }

        return $items;
    }

    /**
     * Normalize optional item variants and per-item notes into OrderItem options array format.
     *
     * @param  array<int, mixed>  $rawOptions
     * @return array<int, string|array{name: string, price: int}>|null
     */
    protected function normalizeOrderItemOptions(array $rawOptions, string $itemNote = ''): ?array
    {
        $normalized = [];
        foreach ($rawOptions as $opt) {
            if (is_array($opt) && ! empty($opt['name'])) {
                $name = mb_substr(trim((string) $opt['name']), 0, 100);
                if ($name !== '') {
                    $normalized[] = [
                        'name' => $name,
                        'price' => max(0, (int) ($opt['price'] ?? 0)),
                    ];
                }
            } elseif (is_string($opt)) {
                $name = mb_substr(trim($opt), 0, 100);
                if ($name !== '') {
                    $normalized[] = $name;
                }
            }
        }

        $cleanNote = mb_substr(trim($itemNote), 0, 200);
        if ($cleanNote !== '') {
            $normalized[] = 'Catatan: '.$cleanNote;
        }

        return count($normalized) > 0 ? $normalized : null;
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
