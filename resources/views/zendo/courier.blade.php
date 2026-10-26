<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Workspace Kurir Zendo — #{{ $delivery->order_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .leaflet-container { font-family: inherit; }
        .pulse-marker {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-ring 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 14px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
</head>
<body class="bg-[#121216] text-white antialiased min-h-screen flex flex-col justify-between">

    {{-- TOP BAR --}}
    <header class="bg-[#1c1c24]/95 backdrop-blur border-b border-white/10 px-4 py-3 sticky top-0 z-30 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center font-black text-sm">
                #{{ $delivery->courier_index }}
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wider text-amber-400 font-bold">Workspace Kurir Zendo</p>
                <h1 class="text-sm font-black text-white truncate max-w-[200px] sm:max-w-none">{{ $delivery->courier_name }}</h1>
            </div>
        </div>

        <div>
            @if($isVerified)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Terhubung
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40">
                    <i class="fa-solid fa-lock text-[10px]"></i> Verifikasi Nomor
                </span>
            @endif
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 max-w-lg w-full mx-auto p-4 space-y-4">

        {{-- FLASH MESSAGES & ERRORS --}}
        @if(session('verified'))
            <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 rounded-2xl p-4 text-xs font-semibold flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-400 text-base mt-0.5"></i>
                <div>
                    <p class="font-bold text-white">{{ session('verified') }}</p>
                    <p class="text-[11px] text-emerald-300/80 mt-0.5">Perangkat ini telah terkoneksi. Pengiriman GPS otomatis aktif.</p>
                </div>
            </div>
        @endif

        @if(session('otp_sent'))
            <div class="bg-blue-500/20 border border-blue-500/40 text-blue-200 rounded-2xl p-4 text-xs font-semibold flex items-center gap-3">
                <i class="fa-brands fa-whatsapp text-blue-400 text-lg"></i>
                <p>{{ session('otp_sent') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-500/20 border border-rose-500/40 text-rose-200 rounded-2xl p-4 text-xs font-semibold space-y-1">
                @foreach($errors->all() as $err)
                    <p class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                        <span>{{ $err }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        @if(! $isVerified)
            {{-- ======================================================== --}}
            {{-- SCREEN 1: VERIFIKASI NOMOR HP KURIR (SELEKSI HANYA KURIR) --}}
            {{-- ======================================================== --}}
            <div class="bg-[#1c1c24] border border-white/15 rounded-3xl p-6 sm:p-7 shadow-2xl space-y-6">
                <div class="text-center space-y-2">
                    <div class="w-16 h-16 rounded-3xl bg-amber-500/20 border border-amber-500/40 text-amber-300 flex items-center justify-center text-2xl mx-auto shadow-lg shadow-amber-500/10">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <h2 class="text-lg font-black text-white">Verifikasi Akses Nomor Kurir</h2>
                    <p class="text-xs text-gray-300 leading-relaxed max-w-sm mx-auto">
                        Link ini merupakan <strong>Link Khusus Kurir</strong> untuk pesanan <strong class="text-amber-400 font-mono">#{{ $delivery->order_code }}</strong>.
                        Sistem membatasi agar link ini <strong>hanya dapat dibuka oleh nomor WhatsApp kurir yang ditugaskan ({{ $maskedPhone }})</strong>.
                    </p>
                </div>

                {{-- Form Kirim OTP (jika Gateway aktif) --}}
                @if($gatewayEnabled)
                    <form method="POST" action="{{ route('zendo.courier.otp', $delivery->courier_token) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-300 mb-1.5">Nomor WhatsApp Kurir Anda</label>
                            <div class="flex gap-2">
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 0812xxxx atau 62812xxxx" required
                                       class="flex-1 bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono placeholder-gray-500 focus:outline-none focus:border-emerald-400">
                                <button type="submit"
                                        class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-black font-extrabold text-xs whitespace-nowrap transition">
                                    Minta OTP WA
                                </button>
                            </div>
                        </div>
                    </form>
                @endif

                {{-- Form Verifikasi & Binding Perangkat --}}
                <form method="POST" action="{{ route('zendo.courier.verify', $delivery->courier_token) }}" class="space-y-4 pt-2 border-t border-white/10">
                    @csrf

                    @if(! $gatewayEnabled)
                        <div>
                            <label class="block text-xs font-bold text-gray-300 mb-1.5">Masukkan Nomor WhatsApp Kurir Anda</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 0812xxxx atau 62812xxxx" required
                                   class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white font-mono placeholder-gray-500 focus:outline-none focus:border-emerald-400">
                            <p class="text-[11px] text-gray-400 mt-1">Sistem akan mencocokkan nomor ini dengan nomor tugas Kurir Zendo #{{ $delivery->courier_index }}.</p>
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-bold text-gray-300 mb-1.5">Konfirmasi Nomor & Kode OTP 6-Digit</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Nomor WhatsApp Kurir..." required
                                   class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white font-mono mb-2 focus:outline-none focus:border-emerald-400">
                            <input type="text" name="otp" inputmode="numeric" maxlength="6" placeholder="Masukkan 6 Digit OTP..." required
                                   class="w-full bg-black/50 border border-emerald-500/40 rounded-xl px-3.5 py-2.5 text-center text-lg font-mono font-black text-emerald-400 tracking-widest focus:outline-none focus:border-emerald-400">
                        </div>
                    @endif

                    <button type="submit"
                            class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 via-emerald-400 to-green-400 text-black font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:opacity-95 transition">
                        Verifikasi & Hubungkan Perangkat
                    </button>
                </form>

                <div class="bg-black/30 border border-white/10 rounded-2xl p-3 text-[11px] text-gray-400 space-y-1">
                    <p class="text-white font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-emerald-400"></i>
                        <span>Keamanan Perangkat Kurir:</span>
                    </p>
                    <p>Setelah nomor terverifikasi, perangkat ini akan terikat ke sistem sebagai {{ $delivery->courier_name }} dan siap menyiarkan GPS secara realtime.</p>
                </div>

                {{-- Opsi untuk Pelanggan / Bukan Kurir --}}
                <div class="pt-2 text-center border-t border-white/10 space-y-2">
                    <p class="text-[11px] text-gray-400">Bukan kurir yang ditugaskan untuk pesanan ini?</p>
                    <a href="{{ route('zendo.track', $delivery->tracking_token) }}"
                       class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/10">
                        <i class="fa-solid fa-map-location-dot text-emerald-400"></i>
                        <span>Lihat Posisi Kurir di Peta (Mode Pelanggan)</span>
                    </a>
                </div>
            </div>

        @else
            {{-- ======================================================== --}}
            {{-- SCREEN 2: WORKSPACE AKTIF KURIR (GPS LOCK & STREAMING) --}}
            {{-- ======================================================== --}}

            {{-- Status GPS Lock Banner --}}
            <div id="gpsBanner" class="bg-emerald-500/15 border border-emerald-500/30 rounded-2xl p-3.5 text-xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="w-3 h-3 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                    <div class="min-w-0">
                        <p class="font-bold text-emerald-300 text-xs truncate">🔒 GPS Lock Aktif (Anti Fake GPS)</p>
                        <p id="gpsStatusSub" class="text-[11px] text-gray-400 truncate">Menyiarkan posisi realtime ke pelanggan...</p>
                    </div>
                </div>
                <span id="gpsAccuracyBadge" class="px-2 py-0.5 rounded-lg bg-black/40 font-mono text-[10px] text-emerald-300 font-bold shrink-0">
                    Akurasi: -- m
                </span>
            </div>

            {{-- Warning Lock jika terdeteksi Fake GPS --}}
            <div id="fakeGpsWarning" class="hidden bg-rose-500/20 border-2 border-rose-500 text-rose-200 rounded-2xl p-4 text-xs space-y-1">
                <p class="font-extrabold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-ban text-rose-400 text-base"></i>
                    <span>GPS TERKUNCI (Terdeteksi Fake GPS / Mock Location)</span>
                </p>
                <p id="fakeGpsReason" class="text-rose-200 text-xs"></p>
                <p class="text-[11px] text-gray-300 pt-1">Matikan aplikasi lokasi tiruan / mock provider pada smartphone Anda agar pengiriman dapat dilanjutkan.</p>
            </div>

            {{-- Rincian Pengiriman --}}
            <div class="bg-[#1c1c24] border border-white/15 rounded-3xl p-5 shadow-xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kode Pesanan</span>
                        <p class="text-base font-black text-amber-400 font-mono">#{{ $delivery->order_code }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status Tahap</span>
                        <p id="stageLabel" class="text-xs font-black text-white uppercase">{{ $delivery->status_label }}</p>
                    </div>
                </div>

                {{-- Titik Tujuan Pelanggan --}}
                <div class="space-y-1.5">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-gray-400 flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-rose-400"></i> Alamat Pengantaran
                    </span>
                    <p class="text-sm font-bold text-white">{{ $delivery->delivery_address ?: 'Alamat Pelanggan' }}</p>
                    <p class="text-xs text-gray-400">Pemesan: <strong class="text-white">{{ $delivery->customer_name }}</strong></p>

                    @if($delivery->destination_lat && $delivery->destination_lng)
                        <div class="pt-2 flex gap-2">
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $delivery->destination_lat }},{{ $delivery->destination_lng }}" target="_blank"
                               class="flex-1 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition">
                                <i class="fa-solid fa-diamond-turn-right"></i>
                                <span>Buka Rute Google Maps ↗</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Live Mini Map --}}
                <div class="rounded-2xl overflow-hidden border border-white/10 h-48 relative">
                    <div id="courierMap" class="w-full h-full"></div>
                </div>

                {{-- Jarak & Radius Status --}}
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-black/40 border border-white/10 rounded-2xl p-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Jarak ke Nucomu Kafe</p>
                        <p id="distToCafeText" class="text-base font-black text-amber-400 font-mono mt-0.5">-- m</p>
                        <p class="text-[10px] text-gray-500">Radius Ambil: ≤ 500m</p>
                    </div>
                    <div class="bg-black/40 border border-white/10 rounded-2xl p-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Jarak ke Pelanggan</p>
                        <p id="distToDestText" class="text-base font-black text-emerald-400 font-mono mt-0.5">-- m</p>
                        <p class="text-[10px] text-gray-500">Radius Selesai: ≤ 200m</p>
                    </div>
                </div>

                {{-- ACTION BUTTONS (LOCKED TO VERIFIED GPS RADIUS) --}}
                <div class="pt-2 space-y-2">
                    {{-- Tombol 1: Ambil Pesanan di Kafe --}}
                    <div id="pickupActionWrap" class="{{ $delivery->status === 'assigned' ? '' : 'hidden' }}">
                        <button type="button" id="btnPickup" onclick="submitCourierStatus('picked_up')" disabled
                                class="w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed">
                            <i class="fa-solid fa-box-open"></i>
                            <span id="btnPickupText">Dekati Kafe untuk Ambil (≤ 500m)</span>
                        </button>
                    </div>

                    {{-- Tombol 2: Selesaikan Pengantaran --}}
                    <div id="completeActionWrap" class="{{ in_array($delivery->status, ['picked_up', 'almost_done']) ? '' : 'hidden' }}">
                        <button type="button" id="btnComplete" onclick="submitCourierStatus('completed')" disabled
                                class="w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed">
                            <i class="fa-solid fa-circle-check"></i>
                            <span id="btnCompleteText">Dekati Tujuan untuk Selesai (≤ 200m)</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Script GPS Engine & Anti Fake GPS --}}
            <script>
                const courierToken = '{{ $delivery->courier_token }}';
                const locationEndpoint = '{{ route("zendo.courier.location", $delivery->courier_token, false) }}';
                const statusEndpoint = '{{ route("zendo.courier.status", $delivery->courier_token, false) }}';
                const cafeCoords = { lat: {{ $cafeLat }}, lng: {{ $cafeLng }} };
                const destCoords = {
                    lat: {{ $delivery->destination_lat ?? 'null' }},
                    lng: {{ $delivery->destination_lng ?? 'null' }}
                };

                let courierMap = null;
                let courierMarker = null;
                let cafeMarker = null;
                let destMarker = null;
                let trailLine = null;

                let lastPosition = null;
                let isSending = false;

                function initCourierMap() {
                    courierMap = L.map('courierMap', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView([cafeCoords.lat, cafeCoords.lng], 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(courierMap);

                    // Cafe marker
                    const cafeIcon = L.divIcon({
                        className: '',
                        html: '<div class="w-7 h-7 rounded-full bg-[#2B1F1A] border-2 border-amber-400 text-amber-300 flex items-center justify-center text-xs"><i class="fa-solid fa-mug-hot"></i></div>',
                        iconSize: [28, 28],
                        iconAnchor: [14, 14]
                    });
                    cafeMarker = L.marker([cafeCoords.lat, cafeCoords.lng], { icon: cafeIcon }).addTo(courierMap);

                    // Dest marker
                    if (destCoords.lat && destCoords.lng) {
                        const destIcon = L.divIcon({
                            className: '',
                            html: '<div class="w-7 h-7 rounded-full bg-rose-600 border-2 border-white text-white flex items-center justify-center text-xs"><i class="fa-solid fa-location-dot"></i></div>',
                            iconSize: [28, 28],
                            iconAnchor: [14, 14]
                        });
                        destMarker = L.marker([destCoords.lat, destCoords.lng], { icon: destIcon }).addTo(courierMap);
                    }

                    trailLine = L.polyline([], { color: '#10b981', weight: 3, opacity: 0.8 }).addTo(courierMap);
                }

                // Client-side Anti-Fake-GPS heuristics
                function inspectAntiFakeGps(pos) {
                    const signals = [];
                    const coords = pos.coords;

                    // 1. Accuracy check: Fake GPS apps often report exactly 0m accuracy or mock flags
                    if (coords.accuracy <= 0) {
                        signals.push('accuracy_zero');
                    }

                    // 2. Mock location indicators in Android WebView / browser extensions
                    if (coords.isMock || coords.mocked || pos.isMock) {
                        signals.push('mock_flag_detected');
                    }

                    // 3. Teleportation / speed impossible without aircraft
                    if (lastPosition) {
                        const dt = Math.max(1, (pos.timestamp - lastPosition.timestamp) / 1000);
                        const dist = haversineDistance(lastPosition.coords.latitude, lastPosition.coords.longitude, coords.latitude, coords.longitude);
                        const speed = dist / dt; // m/s
                        if (dist > 300 && speed > 45) { // > 162 km/h
                            signals.push('teleport_velocity');
                        }
                    }

                    return signals;
                }

                function haversineDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371e3;
                    const φ1 = lat1 * Math.PI / 180;
                    const φ2 = lat2 * Math.PI / 180;
                    const Δφ = (lat2 - lat1) * Math.PI / 180;
                    const Δλ = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
                    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                }

                async function sendGpsReading(pos) {
                    if (isSending) return;
                    isSending = true;

                    const signals = inspectAntiFakeGps(pos);
                    const payload = {
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude,
                        accuracy: pos.coords.accuracy,
                        speed: pos.coords.speed,
                        heading: pos.coords.heading,
                        altitude: pos.coords.altitude,
                        timestamp: pos.timestamp,
                        signals: signals
                    };

                    try {
                        const res = await fetch(locationEndpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });
                        const data = await res.json();
                        handleServerResponse(data, pos);
                    } catch (err) {
                        console.warn('Gagal streaming GPS kurir:', err);
                    } finally {
                        isSending = false;
                        lastPosition = pos;
                    }
                }

                function handleServerResponse(data, pos) {
                    const warningBox = document.getElementById('fakeGpsWarning');
                    const reasonEl = document.getElementById('fakeGpsReason');

                    if (data.locked || data.suspicious) {
                        if (warningBox && reasonEl) {
                            reasonEl.innerText = data.message || 'Terdeteksi indikasi Fake GPS.';
                            warningBox.classList.remove('hidden');
                        }
                    } else if (warningBox) {
                        warningBox.classList.add('hidden');
                    }

                    // Update accuracy badge
                    const accEl = document.getElementById('gpsAccuracyBadge');
                    if (accEl) {
                        accEl.innerText = 'Akurasi: ±' + Math.round(pos.coords.accuracy) + ' m';
                    }

                    // Update Map Marker
                    const latLng = [pos.coords.latitude, pos.coords.longitude];
                    if (!courierMarker) {
                        const mIcon = L.divIcon({
                            className: '',
                            html: '<div class="w-8 h-8 rounded-full bg-emerald-500 border-2 border-white text-black flex items-center justify-center text-xs pulse-marker"><i class="fa-solid fa-motorcycle"></i></div>',
                            iconSize: [32, 32],
                            iconAnchor: [16, 16]
                        });
                        courierMarker = L.marker(latLng, { icon: mIcon }).addTo(courierMap);
                        courierMap.setView(latLng, 15);
                    } else {
                        courierMarker.setLatLng(latLng);
                    }

                    if (data.payload) {
                        updateCourierDashboard(data.payload);
                    }
                }

                function updateCourierDashboard(payload) {
                    const distCafeEl = document.getElementById('distToCafeText');
                    const distDestEl = document.getElementById('distToDestText');
                    const stageLabel = document.getElementById('stageLabel');

                    if (stageLabel) stageLabel.innerText = payload.status_label || '';

                    if (distCafeEl) {
                        distCafeEl.innerText = payload.distance_to_cafe_m !== null ? payload.distance_to_cafe_m + ' m' : '-- m';
                    }
                    if (distDestEl) {
                        distDestEl.innerText = payload.remaining_label || '-- m';
                    }

                    // Button Ambil Pesanan
                    const btnPickup = document.getElementById('btnPickup');
                    const btnPickupText = document.getElementById('btnPickupText');
                    if (btnPickup) {
                        if (payload.can_pickup) {
                            btnPickup.disabled = false;
                            btnPickup.className = 'w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition cursor-pointer';
                            btnPickupText.innerText = '✅ Ambil Pesanan Sekarang (Sudah di Kafe)';
                        } else {
                            btnPickup.disabled = true;
                            btnPickup.className = 'w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed';
                            btnPickupText.innerText = 'Dekati Kafe untuk Ambil (≤ 500m)';
                        }
                    }

                    // Button Selesaikan Pengantaran
                    const btnComplete = document.getElementById('btnComplete');
                    const btnCompleteText = document.getElementById('btnCompleteText');
                    if (btnComplete) {
                        if (payload.can_complete) {
                            btnComplete.disabled = false;
                            btnComplete.className = 'w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition cursor-pointer';
                            btnCompleteText.innerText = '✅ Selesaikan Pengantaran (Sudah di Lokasi)';
                        } else {
                            btnComplete.disabled = true;
                            btnComplete.className = 'w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed';
                            btnCompleteText.innerText = 'Dekati Tujuan untuk Selesai (≤ 200m)';
                        }
                    }
                }

                async function submitCourierStatus(status) {
                    try {
                        const res = await fetch(statusEndpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: status })
                        });
                        const data = await res.json();
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Gagal mengubah status.');
                        }
                    } catch (err) {
                        alert('Terjadi kesalahan koneksi.');
                    }
                }

                // Start HTML5 High Accuracy Geolocation Watcher
                function startGpsStreaming() {
                    if (!navigator.geolocation) {
                        const badge = document.getElementById('gpsAccuracyBadge');
                        if (badge) badge.innerText = 'GPS: Tidak didukung browser';
                        return;
                    }

                    // Immediate position check
                    navigator.geolocation.getCurrentPosition(
                        sendGpsReading,
                        (err) => {
                            console.warn('GPS initial err:', err.message);
                            const badge = document.getElementById('gpsAccuracyBadge');
                            if (badge) badge.innerText = err.code === 1 ? 'GPS: Izin lokasi ditolak' : 'GPS: Mencari sinyal...';
                        },
                        { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
                    );

                    // Continuous position watcher
                    navigator.geolocation.watchPosition(
                        sendGpsReading,
                        (err) => {
                            console.warn('GPS watch err:', err.message);
                        },
                        {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 10000
                        }
                    );
                }

                document.addEventListener('DOMContentLoaded', () => {
                    initCourierMap();
                    startGpsStreaming();
                });
            </script>
        @endif
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#181820] border-t border-white/5 py-3 px-4 text-center text-[10px] text-gray-500">
        Nucomu Cafe &copy; 2026 — Zendo Delivery System with Anti Fake-GPS Protection
    </footer>
</body>
</html>
