<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ZendoDelivery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Live Zendo courier tracking (Grab-like):
 * - Public tracking link for customer & admin (read-only live map).
 * - Courier-only link, gated by verification of the assigned courier's WhatsApp number
 *   and bound to a single verified device, streaming GPS with anti fake-GPS checks (GPS Lock).
 */
class ZendoTrackingController extends Controller
{
    public const DEVICE_COOKIE = 'zendo_courier_device';

    public function __construct(protected NucomuController $nucomu) {}

    /**
     * Unified live tracking page (auto-detects courier vs customer from token or key).
     */
    public function track(Request $request, string $trackingToken): View
    {
        $delivery = ZendoDelivery::with('order.items')
            ->where('tracking_token', $trackingToken)
            ->orWhere('courier_token', $trackingToken)
            ->firstOrFail();

        $txSettings = $this->nucomu->getTransactionSettings();

        // Mode kurir otomatis aktif jika URL memiliki kunci kurir (?k=...) atau dibuka via token kurir
        $isCourier = false;
        if ($request->filled('k') && hash_equals($delivery->courier_token, (string) $request->query('k'))) {
            $this->bindCourierDevice($delivery);
            $isCourier = true;
        } elseif (hash_equals($delivery->courier_token, $trackingToken)) {
            $this->bindCourierDevice($delivery);
            $isCourier = true;
        }

        return view('zendo.track', [
            'delivery' => $delivery,
            'isCourier' => $isCourier,
            'cafeLat' => $txSettings['cafe_lat'],
            'cafeLng' => $txSettings['cafe_lng'],
            'trackingConfig' => config('nucomu.contact.zendo_tracking'),
            'initialPayload' => $this->buildTrackingPayload($delivery, $isCourier),
        ]);
    }

    /**
     * Live tracking JSON (polled by the tracking page).
     */
    public function trackData(string $trackingToken): JsonResponse
    {
        $delivery = ZendoDelivery::where('tracking_token', $trackingToken)
            ->orWhere('courier_token', $trackingToken)
            ->firstOrFail();

        return response()->json($this->buildTrackingPayload($delivery));
    }

    /**
     * Courier-only entry route: automatically binds the courier device without requiring manual phone entry,
     * and redirects directly to live courier workspace.
     */
    public function courier(Request $request, string $courierToken): RedirectResponse
    {
        $delivery = ZendoDelivery::with('order.items')->where('courier_token', $courierToken)->firstOrFail();
        $this->bindCourierDevice($delivery);

        return redirect()->route('zendo.track', [
            'token' => $delivery->tracking_token,
            'k' => $delivery->courier_token,
        ]);
    }

    /**
     * Unlink courier device cookie (allows resetting/logging out of courier mode on this device).
     */
    public function unbindCourier(Request $request): RedirectResponse
    {
        Cookie::queue(Cookie::forget(self::DEVICE_COOKIE));

        return back()->with('info', 'Perangkat berhasil dilepas dari Mode Kurir.');
    }

