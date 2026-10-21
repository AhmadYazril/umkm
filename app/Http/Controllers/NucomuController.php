<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NucomuController extends Controller
{
    /**
     * Display the main landing page with all 5 sections.
     */
    public function index()
    {
        $brand = config('nucomu.brand');
        $contact = config('nucomu.contact');
        $facilities = config('nucomu.facilities');
        $bestMenu = config('nucomu.best_menu');
        $allMenu = config('nucomu.all_menu');
        $gallery = config('nucomu.gallery');
        $services = config('nucomu.services');
        $integrations = config('nucomu.integrations');
        $menuCategories = config('nucomu.menu_categories');
        $recommendations = config('nucomu.recommendations');
        $reviews = config('nucomu.reviews');

        // Check if database settings override contact info
        try {
            $dbSettings = Setting::pluck('value', 'key');
            if (! empty($dbSettings['cafe_name'])) {
                $brand['name'] = $dbSettings['cafe_name'];
            }
            if (! empty($dbSettings['cafe_address'])) {
                $contact['address'] = $dbSettings['cafe_address'];
            }
            if (! empty($dbSettings['cafe_whatsapp'])) {
                $contact['whatsapp_number'] = $dbSettings['cafe_whatsapp'];
                $contact['whatsapp_display'] = $dbSettings['cafe_whatsapp'];
            }
        } catch (\Throwable $e) {
            // Fallback gracefully to config
        }

        // Fetch stories from DB or fallback
        $stories = collect();
        try {
            $stories = GalleryItem::orderBy('sort_order')->get()->map(function (GalleryItem $item) {
                $imageUrl = null;
                if ($item->image && file_exists(public_path('storage/'.$item->image))) {
                    $imageUrl = asset('storage/'.$item->image);
                } elseif ($item->image && (str_starts_with($item->image, 'http://') || str_starts_with($item->image, 'https://'))) {
                    $imageUrl = $item->image;
                }

                return [
                    'image' => $imageUrl ?? 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => $item->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => $item->author ?? 'Barista Corner',
                    'time_label' => $item->time_label ?? 'Baru saja',
                    'category_badge' => $item->category_badge ?? ('#'.($item->category ?? 'Nucomu')),
                    'caption' => $item->caption ?? 'Nucomu Cafe',
                    'description' => $item->description ?? '',
                ];
            });
        } catch (\Throwable $e) {
        }

        if ($stories->isEmpty()) {
            $stories = collect([
                [
                    'image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Barista Corner',
                    'time_label' => '2 Jam Lalu',
                    'category_badge' => '#CraftCoffee',
                    'caption' => 'Manual Pour-over Beans House Blend',
                    'description' => 'Proses seduh perlahan untuk mengekstrak aroma fruity & floral yang lembut dan khas.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Nucomu Nook',
                    'time_label' => '4 Jam Lalu',
                    'category_badge' => '#CozyVibes',
                    'caption' => 'Sudut Favorit Work From Cafe',
                    'description' => 'Pencahayaan hangat, musik lo-fi tenang, & colokan listrik cepat di tiap meja.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Bakery Kitchen',
                    'time_label' => '6 Jam Lalu',
                    'category_badge' => '#FreshBakery',
                    'caption' => 'Butter Croissant Dipanggang Pagi Ini',
                    'description' => 'Tekstur luar yang renyah flaky bersanding sempurna dengan hangatnya Cappuccino.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Night Vibe',
                    'time_label' => 'Kemarin',
                    'category_badge' => '#AcousticNight',
                    'caption' => 'Suasana Hangat Malam Hari',
                    'description' => 'Malam makin syahdu dengan racikan Signature Mocktail dan obrolan hangat.',
                ],
            ]);
        }

        // Fetch dynamic reviews from DB or fallback
        $dynamicReviews = collect();
        try {
            $dynamicReviews = Testimonial::where('is_active', true)->latest()->get()->map(function (Testimonial $t) {
                return [
                    'name' => $t->name,
                    'handle' => $t->handle ?? '@pelanggan',
                    'avatar_url' => $t->avatar_url ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                    'rating' => $t->rating,
                    'content' => $t->content,
                    'order_tag' => $t->order_tag ?? 'Menu Spesial',
                ];
            });
        } catch (\Throwable $e) {
        }

        if ($dynamicReviews->isEmpty()) {
            $dynamicReviews = collect([
                [
                    'name' => 'Siti Rahmawati',
                    'handle' => '@siti_rahma',
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'content' => 'Tempatnya nyaman banget buat nugas, pencahayaannya hangat dan gak bising. Iced Palm Sugar Latte-nya rasanya creamy dan pas manisnya!',
                    'order_tag' => 'Iced Palm Sugar Latte',
                ],
                [
                    'name' => 'Budi Prasetyo',
                    'handle' => '@budi_coffee',
                    'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'content' => 'Manual brew v60 beans Aceh Gayo-nya juara! Notes fruity-nya dapet banget. Baristanya ramah dan menguasai teknik seduh.',
                    'order_tag' => 'V60 Manual Brew',
                ],
                [
                    'name' => 'Nadia Putri',
                    'handle' => '@nadia_p',
                    'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'content' => 'Suka banget sama playlist musik lo-fi di sini. Matcha Latte & Butter Croissant-nya jodoh terbaik buat bersantai sore.',
                    'order_tag' => 'Kyoto Ice Matcha',
                ],
            ]);
        }

        return view('home.index', compact(
            'brand',
            'contact',
            'facilities',
            'bestMenu',
            'allMenu',
            'gallery',
            'services',
            'integrations',
            'menuCategories',
            'recommendations',
            'reviews',
            'stories',
            'dynamicReviews'
        ));
    }

    /**
     * Display the dedicated full All Menu catalog page.
     */
    public function allMenu(Request $request)
    {
        $brand = config('nucomu.brand');
        $contact = config('nucomu.contact');

        $configMenu = collect(config('nucomu.all_menu', []));

        // Attempt to load from database and merge
        try {
            $dbMenus = Menu::available()->with('category')->get()->map(function ($m) {
                $catSlug = $m->category ? strtolower($m->category->slug ?? $m->category->name) : 'coffee';
                if (str_contains($catSlug, 'non') || str_contains($catSlug, 'tea') || str_contains($catSlug, 'beverage')) {
                    $normCat = 'non-coffee';
                } elseif (str_contains($catSlug, 'dessert') || str_contains($catSlug, 'pastry') || str_contains($catSlug, 'cake')) {
                    $normCat = 'dessert';
                } elseif (str_contains($catSlug, 'snack') || str_contains($catSlug, 'meal') || str_contains($catSlug, 'food')) {
                    $normCat = 'snacks';
                } else {
                    $normCat = 'coffee';
                }

                $img = null;
                if ($m->image && file_exists(public_path('storage/'.$m->image))) {
                    $img = asset('storage/'.$m->image);
                } elseif ($m->image && (str_starts_with($m->image, 'http://') || str_starts_with($m->image, 'https://'))) {
                    $img = $m->image;
                }

                return [
                    'id' => $m->slug ?: 'menu-'.$m->id,
                    'name' => $m->name,
                    'category' => $normCat,
                    'category_label' => $m->category->name ?? ucfirst($normCat),
                    'price' => (int) $m->price,
                    'price_formatted' => 'Rp '.number_format($m->price, 0, ',', '.'),
                    'short_info' => $m->description ?? 'Racikan pilihan Nucomu Cafe.',
                    'image' => $img ?: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&q=80&w=800',
                ];
            });

            if ($dbMenus->isNotEmpty()) {
                $allMenu = $dbMenus->concat($configMenu)->unique('name')->values();
            } else {
                $allMenu = $configMenu;
            }
        } catch (\Throwable $e) {
            $allMenu = $configMenu;
        }

        $selectedCategory = $request->query('category', 'all');

        if ($selectedCategory !== 'all') {
            $filteredMenu = $allMenu->filter(function ($item) use ($selectedCategory) {
                return $item['category'] === $selectedCategory;
            })->values();
        } else {
            $filteredMenu = $allMenu;
        }

        return view('menu.all', compact('brand', 'contact', 'filteredMenu', 'selectedCategory'));
    }

    /**
     * Nucomu Story & Review page — serves gallery + testimonials from DB.
     */
    public function nucomuPage()
    {
        // Gallery stories from DB
        $stories = collect();
        try {
            $stories = GalleryItem::orderBy('sort_order')->get()->map(function (GalleryItem $item) {
                $imageUrl = null;
                if ($item->image && file_exists(public_path('storage/'.$item->image))) {
                    $imageUrl = asset('storage/'.$item->image);
                } elseif ($item->image && (str_starts_with($item->image, 'http://') || str_starts_with($item->image, 'https://'))) {
                    $imageUrl = $item->image;
                }

                return [
                    'image' => $imageUrl ?? 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => $item->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => $item->author ?? 'Barista Corner',
                    'time_label' => $item->time_label ?? 'Baru saja',
                    'category_badge' => $item->category_badge ?? ('#'.($item->category ?? 'Nucomu')),
                    'caption' => $item->caption ?? 'Nucomu Cafe',
                    'description' => $item->description ?? '',
                ];
            });
        } catch (\Throwable $e) {
            // Keep empty
        }

        // Fallback stories if DB has none
        if ($stories->isEmpty()) {
            $stories = collect([
                [
                    'image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Barista Corner',
                    'time_label' => '2 Jam Lalu',
                    'category_badge' => '#CraftCoffee',
                    'caption' => 'Manual Pour-over Beans House Blend',
                    'description' => 'Proses seduh perlahan untuk mengekstrak aroma fruity & floral yang lembut dan khas.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Nucomu Nook',
                    'time_label' => '4 Jam Lalu',
                    'category_badge' => '#CozyVibes',
                    'caption' => 'Sudut Favorit Work From Cafe',
                    'description' => 'Pencahayaan hangat, musik lo-fi tenang, & colokan listrik cepat di tiap meja.',
                ],
            ]);
        }

        // Reviews from DB
        $reviews = collect();
        try {
            $reviews = Testimonial::where('is_active', true)->latest()->get()->map(function (Testimonial $t) {
                return [
                    'name' => $t->name,
                    'handle' => $t->handle ?? '@pelanggan',
                    'avatar_url' => $t->avatar_url ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                    'rating' => $t->rating,
                    'content' => $t->content,
                    'order_tag' => $t->order_tag ?? 'Menu Spesial',
                ];
            });
        } catch (\Throwable $e) {
            // Keep empty
        }

        // Fallback reviews if DB has none
        if ($reviews->isEmpty()) {
            $reviews = collect([
                [
                    'name' => 'Siti Rahmawati',
                    'handle' => '@siti_rahma',
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'content' => 'Tempatnya nyaman banget buat nugas, pencahayaannya hangat dan gak bising. Iced Palm Sugar Latte-nya rasanya creamy dan pas manisnya!',
                    'order_tag' => 'Iced Palm Sugar Latte',
                ],
            ]);
        }

        return view('nucomu.index', compact('stories', 'reviews'));
    }

    /**
     * Nucomu Order page — pelanggan pilih menu dan buat slip pesanan.
     */
    public function orderPage(Request $request)
    {
        $categories = collect();
        $menus = collect();

        try {
            $categories = Category::whereHas('menus', fn ($q) => $q->where('is_available', true))->orderBy('name')->get();
            $menus = Menu::available()->with('category')->orderBy('sort_order')->get();
        } catch (\Throwable $e) {
            // Keep empty
        }

        return view('nucomu.order', compact('categories', 'menus'));
    }

    /**
     * Store nucomu order (AJAX — returns JSON with slip URL).
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer',
            'items.*.menu_name' => 'required|string',
            'items.*.unit_price' => 'required|integer|min:0',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.line_total' => 'required|integer|min:0',
            'total' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                $date = Carbon::now()->format('Ymd');
                $count = Order::whereDate('created_at', today())->count() + 1;
                $code = 'NCM-'.$date.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);

                $order = Order::create([
                    'code' => $code,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => '-',
                    'order_type' => 'dine_in',
                    'notes' => $request->notes,
                    'subtotal' => $request->total,
                    'total' => $request->total,
                    'payment_method' => 'cash',
                    'payment_status' => 'unpaid',
                    'status' => 'pending',
                ]);

                foreach ($request->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $item['menu_id'],
                        'menu_name' => $item['menu_name'],
                        'unit_price' => $item['unit_price'],
                        'qty' => $item['qty'],
                        'options' => null,
                        'line_total' => $item['line_total'],
                    ]);
                }

                return $order;
            });

            return response()->json([
                'success' => true,
                'order_code' => $order->code,
                'slip_url' => route('nucomu.order.slip', $order->code),
            ]);
        } catch (\Exception $e) {
            Log::error('Nucomu order store error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Show the order slip page with QR code.
     */
    public function orderSlip(Request $request, string $code)
    {
        $order = Order::where('code', $code)->with('items')->firstOrFail();

        // Detect appropriate host for scanning via Google Lens / mobile phone
        $host = $request->getHost();
        $port = $request->getPort();
        $scheme = $request->getScheme();

        // If accessed via localhost, resolve the actual LAN IP from system network adapters
        if (in_array($host, ['localhost', '127.0.0.1', '::1'])) {
            $detectedLanIp = $this->detectLocalNetworkIp();
            if ($detectedLanIp) {
                $host = $detectedLanIp;
            }
        }

        $portSuffix = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $adminOrderUrl = "{$scheme}://{$host}{$portSuffix}/qr/pesanan/{$order->code}";

        return view('nucomu.order-slip', compact('order', 'adminOrderUrl', 'host', 'portSuffix', 'scheme'));
    }

    /**
     * Helper to detect local network IP (WiFi / Ethernet) from system ipconfig.
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

    /**
     * Store a review submitted from the Nucomu page.
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'handle' => 'nullable|string|max:50',
            'order_tag' => 'nullable|string|max:100',
        ]);

        try {
            Testimonial::create([
                'name' => $request->name,
                'handle' => $request->handle ? (str_starts_with($request->handle, '@') ? $request->handle : '@'.$request->handle) : null,
                'order_tag' => $request->order_tag,
                'content' => $request->content,
                'rating' => $request->rating,
                'is_sample' => false,
                'is_active' => true,
            ]);
        } catch (\Throwable $e) {
            Log::error('Nucomu review store error: '.$e->getMessage());
        }

        return back()->with('review_success', 'Ulasan Anda berhasil dikirim! Terima kasih.');
    }

    /**
     * Validate reservation, save to database, and generate formatted WhatsApp link.
     */
    public function submitReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^([0-9\\s\\-\\+\\(\\)]*)$/', 'min:9', 'max:20'],
            'date' => ['required', 'date'],
            'time' => ['required', 'string'],
            'guestCount' => ['required', 'string'],
            'serviceType' => ['required', 'string'],
            'area' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'fullName.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid.',
            'date.required' => 'Tanggal acara wajib dipilih.',
            'time.required' => 'Jam acara wajib ditentukan.',
            'guestCount.required' => 'Jumlah tamu wajib ditentukan.',
            'serviceType.required' => 'Jenis acara atau layanan wajib dipilih.',
            'area.required' => 'Pilihan area (indoor/outdoor) wajib ditentukan.',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi formulir belum terpenuhi.',
                    'errors' => $validator->errors()->all(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // 1. Save reservation to database
        try {
            $guestInt = (int) preg_replace('/[^0-9]/', '', $validated['guestCount']);
            Reservation::create([
                'name' => $validated['fullName'],
                'phone' => $validated['phone'],
                'date' => $validated['date'],
                'time' => $validated['time'],
                'guests' => $guestInt > 0 ? $guestInt : 2,
                'notes' => trim("Layanan: {$validated['serviceType']} | Area: {$validated['area']}".($validated['notes'] ? " | Catatan: {$validated['notes']}" : '')),
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menyimpan reservasi ke DB: '.$e->getMessage());
        }

        // 2. Format Indonesian date
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $carbonDate = Carbon::parse($validated['date']);
        $formattedDate = $carbonDate->format('d').' '.($months[(int) $carbonDate->format('m')] ?? $carbonDate->format('F')).' '.$carbonDate->format('Y');

        $destinationNumber = config('nucomu.contact.whatsapp_number', '6281234567890');

        $formattedMessage = "Halo Nucomu Cafe, saya ingin mengajukan reservasi:\n\n".
            '• Nama: '.$validated['fullName']."\n".
            '• WhatsApp: '.$validated['phone']."\n".
            '• Tanggal: '.$formattedDate."\n".
            '• Waktu: '.$validated['time']." WIB\n".
            '• Jumlah Tamu: '.$validated['guestCount']."\n".
            '• Layanan / Acara: '.$validated['serviceType']."\n".
            '• Area: '.$validated['area']."\n".
            '• Catatan Khusus: '.($validated['notes'] ? $validated['notes'] : '-')."\n\n".
            'Mohon informasi ketersediaan jadwal dan tindak lanjutnya. Terima kasih.';

        $whatsappUrl = 'https://wa.me/'.preg_replace('/[^0-9]/', '', $destinationNumber).'?text='.rawurlencode($formattedMessage);

        if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Validasi dan penyimpanan berhasil. Mengarahkan ke WhatsApp...',
                'whatsapp_url' => $whatsappUrl,
                'data' => $validated,
            ]);
        }

        return redirect()->away($whatsappUrl);
    }
}
