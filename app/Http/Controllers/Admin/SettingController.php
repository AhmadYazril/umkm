<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NucomuController;
use App\Models\Event;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\OperatingHour;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\ZendoDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = collect();
        $hours = collect();
        $facilities = collect();

        try {
            $settings = Setting::pluck('value', 'key');
            $hours = OperatingHour::orderBy('day_of_week')->get();
            $facilities = Facility::orderBy('sort_order')->get();
        } catch (\Throwable $e) {
            // Keep empty collections if DB is unavailable
        }

        $operatingHours = $hours;
        $nucomu = app(NucomuController::class);
        $couriers = $nucomu->getCouriersWithLiveTracking();

        return view('admin.settings.index', compact('settings', 'hours', 'operatingHours', 'facilities', 'couriers'));
    }

    /**
     * Dedicated Zendo Courier Management & Live Order Monitoring page (/admin/zendo).
     */
    public function zendoIndex(Request $request)
    {
        $settings = collect();
        try {
            $settings = Setting::pluck('value', 'key');
        } catch (\Throwable $e) {
            // Fallback to empty collection
        }

        $nucomu = app(NucomuController::class);
        $txSettings = $nucomu->getTransactionSettings();
        $couriers = $nucomu->getCouriersWithLiveTracking();
        $nextDispatchPreview = $nucomu->selectAutoDispatchedCourier();

        // Filters
        $filterPhone = trim((string) $request->query('courier_phone', ''));
        $filterStatus = trim((string) $request->query('status', 'all'));
        $searchQuery = trim((string) $request->query('q', ''));

        // Query active and recent Zendo deliveries
        $deliveriesQuery = ZendoDelivery::with('order.items')->latest();

        if ($filterPhone !== '') {
            $deliveriesQuery->where('courier_phone', $filterPhone);
        }
        if ($filterStatus !== 'all' && $filterStatus !== '') {
            $deliveriesQuery->where('status', $filterStatus);
        }
        if ($searchQuery !== '') {
            $deliveriesQuery->where(function ($q) use ($searchQuery) {
                $q->where('order_code', 'like', "%{$searchQuery}%")
                    ->orWhere('customer_name', 'like', "%{$searchQuery}%")
                    ->orWhere('delivery_address', 'like', "%{$searchQuery}%")
                    ->orWhere('courier_name', 'like', "%{$searchQuery}%")
                    ->orWhere('courier_phone', 'like', "%{$searchQuery}%");
            });
        }

        $deliveries = $deliveriesQuery->paginate(20)->withQueryString();

        return view('admin.zendo.index', compact(
            'settings',
            'txSettings',
            'couriers',
            'nextDispatchPreview',
            'deliveries',
            'filterPhone',
            'filterStatus',
            'searchQuery'
        ));
    }

    /**
     * Save multi-courier Zendo count, individual names, and individual WhatsApp phone numbers.
     */
    public function updateZendoCouriers(Request $request)
    {
        $request->validate([
            'zendo_courier_count' => 'required|integer|min:1|max:50',
            'zendo_couriers' => 'required|array|min:1',
            'zendo_couriers.*.name' => 'nullable|string|max:100',
            'zendo_couriers.*.phone' => 'required|string|max:30',
            'zendo_couriers.*.status' => 'nullable|in:idle,delivering,almost_done',
        ], [
            'zendo_couriers.*.phone.required' => 'Nomor WhatsApp setiap kurir Zendo wajib diisi.',
        ]);

        $submittedCouriers = array_values((array) $request->input('zendo_couriers', []));
        $courierCount = max(1, min(50, (int) $request->input('zendo_courier_count', count($submittedCouriers) ?: 1)));

        $normalizedCouriers = [];
        for ($i = 0; $i < $courierCount; $i++) {
            $idx = $i + 1;
            $raw = $submittedCouriers[$i] ?? [];
            $name = trim((string) ($raw['name'] ?? '')) ?: "Kurir Zendo {$idx}";
            $phone = trim((string) ($raw['phone'] ?? ''));
            if ($phone === '') {
                $phone = (string) Setting::get("zendo_courier_{$idx}_phone", '');
            }

            $existingStatus = (string) Setting::get("zendo_courier_{$idx}_status", 'idle');
            $status = in_array($raw['status'] ?? '', ['idle', 'delivering', 'almost_done'], true)
                ? $raw['status']
                : (in_array($existingStatus, ['idle', 'delivering', 'almost_done'], true) ? $existingStatus : 'idle');

            $cleanPhone = $phone !== '' ? $this->normalizeWhatsappNumber($phone) : '';

            Setting::set("zendo_courier_{$idx}_name", $name);
            Setting::set("zendo_courier_{$idx}_phone", $phone);
            Setting::set("zendo_courier_{$idx}_status", $status);

            $normalizedCouriers[] = [
                'index' => $idx,
                'name' => $name,
                'phone' => $phone,
                'clean_phone' => $cleanPhone,
                'status' => $status,
            ];
        }

        Setting::set('zendo_courier_count', (string) $courierCount);
        Setting::set('zendo_couriers', json_encode($normalizedCouriers, JSON_UNESCAPED_UNICODE));

        if (! empty($normalizedCouriers[0]['phone'])) {
            Setting::set('zendo_whatsapp', $normalizedCouriers[0]['phone']);
        }

        return back()->with('success', "Berhasil menyimpan {$courierCount} nomor WhatsApp Kurir Zendo secara terpisah.");
    }

    /**
     * Quick 1-click update of a Zendo courier's live dispatch status (idle / delivering / almost_done).
     */
    public function updateZendoCourierStatus(Request $request, int $index)
    {
        $request->validate([
            'status' => 'required|in:idle,delivering,almost_done',
        ]);

        $idx = max(1, min(50, $index));
        $newStatus = (string) $request->input('status');
        Setting::set("zendo_courier_{$idx}_status", $newStatus);

        $courierPhone = $this->normalizeWhatsappNumber((string) Setting::get("zendo_courier_{$idx}_phone", ''));
        $courierName = (string) Setting::get("zendo_courier_{$idx}_name", "Kurir Zendo {$idx}");

        // Sync active orders belonging to this courier so order tracking matches the courier status
        try {
            $activeOrders = Order::where('notes', 'like', '[KURIR ZENDO]%')
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->get();

            $nucomu = app(NucomuController::class);
            foreach ($activeOrders as $order) {
                $meta = $nucomu->parseZendoOrderNotes($order->notes);
                $matchesCourier = ($courierPhone !== '' && $meta['courier_phone'] === $courierPhone)
                    || ((int) $meta['courier_index'] === $idx);

                if ($matchesCourier) {
                    if ($newStatus === 'idle') {
                        $updatedNotes = $this->replaceZendoStageInNotes((string) $order->notes, 'completed');
                        $order->update([
                            'notes' => $updatedNotes,
                            'status' => 'completed',
                        ]);
                    } elseif ($newStatus === 'almost_done') {
                        $updatedNotes = $this->replaceZendoStageInNotes((string) $order->notes, 'almost_done');
                        $order->update([
                            'notes' => $updatedNotes,
                            'status' => 'processing',
                        ]);
                    } elseif ($newStatus === 'delivering') {
                        $updatedNotes = $this->replaceZendoStageInNotes((string) $order->notes, 'delivering');
                        $order->update([
                            'notes' => $updatedNotes,
                            'status' => 'processing',
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Continue gracefully
        }

        $labels = [
            'idle' => 'Standby / Belum Dapat Pesanan (Siap Terima Order Baru)',
            'delivering' => 'Sedang Mengantar Pesanan',
            'almost_done' => 'Hampir Selesai Pengiriman (Prioritas Order Berikutnya)',
        ];

        return back()->with('success', "Status {$courierName} diperbarui menjadi: ".($labels[$newStatus] ?? $newStatus));
    }

    /**
     * Update a specific Zendo order's delivery stage (delivering -> almost_done -> completed)
     * and synchronize the assigned courier's availability status.
     */
    public function updateZendoOrderStage(Request $request, Order $order)
    {
        $request->validate([
            'stage' => 'required|in:delivering,almost_done,completed',
        ]);

        $stage = (string) $request->input('stage');
        $nucomu = app(NucomuController::class);
        $meta = $nucomu->parseZendoOrderNotes($order->notes);

        $updatedNotes = $this->replaceZendoStageInNotes((string) $order->notes, $stage);
        $newOrderStatus = $stage === 'completed' ? 'completed' : 'processing';

        $order->update([
            'notes' => $updatedNotes,
            'status' => $newOrderStatus,
        ]);

        // Synchronize courier status based on remaining active orders
        $couriers = $nucomu->getTransactionSettings()['zendo_couriers'];
        $matchedCourier = collect($couriers)->first(function (array $c) use ($meta) {
            if (! empty($meta['courier_phone']) && ! empty($c['clean_phone']) && $meta['courier_phone'] === $c['clean_phone']) {
                return true;
            }
            if (! empty($meta['courier_index']) && (int) $meta['courier_index'] === (int) $c['index']) {
                return true;
            }

            return false;
        });

        if ($matchedCourier) {
            $idx = (int) $matchedCourier['index'];
            if ($stage === 'almost_done') {
                Setting::set("zendo_courier_{$idx}_status", 'almost_done');
            } elseif ($stage === 'delivering') {
                Setting::set("zendo_courier_{$idx}_status", 'delivering');
            } elseif ($stage === 'completed') {
                $refreshed = collect($nucomu->getCouriersWithLiveTracking())->firstWhere('index', $idx);
                if (! $refreshed || $refreshed['active_orders_count'] === 0) {
                    Setting::set("zendo_courier_{$idx}_status", 'idle');
                }
            }
        }

        $stageLabels = [
            'delivering' => 'Sedang Mengantar',
            'almost_done' => 'Hampir Selesai Pengiriman',
            'completed' => 'Selesai Diantar (Kurir Kembali Standby)',
        ];

        return back()->with('success', "Pesanan #{$order->code} diperbarui ke tahap: ".($stageLabels[$stage] ?? $stage));
    }

    /**
     * Save Zendo distance tariff, cafe coordinates, and optional WhatsApp Gateway credentials.
     */
    public function updateZendoTariff(Request $request)
    {
        $request->validate([
            'zendo_send_mode' => 'nullable|in:auto_direct,wa_redirect',
            'zendo_wa_api_url' => 'nullable|string|max:255',
            'zendo_wa_api_token' => 'nullable|string|max:255',
            'cafe_lat' => 'nullable|numeric',
            'cafe_lng' => 'nullable|numeric',
            'zendo_tier_0_3' => 'nullable|integer|min:0',
            'zendo_tier_3_4' => 'nullable|integer|min:0',
            'zendo_tier_4_5' => 'nullable|integer|min:0',
            'zendo_tier_5_6' => 'nullable|integer|min:0',
            'zendo_tier_6_7' => 'nullable|integer|min:0',
            'zendo_extra_per_km' => 'nullable|integer|min:0',
        ]);

        $keys = [
            'zendo_send_mode',
            'zendo_wa_api_url',
            'zendo_wa_api_token',
            'cafe_lat',
            'cafe_lng',
            'zendo_tier_0_3',
            'zendo_tier_3_4',
            'zendo_tier_4_5',
            'zendo_tier_5_6',
            'zendo_tier_6_7',
            'zendo_extra_per_km',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'Tarif jarak Zendo & pengaturan pengiriman berhasil disimpan.');
    }

    /**
     * Helper to update or insert Tahap_Zendo in an Order's notes string.
     */
    protected function replaceZendoStageInNotes(string $notes, string $stage): string
    {
        if (str_contains($notes, 'Tahap_Zendo:')) {
            return (string) preg_replace('/Tahap_Zendo:\s*[a-zA-Z0-9_]+/', "Tahap_Zendo: {$stage}", $notes);
        }

        $parts = explode(' | ', $notes);
        array_splice($parts, 1, 0, ["Tahap_Zendo: {$stage}"]);

        return implode(' | ', $parts);
    }

    public function update(Request $request)
    {
        $request->validate([
            'transaction_mode' => 'nullable|in:kasir,zendo,both',
            'zendo_whatsapp' => 'nullable|string|max:30',
            'zendo_courier_count' => 'nullable|integer|min:1|max:50',
            'zendo_couriers' => 'nullable|array',
            'zendo_couriers.*.name' => 'nullable|string|max:100',
            'zendo_couriers.*.phone' => 'nullable|string|max:30',
            'zendo_send_mode' => 'nullable|in:auto_direct,wa_redirect',
            'zendo_wa_api_url' => 'nullable|string|max:255',
            'zendo_wa_api_token' => 'nullable|string|max:255',
            'cafe_lat' => 'nullable|numeric',
            'cafe_lng' => 'nullable|numeric',
            'zendo_tier_0_3' => 'nullable|integer|min:0',
            'zendo_tier_3_4' => 'nullable|integer|min:0',
            'zendo_tier_4_5' => 'nullable|integer|min:0',
            'zendo_tier_5_6' => 'nullable|integer|min:0',
            'zendo_tier_6_7' => 'nullable|integer|min:0',
            'zendo_extra_per_km' => 'nullable|integer|min:0',
        ]);

        $keys = [
            'store_name', 'phone', 'address', 'instagram', 'tagline',
            'cafe_name', 'cafe_tagline', 'cafe_subtitle', 'cafe_story', 'cafe_history',
            'cafe_concept', 'cafe_address', 'cafe_maps_url', 'cafe_whatsapp',
            'cafe_email', 'cafe_instagram', 'cafe_tiktok',
            'transaction_mode', 'zendo_whatsapp', 'zendo_send_mode',
            'zendo_wa_api_url', 'zendo_wa_api_token',
            'cafe_lat', 'cafe_lng',
            'zendo_tier_0_3', 'zendo_tier_3_4', 'zendo_tier_4_5',
            'zendo_tier_5_6', 'zendo_tier_6_7', 'zendo_extra_per_km',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Save multi-courier Zendo configuration (count + list of couriers & WhatsApp numbers)
        if ($request->has('zendo_couriers') || $request->has('zendo_courier_count')) {
            $submittedCouriers = array_values((array) $request->input('zendo_couriers', []));
            $requestedCount = (int) $request->input('zendo_courier_count', count($submittedCouriers) ?: 1);
            $courierCount = max(1, min(50, $requestedCount));

            $fallbackPhone = trim((string) $request->input('zendo_whatsapp', config('nucomu.contact.zendo_whatsapp', '6281234567890')));
            $normalizedCouriers = [];

            for ($i = 0; $i < $courierCount; $i++) {
                $idx = $i + 1;
                $raw = $submittedCouriers[$i] ?? [];
                $name = trim((string) ($raw['name'] ?? '')) ?: "Kurir Zendo {$idx}";
                $phone = trim((string) ($raw['phone'] ?? ''));
                if ($phone === '') {
                    $phone = (string) Setting::get("zendo_courier_{$idx}_phone", $idx === 1 ? $fallbackPhone : '');
                }

                $existingStatus = (string) Setting::get("zendo_courier_{$idx}_status", 'idle');
                $status = in_array($raw['status'] ?? '', ['idle', 'delivering', 'almost_done'], true)
                    ? $raw['status']
                    : (in_array($existingStatus, ['idle', 'delivering', 'almost_done'], true) ? $existingStatus : 'idle');

                $cleanPhone = $phone !== '' ? $this->normalizeWhatsappNumber($phone) : '';

                Setting::set("zendo_courier_{$idx}_name", $name);
                Setting::set("zendo_courier_{$idx}_phone", $phone);
                Setting::set("zendo_courier_{$idx}_status", $status);

                $normalizedCouriers[] = [
                    'index' => $idx,
                    'name' => $name,
                    'phone' => $phone,
                    'clean_phone' => $cleanPhone,
                    'status' => $status,
                ];
            }

            Setting::set('zendo_courier_count', (string) $courierCount);
            Setting::set('zendo_couriers', json_encode($normalizedCouriers, JSON_UNESCAPED_UNICODE));

            if (! empty($normalizedCouriers[0]['phone'])) {
                Setting::set('zendo_whatsapp', $normalizedCouriers[0]['phone']);
            }
        }

        // Keep legacy and current setting keys synchronized
        if ($request->has('store_name') && ! $request->has('cafe_name')) {
            Setting::set('cafe_name', $request->input('store_name'));
        }
        if ($request->has('phone') && ! $request->has('cafe_whatsapp')) {
            Setting::set('cafe_whatsapp', $request->input('phone'));
        }
        if ($request->has('address') && ! $request->has('cafe_address')) {
            Setting::set('cafe_address', $request->input('address'));
        }
        if ($request->has('instagram') && ! $request->has('cafe_instagram')) {
            Setting::set('cafe_instagram', $request->input('instagram'));
        }
        if ($request->has('tagline') && ! $request->has('cafe_tagline')) {
            Setting::set('cafe_tagline', $request->input('tagline'));
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Test automatic WhatsApp Gateway message delivery to a Zendo courier.
     */
    public function testZendoWhatsapp(Request $request)
    {
        $phone = trim((string) $request->input('phone', ''));
        $apiUrl = trim((string) $request->input('api_url', Setting::get('zendo_wa_api_url', 'https://api.fonnte.com/send')));
        $apiToken = trim((string) $request->input('api_token', Setting::get('zendo_wa_api_token', '')));

        if ($phone === '') {
            $phone = (string) Setting::get('zendo_whatsapp', config('nucomu.contact.zendo_whatsapp', '6281234567890'));
        }
        $cleanPhone = $this->normalizeWhatsappNumber($phone);

        if ($apiToken === '') {
            return response()->json([
                'success' => false,
                'message' => 'Token API WhatsApp Gateway masih kosong. Silakan isi Token API (misal dari fonnte.com) terlebih dahulu agar sistem dapat mengirim pesan WhatsApp secara otomatis di background tanpa klik manual.',
            ], 422);
        }

        try {
            $testMessage = "✅ *[TES SISTEM NUCOMU CAFE]*\nKoneksi Auto-Send WhatsApp Kurir Zendo berhasil terhubung! Pesanan pelanggan akan langsung masuk ke nomor ini secara otomatis.";

            $response = Http::connectTimeout(4)
                ->timeout(8)
                ->withHeaders([
                    'Authorization' => $apiToken,
                ])
                ->asForm()
                ->post($apiUrl ?: 'https://api.fonnte.com/send', [
                    'target' => $cleanPhone,
                    'phone' => $cleanPhone,
                    'message' => $testMessage,
                    'countryCode' => '62',
                ]);

            $json = $response->json();
            $isOk = $response->successful() && (($json['status'] ?? true) !== false);

            if ($isOk) {
                return response()->json([
                    'success' => true,
                    'message' => "Pesan tes berhasil dikirim otomatis ke nomor WhatsApp Kurir (+{$cleanPhone})!",
                ]);
            }

            $reason = $json['reason'] ?? $json['message'] ?? ('HTTP '.$response->status());

            return response()->json([
                'success' => false,
                'message' => "Gateway menolak pengiriman: {$reason}. Pastikan Token API aktif dan perangkat WhatsApp sudah terhubung.",
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi server WhatsApp Gateway: '.$e->getMessage(),
            ], 500);
        }
    }

    protected function normalizeWhatsappNumber(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }
        if ($digits === '') {
            $digits = '6281234567890';
        }

        return $digits;
    }

    public function updateHours(Request $request)
    {
        $days = $request->input('hours', []);
        foreach ($days as $dayOfWeek => $data) {
            OperatingHour::updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                [
                    'open_time' => $data['is_closed'] ?? false ? null : ($data['open_time'] ?? null),
                    'close_time' => $data['is_closed'] ?? false ? null : ($data['close_time'] ?? null),
                    'is_closed' => isset($data['is_closed']),
                ]
            );
        }

        return back()->with('success', 'Jam operasional berhasil diperbarui.');
    }

    // ── Reservasi ──────────────────────────────────────
    public function reservations(Request $request)
    {
        $query = Reservation::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $reservations = $query->latest()->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateReservation(Request $request, Reservation $reservation)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,cancelled']);
        $reservation->update(['status' => $request->status]);

        return back()->with('success', 'Status reservasi diperbarui.');
    }

    // ── Galeri ─────────────────────────────────────────
    public function gallery()
    {
        $items = GalleryItem::orderBy('sort_order')->get();

        return view('admin.gallery.index', compact('items'));
    }

    public function storeGallery(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
            'caption' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'time_label' => 'nullable|string|max:50',
            'category_badge' => 'nullable|string|max:50',
            'avatar_url' => 'nullable|url|max:500',
            'description' => 'nullable|string|max:500',
        ]);
        $path = $request->file('image')->store('gallery', 'public');
        GalleryItem::create([
            'image' => $path,
            'caption' => $request->caption,
            'category' => $request->category ?? 'ambience',
            'sort_order' => GalleryItem::max('sort_order') + 1,
            'author' => $request->author,
            'time_label' => $request->time_label,
            'category_badge' => $request->category_badge,
            'avatar_url' => $request->avatar_url,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Foto berhasil ditambahkan.');
    }

    public function destroyGallery(GalleryItem $galleryItem)
    {
        Storage::disk('public')->delete($galleryItem->image);
        $galleryItem->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    // ── Testimoni ──────────────────────────────────────
    public function testimonials()
    {
        $testimonials = Testimonial::latest()->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'handle' => 'nullable|string|max:50',
            'order_tag' => 'nullable|string|max:100',
            'avatar_url' => 'nullable|url|max:500',
        ]);
        Testimonial::create([
            'name' => $request->name,
            'handle' => $request->handle ? (str_starts_with($request->handle, '@') ? $request->handle : '@'.$request->handle) : null,
            'order_tag' => $request->order_tag,
            'avatar_url' => $request->avatar_url,
            'content' => $request->content,
            'rating' => $request->rating,
            'is_sample' => false,
            'is_active' => true,
        ]);

        return back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }

    public function deleteSampleTestimonials()
    {
        $count = Testimonial::where('is_sample', true)->count();
        Testimonial::where('is_sample', true)->delete();

        return back()->with('success', "$count testimoni data contoh berhasil dihapus.");
    }

    public function toggleTestimonial(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);

        return back()->with('success', 'Status ulasan diperbarui.');
    }

    // ── Events ─────────────────────────────────────────
    public function events()
    {
        $events = Event::latest()->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate(['title' => 'required', 'description' => 'required', 'date' => 'nullable|date']);
        $data = $request->only(['title', 'description', 'date']);
        $data['is_active'] = true;
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }
        Event::create($data);

        return back()->with('success', 'Event berhasil ditambahkan.');
    }

    public function destroyEvent(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();

        return back()->with('success', 'Event berhasil dihapus.');
    }
}