    /**
     * Send a one-time WhatsApp OTP to the assigned courier number (only if the entered number matches).
     */
    public function sendOtp(Request $request, string $courierToken): RedirectResponse
    {
        $delivery = ZendoDelivery::where('courier_token', $courierToken)->firstOrFail();
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ], [
            'phone.required' => 'Masukkan nomor WhatsApp kurir Anda.',
        ]);

        $enteredPhone = $this->nucomu->normalizeWhatsappNumber($validated['phone']);
        if (! hash_equals($delivery->courier_phone, $enteredPhone)) {
            return redirect()->route('zendo.track', $delivery->tracking_token)
                ->with('info', 'Nomor ini bukan nomor kurir yang ditugaskan. Anda dialihkan ke Mode Pelacakan untuk melihat posisi kurir.');
        }

        $rateKey = 'zendo-otp:'.$delivery->id;
        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $seconds = RateLimiter::availableIn($rateKey);

            return back()->withInput()->withErrors(['phone' => "Terlalu sering meminta kode. Coba lagi dalam {$seconds} detik."]);
        }
        RateLimiter::hit($rateKey, 600);

        $txSettings = $this->nucomu->getTransactionSettings();
        $otpCode = (string) random_int(100000, 999999);
        Cache::put($this->otpCacheKey($delivery), Hash::make($otpCode), now()->addMinutes(5));

        $sent = $this->nucomu->dispatchZendoWhatsappMessage(
            $delivery->courier_phone,
            "🔐 Kode verifikasi Kurir Nucomu Cafe: *{$otpCode}*\nPesanan #{$delivery->order_code}. Berlaku 5 menit. Jangan bagikan kode ini kepada siapa pun.",
            $txSettings
        );

        if (! $sent) {
            return back()->withInput()->withErrors(['phone' => 'Kode OTP gagal dikirim melalui WhatsApp Gateway. Hubungi admin Nucomu Cafe.']);
        }

        return back()->withInput()->with('otp_sent', 'Kode OTP telah dikirim ke WhatsApp '.$this->maskPhone($delivery->courier_phone).'.');
    }

    /**
     * Verify the courier number (+ OTP when WhatsApp Gateway is active) and bind this device to the courier.
     */
    public function verify(Request $request, string $courierToken): RedirectResponse
    {
        $delivery = ZendoDelivery::where('courier_token', $courierToken)->firstOrFail();
        $txSettings = $this->nucomu->getTransactionSettings();
        $gatewayEnabled = $this->isGatewayEnabled($txSettings);

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'otp' => [$gatewayEnabled ? 'required' : 'nullable', 'digits:6'],
        ], [
            'phone.required' => 'Masukkan nomor WhatsApp kurir Anda.',
            'otp.required' => 'Masukkan 6 digit kode OTP yang dikirim ke WhatsApp Anda.',
            'otp.digits' => 'Kode OTP harus 6 digit angka.',
        ]);

        $rateKey = 'zendo-verify:'.$delivery->id.'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($rateKey, 6)) {
            $seconds = RateLimiter::availableIn($rateKey);

            return back()->withInput()->withErrors(['phone' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
        }
        RateLimiter::hit($rateKey, 600);

        $enteredPhone = $this->nucomu->normalizeWhatsappNumber($validated['phone']);
        if (! hash_equals($delivery->courier_phone, $enteredPhone)) {
            return redirect()->route('zendo.track', $delivery->tracking_token)
                ->with('info', 'Nomor ini bukan nomor kurir yang ditugaskan. Anda dialihkan ke Mode Pelacakan untuk melihat posisi kurir di peta.');
        }

        if ($gatewayEnabled) {
            $hashedOtp = Cache::get($this->otpCacheKey($delivery));
            if (! $hashedOtp || ! Hash::check((string) $validated['otp'], $hashedOtp)) {
                return back()->withInput()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
            }
            Cache::forget($this->otpCacheKey($delivery));
        }

        RateLimiter::clear($rateKey);

        // Bind this device to the courier number (one active device per courier)
        $this->bindCourierDevice($delivery);

        return redirect()->route('zendo.track', $delivery->tracking_token)
            ->with('verified', 'Nomor kurir terverifikasi. Perangkat ini sekarang terhubung sebagai '.$delivery->courier_name.'.');
    }

    /**
     * Receive a GPS reading from the verified courier device, validated by anti fake-GPS checks (GPS Lock).
     */
    public function updateLocation(Request $request, string $courierToken): JsonResponse
    {
        $delivery = ZendoDelivery::where('courier_token', $courierToken)
            ->orWhere('tracking_token', $courierToken)
            ->firstOrFail();

        if (! $this->isVerifiedCourierDevice($request, $delivery)) {
            return response()->json(['accepted' => false, 'message' => 'Perangkat belum terverifikasi sebagai nomor kurir.'], 403);
        }

        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['required', 'numeric', 'min:0'],
            'speed' => ['nullable', 'numeric'],
            'heading' => ['nullable', 'numeric'],
            'altitude' => ['nullable', 'numeric'],
            'timestamp' => ['required', 'numeric'],
            'signals' => ['nullable', 'array', 'max:10'],
            'signals.*' => ['string', 'max:40'],
        ]);

        Setting::set("zendo_last_ping_{$delivery->courier_phone}", now()->toDateTimeString());

        if (! $delivery->isActive()) {
            return response()->json([
                'accepted' => false,
                'message' => 'Pengantaran ini sudah selesai.',
                'payload' => $this->buildTrackingPayload($delivery, true),
            ], 409);
        }

        if ($delivery->isGpsLocked()) {
            return response()->json([
                'accepted' => false,
                'locked' => true,
                'message' => 'GPS terkunci karena terdeteksi indikasi Fake GPS. Matikan aplikasi lokasi palsu / Mock Location lalu tunggu hingga kunci terbuka.',
                'payload' => $this->buildTrackingPayload($delivery, true),
            ], 423);
        }

        $evaluation = $this->evaluateGpsReading($delivery, $validated);
        $strikesKey = 'zendo_gps_strikes_'.$delivery->id;

        if ($evaluation['verdict'] === 'weak') {
            return response()->json([
                'accepted' => false,
                'weak' => true,
                'message' => $evaluation['reason'],
                'payload' => $this->buildTrackingPayload($delivery, true),
            ], 202);
        }

        if ($evaluation['verdict'] === 'suspicious') {
            $strikes = (int) Cache::get($strikesKey, 0) + 1;
            $threshold = (int) config('nucomu.contact.zendo_tracking.suspicious_lock_threshold', 3);
            $updates = [
                'gps_suspicious_count' => $delivery->gps_suspicious_count + 1,
                'last_gps_warning' => $evaluation['reason'],
            ];

            if ($strikes >= $threshold) {
                $updates['gps_locked_until'] = now()->addMinutes((int) config('nucomu.contact.zendo_tracking.lock_minutes', 5));
                Cache::forget($strikesKey);
            } else {
                Cache::put($strikesKey, $strikes, now()->addMinutes(10));
            }

            $delivery->update($updates);
            $delivery->refresh();

            return response()->json([
                'accepted' => false,
                'suspicious' => true,
                'locked' => $delivery->isGpsLocked(),
                'message' => $evaluation['reason'],
                'payload' => $this->buildTrackingPayload($delivery, true),
            ], 422);
        }

        Cache::forget($strikesKey);

        $lat = (float) $validated['lat'];
        $lng = (float) $validated['lng'];
        $trail = $delivery->route_trail ?? [];
        $lastPoint = end($trail) ?: null;
        if (! $lastPoint || ZendoDelivery::distanceMeters($lastPoint[0], $lastPoint[1], $lat, $lng) >= 10) {
            $trail[] = [round($lat, 7), round($lng, 7), now()->timestamp];
            $trail = array_slice($trail, -300);
        }

        $updates = [
            'courier_lat' => $lat,
            'courier_lng' => $lng,
            'courier_accuracy' => (float) $validated['accuracy'],
            'courier_heading' => isset($validated['heading']) ? (float) $validated['heading'] : null,
            'courier_speed' => isset($validated['speed']) ? max(0.0, (float) $validated['speed']) : null,
            'location_updated_at' => now(),
            'route_trail' => $trail,
        ];

        // Auto "hampir sampai" when the courier's verified GPS gets close to the destination
        if ($delivery->status === 'picked_up' && $delivery->hasDestinationPin()) {
            $remaining = ZendoDelivery::distanceMeters($lat, $lng, $delivery->destination_lat, $delivery->destination_lng);
            if ($remaining <= (int) config('nucomu.contact.zendo_tracking.almost_done_radius_m', 1000)) {
                $updates['status'] = 'almost_done';
            }
        }

        $delivery->update($updates);
        $delivery->refresh();

        return response()->json([
            'accepted' => true,
            'message' => 'Lokasi terkirim.',
            'payload' => $this->buildTrackingPayload($delivery, true),
        ]);
    }

    /**
     * Courier-driven status updates, locked to the courier's verified live GPS position.
     */
    public function updateStatus(Request $request, string $courierToken): JsonResponse
    {
        $delivery = ZendoDelivery::with('order')
            ->where('courier_token', $courierToken)
            ->orWhere('tracking_token', $courierToken)
            ->firstOrFail();

        if (! $this->isVerifiedCourierDevice($request, $delivery)) {
            return response()->json(['success' => false, 'message' => 'Perangkat belum terverifikasi sebagai nomor kurir.'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:picked_up,completed'],
        ]);

        if ($delivery->isGpsLocked()) {
            return response()->json(['success' => false, 'message' => 'GPS sedang terkunci karena indikasi Fake GPS. Status tidak dapat diubah.'], 423);
        }

        $staleSeconds = (int) config('nucomu.contact.zendo_tracking.stale_location_s', 120);
        if (! $delivery->hasCourierLocation() || ! $delivery->location_updated_at || $delivery->location_updated_at->lt(now()->subSeconds($staleSeconds))) {
            return response()->json(['success' => false, 'message' => 'Lokasi GPS Anda belum terbaca. Aktifkan GPS (akurasi tinggi) dan tunggu beberapa detik.'], 422);
        }

        $txSettings = $this->nucomu->getTransactionSettings();
        $target = $validated['status'];

        if ($target === 'picked_up') {
            if ($delivery->status !== 'assigned') {
                return response()->json(['success' => false, 'message' => 'Pesanan sudah diambil sebelumnya.'], 409);
            }

            $pickupRadius = (int) config('nucomu.contact.zendo_tracking.pickup_radius_m', 500);
            $distanceToCafe = ZendoDelivery::distanceMeters($delivery->courier_lat, $delivery->courier_lng, (float) $txSettings['cafe_lat'], (float) $txSettings['cafe_lng']);
            if ($distanceToCafe > $pickupRadius) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda masih '.$this->formatDistance($distanceToCafe)." dari Nucomu Cafe. Ambil pesanan hanya bisa dilakukan dalam radius {$pickupRadius} m dari kafe.",
                ], 422);
            }

            $delivery->update(['status' => 'picked_up', 'picked_up_at' => now()]);
            $delivery->order?->update(['status' => 'processing']);
        }

        if ($target === 'completed') {
            if (! in_array($delivery->status, ['picked_up', 'almost_done'], true)) {
                return response()->json(['success' => false, 'message' => 'Ambil pesanan terlebih dahulu sebelum menyelesaikan pengantaran.'], 409);
            }

            if ($delivery->hasDestinationPin()) {
                $dropoffRadius = (int) config('nucomu.contact.zendo_tracking.dropoff_radius_m', 200);
                $remaining = (float) $delivery->remainingDistanceMeters();
                if ($remaining > $dropoffRadius) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda masih '.$this->formatDistance($remaining)." dari titik tujuan. Selesaikan pengantaran hanya bisa dalam radius {$dropoffRadius} m dari lokasi pelanggan.",
                    ], 422);
                }
            }

            $delivery->update(['status' => 'completed', 'completed_at' => now()]);
            $delivery->order?->update(['status' => 'completed']);
        }

        $delivery->refresh();

        return response()->json([
            'success' => true,
            'message' => $delivery->status_label,
            'payload' => $this->buildTrackingPayload($delivery, true),
        ]);
    }

    /**
     * Server-side anti fake-GPS evaluation of a single reading.
     *
     * @param  array{lat: mixed, lng: mixed, accuracy: mixed, speed?: mixed, heading?: mixed, altitude?: mixed, timestamp: mixed, signals?: array<int, string>|null}  $reading
     * @return array{verdict: 'ok'|'weak'|'suspicious', reason: string|null}
     */
    public function evaluateGpsReading(ZendoDelivery $delivery, array $reading): array
    {
        $config = config('nucomu.contact.zendo_tracking');
        $lat = (float) $reading['lat'];
        $lng = (float) $reading['lng'];
        $accuracy = (float) $reading['accuracy'];
        $clientTimestampSeconds = ((float) $reading['timestamp']) / 1000.0;
        $signals = array_values(array_unique((array) ($reading['signals'] ?? [])));

        if ($accuracy <= 0) {
            return ['verdict' => 'suspicious', 'reason' => 'Akurasi GPS tidak wajar (0 m) — indikasi lokasi palsu.'];
        }

        if (abs(now()->timestamp - $clientTimestampSeconds) > (int) ($config['max_clock_skew_s'] ?? 120)) {
            return ['verdict' => 'suspicious', 'reason' => 'Waktu pembacaan GPS tidak sinkron dengan server — indikasi lokasi palsu / jam perangkat diubah.'];
        }

        if (isset($reading['speed']) && (float) $reading['speed'] > (float) ($config['max_speed_mps'] ?? 45)) {
            return ['verdict' => 'suspicious', 'reason' => 'Kecepatan yang dilaporkan GPS tidak wajar untuk kendaraan kurir.'];
        }

        $txSettings = $this->nucomu->getTransactionSettings();
        $distanceFromCafeKm = ZendoDelivery::distanceMeters($lat, $lng, (float) $txSettings['cafe_lat'], (float) $txSettings['cafe_lng']) / 1000;
        if ($distanceFromCafeKm > (float) ($config['service_radius_km'] ?? 40)) {
            return ['verdict' => 'suspicious', 'reason' => 'Lokasi berada jauh di luar area layanan Nucomu Cafe — indikasi lokasi palsu.'];
        }

        if ($delivery->hasCourierLocation() && $delivery->location_updated_at) {
            $elapsed = max(1, now()->diffInSeconds($delivery->location_updated_at, true));
            $jump = ZendoDelivery::distanceMeters($delivery->courier_lat, $delivery->courier_lng, $lat, $lng);
            if ($jump > 300 && ($jump / $elapsed) > (float) ($config['max_speed_mps'] ?? 45)) {
                return ['verdict' => 'suspicious', 'reason' => 'Lokasi melompat '.$this->formatDistance($jump)." dalam {$elapsed} detik — indikasi teleport / Fake GPS."];
            }
        }

        if (count($signals) >= 2) {
            return ['verdict' => 'suspicious', 'reason' => 'Pola sinyal GPS perangkat menyerupai aplikasi lokasi palsu (Mock Location).'];
        }

        if ($accuracy > (float) ($config['max_accuracy_m'] ?? 150)) {
            return ['verdict' => 'weak', 'reason' => 'Sinyal GPS lemah (akurasi '.round($accuracy).' m). Pindah ke area terbuka & aktifkan mode akurasi tinggi.'];
        }

        return ['verdict' => 'ok', 'reason' => null];
    }

    /**
     * Build the live tracking JSON payload.
     *
     * @return array<string, mixed>
     */
    public function buildTrackingPayload(ZendoDelivery $delivery, bool $forCourier = false): array
    {
        $txSettings = $this->nucomu->getTransactionSettings();
        $cafeLat = (float) $txSettings['cafe_lat'];
        $cafeLng = (float) $txSettings['cafe_lng'];
        $staleSeconds = (int) config('nucomu.contact.zendo_tracking.stale_location_s', 120);

        $remaining = $delivery->remainingDistanceMeters();
        $etaMinutes = null;
        if ($remaining !== null && $delivery->isActive()) {
            $speed = ($delivery->courier_speed ?? 0) > 2 ? $delivery->courier_speed : 6.9;
            $etaMinutes = max(1, (int) ceil(($remaining / $speed) / 60));
        }

        $secondsAgo = $delivery->location_updated_at ? (int) $delivery->location_updated_at->diffInSeconds(now(), true) : null;

        $payload = [
            'order_code' => $delivery->order_code,
            'status' => $delivery->status,
            'status_label' => $delivery->status_label,
            'is_active' => $delivery->isActive(),
            'courier' => [
                'name' => $delivery->courier_name,
                'phone' => $delivery->courier_phone,
                'verified' => $delivery->courier_verified_at !== null,
                'lat' => $delivery->courier_lat,
                'lng' => $delivery->courier_lng,
                'accuracy' => $delivery->courier_accuracy,
                'heading' => $delivery->courier_heading,
                'speed_kmh' => $delivery->courier_speed !== null ? round($delivery->courier_speed * 3.6, 1) : null,
                'updated_at' => $delivery->location_updated_at?->toIso8601String(),
                'seconds_ago' => $secondsAgo,
                'is_stale' => $secondsAgo === null || $secondsAgo > $staleSeconds,
            ],
            'cafe' => ['lat' => $cafeLat, 'lng' => $cafeLng],
            'destination' => [
                'lat' => $delivery->destination_lat,
                'lng' => $delivery->destination_lng,
                'address' => $delivery->delivery_address,
            ],
            'trail' => array_map(fn (array $point) => [$point[0], $point[1]], $delivery->route_trail ?? []),
            'remaining_m' => $remaining !== null ? (int) round($remaining) : null,
            'remaining_label' => $remaining !== null ? $this->formatDistance($remaining) : null,
            'eta_minutes' => $etaMinutes,
            'gps' => [
                'suspicious_count' => $delivery->gps_suspicious_count,
                'last_warning' => $delivery->last_gps_warning,
                'locked' => $delivery->isGpsLocked(),
                'locked_until' => $delivery->gps_locked_until?->toIso8601String(),
            ],
            'timeline' => [
                'assigned_at' => $delivery->created_at?->format('H:i'),
                'verified_at' => $delivery->courier_verified_at?->format('H:i'),
                'picked_up_at' => $delivery->picked_up_at?->format('H:i'),
                'completed_at' => $delivery->completed_at?->format('H:i'),
            ],
        ];

        if ($forCourier) {
            $distanceToCafe = $delivery->hasCourierLocation()
                ? ZendoDelivery::distanceMeters($delivery->courier_lat, $delivery->courier_lng, $cafeLat, $cafeLng)
                : null;
            $isFresh = $secondsAgo !== null && $secondsAgo <= $staleSeconds;

            $payload['distance_to_cafe_m'] = $distanceToCafe !== null ? (int) round($distanceToCafe) : null;
            $payload['can_pickup'] = $delivery->status === 'assigned' && $isFresh && ! $delivery->isGpsLocked()
                && $distanceToCafe !== null && $distanceToCafe <= (int) config('nucomu.contact.zendo_tracking.pickup_radius_m', 500);
            $payload['can_complete'] = in_array($delivery->status, ['picked_up', 'almost_done'], true) && $isFresh && ! $delivery->isGpsLocked()
                && (! $delivery->hasDestinationPin() || ($remaining !== null && $remaining <= (int) config('nucomu.contact.zendo_tracking.dropoff_radius_m', 200)));
        }

        return $payload;
    }

    /**
     * Bind this device to the assigned courier's phone number and record verified timestamp.
     * Must only be called after the courier's WhatsApp number (and OTP, when enabled) has been verified.
     */
    public function bindCourierDevice(ZendoDelivery $delivery): string
    {
        $delivery->update(['courier_verified_at' => now()]);

        $cookieDays = (int) config('nucomu.contact.zendo_tracking.device_cookie_days', 30);
        $cookieValue = (string) (request()->cookie(self::DEVICE_COOKIE) ?: request()->header('X-Courier-Device', ''));
        if ($cookieValue !== '' && str_contains($cookieValue, '|')) {
            [$phone, $existingToken] = explode('|', $cookieValue, 2);
            if ($existingToken !== '' && hash_equals((string) $delivery->courier_phone, $phone)) {
                $storedHash = (string) Setting::get("zendo_device_{$phone}", '');
                if ($storedHash !== '' && hash_equals($storedHash, hash('sha256', $existingToken))) {
                    Cookie::queue(self::DEVICE_COOKIE, $delivery->courier_phone.'|'.$existingToken, $cookieDays * 24 * 60);

                    return $existingToken;
                }
            }
        }

        $deviceToken = Str::random(48);
        Setting::set("zendo_device_{$delivery->courier_phone}", hash('sha256', $deviceToken));
        Setting::set("zendo_connected_at_{$delivery->courier_phone}", now()->toDateTimeString());

        Cookie::queue(self::DEVICE_COOKIE, $delivery->courier_phone.'|'.$deviceToken, $cookieDays * 24 * 60);

        return $deviceToken;
    }

    /**
     * Check whether the current device is authorized as the delivery's assigned courier.
     */
    public function isVerifiedCourierDevice(Request $request, ZendoDelivery $delivery): bool
    {
        // 1. Direct match with courier token via query parameter or route
        if ($request->filled('k') && hash_equals($delivery->courier_token, (string) $request->query('k'))) {
            $this->bindCourierDevice($delivery);

            return true;
        }

        $routeToken = (string) ($request->route('token') ?? $request->input('courier_token', ''));
        if ($routeToken !== '' && hash_equals($delivery->courier_token, $routeToken)) {
            $this->bindCourierDevice($delivery);

            return true;
        }

        // 2. Cookie or Header match
        $cookieValue = (string) ($request->cookie(self::DEVICE_COOKIE) ?: $request->header('X-Courier-Device', ''));
        if ($cookieValue === '' || ! str_contains($cookieValue, '|')) {
            return false;
        }

        [$phone, $deviceToken] = explode('|', $cookieValue, 2);
        if ($deviceToken === '' || ! hash_equals((string) $delivery->courier_phone, $phone)) {
            return false;
        }

        $storedHash = (string) Setting::get("zendo_device_{$phone}", '');

        return $storedHash !== '' && hash_equals($storedHash, hash('sha256', $deviceToken));
    }

    protected function isGatewayEnabled(array $txSettings): bool
    {
        return trim((string) ($txSettings['zendo_wa_api_token'] ?? '')) !== ''
            && trim((string) ($txSettings['zendo_wa_api_url'] ?? '')) !== '';
    }

    protected function otpCacheKey(ZendoDelivery $delivery): string
    {
        return 'zendo_courier_otp_'.$delivery->id;
    }

    protected function maskPhone(string $phone): string
    {
        $length = strlen($phone);
        if ($length <= 6) {
            return '+'.$phone;
        }

        return '+'.substr($phone, 0, 4).str_repeat('•', $length - 7).substr($phone, -3);
    }

    protected function formatDistance(float $meters): string
    {
        return $meters >= 1000
            ? number_format($meters / 1000, 1, ',', '.').' km'
            : (int) round($meters).' m';
    }
}
