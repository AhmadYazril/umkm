<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $isCourier ? 'Workspace Kurir Zendo' : 'Live Tracking Pesanan' }} — #{{ $delivery->order_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .leaflet-container { font-family: inherit; }
        .leaflet-control-attribution { font-size: 9px !important; opacity: 0.7; }
        .pulse-marker {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-ring 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 14px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .courier-motor-icon {
            transition: transform 0.4s ease-out;
        }
    </style>
</head>
<body class="bg-[#121216] text-white antialiased flex flex-col h-screen overflow-hidden">

    {{-- Top App Bar --}}
    <header class="bg-[#1c1c24]/95 backdrop-blur border-b border-white/10 px-4 py-3 shrink-0 z-20 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ url('/') }}" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs text-white transition shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    @if($isCourier)
                        <span class="text-xs font-black tracking-wider uppercase text-emerald-400">
                            <i class="fa-solid fa-motorcycle mr-1"></i> Mode Kurir
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 truncate">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                            {{ $delivery->courier_name }} ({{ $delivery->courier_phone }})
                        </span>
                        <a href="{{ route('zendo.track', $delivery->tracking_token) }}" title="Lihat tampilan dari sisi pelanggan" class="px-2 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white border border-white/10 text-[9px] font-bold transition">
                            <i class="fa-solid fa-eye"></i> Tampilan Pelanggan
                        </a>
                        <form method="POST" action="{{ route('zendo.courier.unbind') }}" class="inline ml-1" onsubmit="try { localStorage.removeItem('zendo_courier_permanent_access'); localStorage.removeItem('zendo_courier_device_token'); } catch(e){} return confirm('Keluar dari Mode Kurir pada perangkat ini? Anda harus memasukkan PIN lagi jika ingin masuk kembali.');">
                            @csrf
                            <button type="submit" title="Lepas perangkat ini dari Mode Kurir" class="px-2 py-0.5 rounded-md bg-zinc-800/80 hover:bg-rose-950 text-gray-400 hover:text-rose-300 border border-white/10 text-[9px] font-bold transition">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    @else
                        <span class="text-xs font-black tracking-wider uppercase text-amber-400">Nucomu Cafe x Zendo</span>
                        <span id="liveBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Live Tracking
                        </span>
                        <button type="button" onclick="openCourierPinModal()" class="px-2 py-0.5 rounded-md bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 text-[9px] font-bold transition flex items-center gap-1 ml-1 cursor-pointer">
                            <i class="fa-solid fa-motorcycle"></i> Masuk Mode Kurir
                        </button>
                    @endif
                </div>
                <h1 class="text-sm font-extrabold text-white truncate">Pesanan #{{ $delivery->order_code }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span id="statusHeaderBadge" class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                {{ $delivery->status === 'completed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : ($delivery->status === 'almost_done' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-blue-500/20 text-blue-300 border border-blue-500/40') }}">
                {{ $delivery->status_label }}
            </span>
        </div>
    </header>

    @if(session('info'))
        <div class="bg-blue-500/20 border-b border-blue-500/30 text-blue-200 px-4 py-2.5 text-xs flex items-center justify-between gap-3 shrink-0 z-20">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-400"></i>
                <span>{{ session('info') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-blue-300 hover:text-white">&times;</button>
        </div>
    @endif

    {{-- Main Viewport: Leaflet Map + Floating Cards --}}
    <div class="relative flex-1 w-full overflow-hidden">
        {{-- Map Container --}}
        <div id="trackingMap" class="w-full h-full z-0 bg-[#16161d]"></div>

        {{-- Floating Recenter Map Button --}}
        <button type="button" onclick="recenterMap()"
                class="absolute right-4 top-4 z-10 w-10 h-10 rounded-full bg-[#1c1c24]/90 border border-white/15 text-white shadow-xl flex items-center justify-center hover:bg-white/20 active:scale-95 transition"
                title="Pusatkan Peta ke Kurir">
            <i class="fa-solid fa-location-crosshairs text-sm text-emerald-400"></i>
        </button>

        {{-- ======================================================== --}}
        {{-- MODE 1: WORKSPACE KURIR AKTIF (JIKA DIAKSES DARI LINK KURIR) --}}
        {{-- ======================================================== --}}
        @if($isCourier)
            <div class="absolute inset-x-0 bottom-0 z-10 max-w-xl mx-auto p-3 sm:p-4 max-h-[75vh] overflow-y-auto">
                <div class="bg-[#1c1c24]/95 backdrop-blur-xl border border-white/15 rounded-3xl p-4 sm:p-5 shadow-2xl space-y-3.5">

                    {{-- GPS Status Bar --}}
                    <div id="gpsBanner" class="bg-emerald-500/15 border border-emerald-500/30 rounded-2xl p-3 text-xs flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                            <div class="min-w-0">
                                <p class="font-bold text-emerald-300 text-xs truncate">🔒 GPS Lock Aktif (Anti Fake GPS)</p>
                                <p id="gpsStatusSub" class="text-[10px] text-gray-400 truncate">Menyiarkan lokasi ke pelanggan & peta live...</p>
                            </div>
                        </div>
                        <span id="gpsAccuracyBadge" class="px-2 py-0.5 rounded-lg bg-black/40 font-mono text-[10px] text-emerald-300 font-bold shrink-0">
                            Akurasi: Menghubungkan...
                        </span>
                    </div>

                    {{-- Fake GPS Warning Alert --}}
                    <div id="fakeGpsWarning" class="hidden bg-rose-500/20 border-2 border-rose-500 text-rose-200 rounded-2xl p-3 text-xs space-y-1">
                        <p class="font-extrabold text-white text-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-ban text-rose-400"></i>
                            <span>GPS TERKUNCI (Terdeteksi Mock Location / Fake GPS)</span>
                        </p>
                        <p id="fakeGpsReason" class="text-rose-200 text-[11px]"></p>
                    </div>

                    {{-- Rincian Tujuan & Jarak Realtime --}}
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
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

                    {{-- Tombol Aksi Kurir --}}
                    <div class="space-y-2">
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

                        {{-- Status Selesai --}}
                        @if($delivery->status === 'completed')
                            <div class="bg-emerald-500/20 border border-emerald-500/40 rounded-2xl p-3 text-center space-y-0.5">
                                <p class="text-emerald-300 font-black text-xs">✅ Pengantaran Telah Selesai</p>
                                <p class="text-[10px] text-gray-400">Terima kasih atas kerja keras Anda! Status Anda kembali siap menerima order berikutnya.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Info Alamat Pelanggan --}}
                    <div class="pt-2 border-t border-white/10 text-xs space-y-1">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Pemesan & Alamat</p>
                                <p class="text-white font-bold">{{ $delivery->customer_name }} — <span class="font-normal text-gray-300">{{ $delivery->delivery_address ?: 'Titik Lokasi Peta' }}</span></p>
                            </div>
                            @if($delivery->destination_lat && $delivery->destination_lng)
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $delivery->destination_lat }},{{ $delivery->destination_lng }}" target="_blank"
                                   class="px-2.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-[10px] flex items-center gap-1 shrink-0 transition">
                                    <i class="fa-solid fa-diamond-turn-right"></i> Maps ↗
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        {{-- ======================================================== --}}
        {{-- MODE 2: TAMPILAN PELACAKAN PELANGGAN (LIVE TRACKING GRAB) --}}
        {{-- ======================================================== --}}
        @else
            <div class="absolute inset-x-0 bottom-0 z-10 max-w-xl mx-auto p-3 sm:p-4 max-h-[75vh] overflow-y-auto">
                <div class="bg-[#1c1c24]/95 backdrop-blur-xl border border-white/15 rounded-3xl p-4 sm:p-5 shadow-2xl space-y-4">

                    {{-- ETA & Jarak Sisa --}}
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Estimasi Tiba (ETA)</p>
                            <p id="etaText" class="text-2xl font-black text-amber-400 font-mono mt-0.5">
                                {{ $initialPayload['eta_minutes'] ? '~'.$initialPayload['eta_minutes'].' Menit' : ($delivery->status === 'completed' ? 'Tiba di Lokasi' : 'Menghitung...') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Jarak Sisa</p>
                            <p id="remainingText" class="text-base font-extrabold text-white font-mono mt-0.5">
                                {{ $initialPayload['remaining_label'] ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Kurir Profile & Kontak WhatsApp --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-green-400 text-black flex items-center justify-center text-xl font-black shadow-lg shadow-emerald-500/20 shrink-0">
                                <i class="fa-solid fa-motorcycle"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-sm font-black text-white truncate">{{ $delivery->courier_name }}</h2>
                                <p class="text-xs text-emerald-400 font-mono flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                    <span>Kurir Terverifikasi (+{{ $delivery->courier_phone }})</span>
                                </p>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ $delivery->courier_phone }}" target="_blank"
                           class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-black font-extrabold text-xs flex items-center gap-1.5 shadow-md shadow-emerald-500/20 transition shrink-0">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Chat Kurir</span>
                        </a>
                    </div>

                    {{-- Status GPS Kurir & Anti Fake-GPS --}}
                    <div class="bg-black/40 border border-white/10 rounded-2xl p-3 text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 flex items-center gap-1.5 text-[11px]">
                                <i class="fa-solid fa-satellite-dish text-emerald-400"></i>
                                <span>Status GPS Kurir:</span>
                            </span>
                            <span id="gpsStatusBadge" class="font-bold text-[11px] {{ $delivery->hasCourierLocation() ? 'text-emerald-400' : 'text-amber-400' }}">
                                {{ $delivery->hasCourierLocation() ? '🟢 GPS Terhubung Aktif' : '🟡 Menunggu Sinyal GPS' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-gray-500">
                            <span>Proteksi GPS Lock:</span>
                            <span class="text-emerald-400 font-semibold">Aktif (Anti Fake GPS)</span>
                        </div>
                    </div>

                    {{-- Rincian Pesanan (Bisa Dilipat) --}}
                    <details class="group bg-white/5 rounded-2xl border border-white/10 overflow-hidden text-xs">
                        <summary class="px-3.5 py-2.5 font-bold text-gray-300 cursor-pointer flex items-center justify-between select-none hover:text-white">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-rose-400"></i>
                                <span>Tujuan: {{ Str::limit($delivery->delivery_address ?: 'Titik Lokasi Pelanggan', 30) }}</span>
                            </span>
                            <i class="fa-solid fa-chevron-down text-[10px] transition group-open:rotate-180"></i>
                        </summary>
                        <div class="p-3.5 pt-1 space-y-2 text-gray-400 border-t border-white/5">
                            <p class="text-white font-medium">{{ $delivery->delivery_address ?: '-' }}</p>
                            @if($delivery->order)
                                <div class="pt-2 border-t border-white/5 space-y-1 text-[11px]">
                                    <p class="font-bold text-gray-300">Menu Pesanan:</p>
                                    @foreach($delivery->order->items as $item)
                                        <div class="flex justify-between">
                                            <span>{{ $item->qty }}x {{ $item->menu_name }}</span>
                                            <span class="font-mono text-gray-300">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between font-bold text-white pt-1 border-t border-white/10">
                                        <span>Total:</span>
                                        <span class="font-mono text-amber-400">Rp {{ number_format($delivery->order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </details>

                </div>
            </div>
        @endif
    </div>

    {{-- JAVASCRIPT ENGINE (MAP, GPS, CLIENT HAIVERSINE, REALTIME STREAMING) --}}
    <script>
        const isCourierMode = {{ $isCourier ? 'true' : 'false' }};
        const initialData = @json($initialPayload);
        const dataUrl = '{{ route("zendo.track.data", $delivery->tracking_token, false) }}';
        const locationUrl = '{{ route("zendo.track.location", $delivery->tracking_token, false) }}';
        const statusUrl = '{{ route("zendo.track.status", $delivery->tracking_token, false) }}';

        const cafeCoords = {
            lat: {{ $cafeLat }},
            lng: {{ $cafeLng }}
        };
        const destCoords = {
            lat: {{ $delivery->destination_lat ?? 'null' }},
            lng: {{ $delivery->destination_lng ?? 'null' }}
        };

        let map = null;
        let courierMarker = null;
        let cafeMarker = null;
        let destMarker = null;
        let trailPolyline = null;
        let firstFitDone = false;
        let lastPosition = null;
        let isSendingGps = false;

        // Initialize Map with Official Free OpenStreetMap Basemap (NO API KEY REQUIRED)
        function initMap() {
            const cafe = initialData.cafe;
            const dest = initialData.destination;
            const courier = initialData.courier;

            const centerLat = (courier && courier.lat) ? courier.lat : (dest.lat || cafe.lat);
            const centerLng = (courier && courier.lng) ? courier.lng : (dest.lng || cafe.lng);

            map = L.map('trackingMap', {
                zoomControl: false,
                attributionControl: true
            }).setView([centerLat, centerLng], 14);

            // OpenStreetMap Standard Tiles (No API Key Required, Fast, 100% Free)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // 1. Cafe Marker
            const cafeIcon = L.divIcon({
                className: '',
                html: `
                    <div class="w-9 h-9 rounded-full bg-[#2B1F1A] border-2 border-amber-400 flex items-center justify-center text-amber-300 text-sm shadow-xl">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                `,
                iconSize: [36, 36],
                iconAnchor: [18, 18]
            });
            cafeMarker = L.marker([cafe.lat, cafe.lng], { icon: cafeIcon }).addTo(map)
                .bindPopup('<strong class="text-xs">Nucomu Cafe</strong><br><span class="text-[10px]">Titik Asal Pesanan</span>');

            // 2. Destination Marker (Customer)
            if (dest.lat && dest.lng) {
                const destIcon = L.divIcon({
                    className: '',
                    html: `
                        <div class="w-9 h-9 rounded-full bg-rose-600 border-2 border-white flex items-center justify-center text-white text-sm shadow-xl">
                            <i class="fa-solid fa-house-chimney"></i>
                        </div>
                    `,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18]
                });
                destMarker = L.marker([dest.lat, dest.lng], { icon: destIcon }).addTo(map)
                    .bindPopup('<strong class="text-xs">Titik Pengantaran</strong><br><span class="text-[10px]">' + (dest.address || 'Alamat Pelanggan') + '</span>');
            }

            // 3. Polyline Route Trail
            trailPolyline = L.polyline([], {
                color: '#10b981',
                weight: 4,
                opacity: 0.85,
                dashArray: '6, 8',
                lineJoin: 'round'
            }).addTo(map);

            // 4. Update initial marker & trail
            if (courier && courier.lat !== null) {
                updateCourierMarker(courier);
            }
            if (initialData.trail) {
                updateTrail(initialData.trail);
            }

            fitBounds();

            // If initial data has coordinates, compute initial distances in courier mode
            if (isCourierMode && courier && courier.lat !== null) {
                updateCourierDistances(courier.lat, courier.lng, courier.accuracy || 10);
            }
        }

        function createCourierIcon(heading) {
            const rot = (typeof heading === 'number' && !isNaN(heading)) ? heading : 0;
            return L.divIcon({
                className: '',
                html: `
                    <div class="relative w-12 h-12 flex items-center justify-center pulse-marker rounded-full">
                        <div class="courier-motor-icon w-10 h-10 rounded-full bg-emerald-500 border-2 border-white shadow-2xl flex items-center justify-center text-black font-black text-base"
                             style="transform: rotate(${rot}deg);">
                            <i class="fa-solid fa-motorcycle"></i>
                        </div>
                    </div>
                `,
                iconSize: [48, 48],
                iconAnchor: [24, 24]
            });
        }

        function updateCourierMarker(courier) {
            if (!courier || courier.lat === null || courier.lng === null) {
                return;
            }

            const latLng = [courier.lat, courier.lng];
            const icon = createCourierIcon(courier.heading);

            if (!courierMarker) {
                courierMarker = L.marker(latLng, { icon: icon }).addTo(map)
                    .bindPopup('<strong>' + (courier.name || 'Kurir Zendo') + '</strong><br>Sedang mengantar pesanan.');
            } else {
                courierMarker.setLatLng(latLng);
                courierMarker.setIcon(icon);
            }
        }

        function updateTrail(trailPoints) {
            if (!trailPolyline) return;
            if (Array.isArray(trailPoints) && trailPoints.length > 0) {
                trailPolyline.setLatLngs(trailPoints);
            }
        }

        function fitBounds() {
            if (!map) return;
            const group = [];
            if (cafeMarker) group.push(cafeMarker.getLatLng());
            if (destMarker) group.push(destMarker.getLatLng());
            if (courierMarker) group.push(courierMarker.getLatLng());

            if (group.length > 0) {
                map.fitBounds(L.latLngBounds(group), {
                    padding: [60, 60],
                    maxZoom: 16
                });
            }
        }

        function recenterMap() {
            if (courierMarker) {
                map.setView(courierMarker.getLatLng(), 16);
            } else {
                fitBounds();
            }
        }

        // Haversine Distance in meters (instant client calculation)
        function computeHaversineMeters(lat1, lon1, lat2, lon2) {
            const R = 6371e3;
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        function formatDistanceMeters(meters) {
            if (meters === null || meters === undefined || isNaN(meters)) return '-- m';
            if (meters >= 1000) {
                return (meters / 1000).toFixed(1).replace('.', ',') + ' km';
            }
            return Math.round(meters) + ' m';
        }

        // Update Courier distances & action buttons immediately in UI
        function updateCourierDistances(curLat, curLng, accuracy) {
            const distCafeEl = document.getElementById('distToCafeText');
            const distDestEl = document.getElementById('distToDestText');
            const accBadge = document.getElementById('gpsAccuracyBadge');

            if (accBadge && accuracy !== undefined) {
                accBadge.innerText = 'Akurasi: ±' + Math.round(accuracy) + ' m';
            }

            const distToCafe = computeHaversineMeters(curLat, curLng, cafeCoords.lat, cafeCoords.lng);
            if (distCafeEl) {
                distCafeEl.innerText = formatDistanceMeters(distToCafe);
            }

            let distToDest = null;
            if (destCoords.lat && destCoords.lng) {
                distToDest = computeHaversineMeters(curLat, curLng, destCoords.lat, destCoords.lng);
                if (distDestEl) {
                    distDestEl.innerText = formatDistanceMeters(distToDest);
                }
            }

            // Update Button Ambil
            const btnPickup = document.getElementById('btnPickup');
            const btnPickupText = document.getElementById('btnPickupText');
            if (btnPickup) {
                if (distToCafe <= 500) {
                    btnPickup.disabled = false;
                    btnPickup.className = 'w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition cursor-pointer';
                    btnPickupText.innerText = '✅ Ambil Pesanan Sekarang (Sudah di Kafe)';
                } else {
                    btnPickup.disabled = true;
                    btnPickup.className = 'w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed';
                    btnPickupText.innerText = 'Dekati Kafe untuk Ambil (≤ 500m)';
                }
            }

            // Update Button Selesai
            const btnComplete = document.getElementById('btnComplete');
            const btnCompleteText = document.getElementById('btnCompleteText');
            if (btnComplete) {
                if (distToDest !== null && distToDest <= 200) {
                    btnComplete.disabled = false;
                    btnComplete.className = 'w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition cursor-pointer';
                    btnCompleteText.innerText = '✅ Selesaikan Pengantaran (Sudah di Tujuan)';
                } else {
                    btnComplete.disabled = true;
                    btnComplete.className = 'w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed';
                    btnCompleteText.innerText = 'Dekati Tujuan untuk Selesai (≤ 200m)';
                }
            }
        }

        // Send GPS Reading to server with Anti-Fake GPS checks
        async function sendGpsReading(pos) {
            if (isSendingGps) return;
            isSendingGps = true;

            const coords = pos.coords;
            updateCourierDistances(coords.latitude, coords.longitude, coords.accuracy);

            // Update marker on map
            updateCourierMarker({
                lat: coords.latitude,
                lng: coords.longitude,
                heading: coords.heading,
                name: '{{ $delivery->courier_name }}'
            });

            // Anti-fake GPS heuristics
            const signals = [];
            if (coords.accuracy <= 0) signals.push('accuracy_zero');
            if (coords.isMock || coords.mocked || pos.isMock) signals.push('mock_flag_detected');
            if (lastPosition) {
                const dt = Math.max(1, (pos.timestamp - lastPosition.timestamp) / 1000);
                const dist = computeHaversineMeters(lastPosition.coords.latitude, lastPosition.coords.longitude, coords.latitude, coords.longitude);
                if (dist > 300 && (dist / dt) > 45) { // > 162 km/h
                    signals.push('teleport_velocity');
                }
            }

            const payload = {
                lat: coords.latitude,
                lng: coords.longitude,
                accuracy: coords.accuracy || 10,
                speed: coords.speed,
                heading: coords.heading,
                altitude: coords.altitude,
                timestamp: pos.timestamp || Date.now(),
                signals: signals
            };

            try {
                const res = await fetch(locationUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (res.status === 403 || data.revoked) {
                    try {
                        localStorage.removeItem('zendo_courier_permanent_access');
                        localStorage.removeItem('zendo_courier_device_token');
                    } catch (e) {}
                    alert(data.message || 'Akses kurir pada perangkat ini telah direset oleh admin.');
                    window.location.reload();
                    return;
                }
                handleGpsServerFeedback(data);
            } catch (err) {
                console.warn('Gagal streaming GPS kurir:', err);
            } finally {
                isSendingGps = false;
                lastPosition = pos;
            }
        }

        function handleGpsServerFeedback(data) {
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

            if (data.payload && data.payload.trail) {
                updateTrail(data.payload.trail);
            }
        }

        // Submit Status Change (Ambil Pesanan / Selesai)
        async function submitCourierStatus(status) {
            try {
                const res = await fetch(statusUrl, {
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

        // Start High Accuracy GPS Streaming for Courier
        function startCourierGps() {
            if (!navigator.geolocation) {
                const badge = document.getElementById('gpsAccuracyBadge');
                if (badge) badge.innerText = 'GPS: Tidak didukung browser';
                return;
            }

            // 1. Immediate position attempt
            navigator.geolocation.getCurrentPosition(
                sendGpsReading,
                (err) => {
                    console.warn('GPS initial err:', err.message);
                    const badge = document.getElementById('gpsAccuracyBadge');
                    if (badge) badge.innerText = err.code === 1 ? 'GPS: Izin lokasi ditolak' : 'GPS: Mencari sinyal...';
                },
                { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
            );

            // 2. Continuous position watcher
            navigator.geolocation.watchPosition(
                sendGpsReading,
                (err) => {
                    console.warn('GPS watch err:', err.message);
                },
                { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 }
            );
        }

        // Customer Mode: Poll Live Status every 3.5 seconds
        async function pollTracking() {
            try {
                const res = await fetch(dataUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    applyCustomerPayload(data);
                }
            } catch (err) {
                console.warn('Gagal memuat live data:', err);
            }
        }

        function applyCustomerPayload(data) {
            if (!data) return;

            const etaEl = document.getElementById('etaText');
            if (etaEl) {
                etaEl.innerText = data.eta_minutes ? `~${data.eta_minutes} Menit` : (data.status === 'completed' ? 'Tiba di Lokasi' : 'Menghitung...');
            }

            const remEl = document.getElementById('remainingText');
            if (remEl) {
                remEl.innerText = data.remaining_label || '-';
            }

            const badgeEl = document.getElementById('statusHeaderBadge');
            if (badgeEl) {
                badgeEl.innerText = data.status_label || '';
            }

            const gpsBadge = document.getElementById('gpsStatusBadge');
            if (gpsBadge) {
                if (data.gps && data.gps.locked) {
                    gpsBadge.className = 'font-bold text-[11px] text-rose-400';
                    gpsBadge.innerText = '🔴 GPS Terkunci (Fake GPS Terdeteksi)';
                } else if (data.courier && data.courier.lat !== null) {
                    gpsBadge.className = 'font-bold text-[11px] text-emerald-400';
                    gpsBadge.innerText = data.courier.is_stale ? '🟡 Sinyal Terakhir ' + data.courier.seconds_ago + 's lalu' : '🟢 GPS Terhubung Aktif';
                } else {
                    gpsBadge.className = 'font-bold text-[11px] text-amber-400';
                    gpsBadge.innerText = '🟡 Menunggu Sinyal GPS Kurir';
                }
            }

            updateCourierMarker(data.courier);
            updateTrail(data.trail);

            if (!firstFitDone && data.courier && data.courier.lat) {
                fitBounds();
                firstFitDone = true;
            }
        }

        // Courier PIN Modal (1 Link Tunggal)
        function openCourierPinModal() {
            // Jika perangkat ini sebelumnya membuka tampilan pelanggan, langsung kembali ke Mode Kurir
            if (window.location.search.includes('view=customer')) {
                window.location.href = '{{ route("zendo.track", $delivery->tracking_token, false) }}';
                return;
            }

            const modal = document.getElementById('courierPinModal');
            const input = document.getElementById('inputCourierPin');
            const err = document.getElementById('pinErrorMsg');
            if (err) err.classList.add('hidden');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            if (input) {
                input.value = '';
                setTimeout(() => input.focus(), 100);
            }
        }

        function closeCourierPinModal() {
            const modal = document.getElementById('courierPinModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        async function submitCourierPin(e) {
            e.preventDefault();
            const pin = document.getElementById('inputCourierPin').value;
            const err = document.getElementById('pinErrorMsg');
            const btn = document.getElementById('btnSubmitPin');
            if (!pin) return;

            btn.disabled = true;
            btn.innerText = 'Memverifikasi...';
            if (err) err.classList.add('hidden');

            try {
                const res = await fetch('{{ route("zendo.track.pin", $delivery->tracking_token, false) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pin: pin })
                });
                const data = await res.json();
                if (data.success && data.redirect_url) {
                    try {
                        localStorage.setItem('zendo_courier_permanent_access', '1');
                        if (data.device_token) {
                            localStorage.setItem('zendo_courier_device_token', data.device_token);
                        }
                    } catch (e) {}
                    window.location.href = data.redirect_url;
                } else {
                    if (err) {
                        err.innerText = data.message || 'PIN Kurir salah. Silakan coba lagi.';
                        err.classList.remove('hidden');
                    }
                }
            } catch (ex) {
                if (err) {
                    err.innerText = 'Gagal menghubungi server.';
                    err.classList.remove('hidden');
                }
            } finally {
                btn.disabled = false;
                btn.innerText = 'Masuk';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initMap();

            // Auto-restore Mode Kurir permanen jika perangkat ini sebelumnya sudah diverifikasi via PIN
            if (!isCourierMode && !window.location.search.includes('view=customer')) {
                try {
                    const savedToken = localStorage.getItem('zendo_courier_device_token');
                    const hasPermanent = localStorage.getItem('zendo_courier_permanent_access') === '1';
                    if (hasPermanent && savedToken) {
                        fetch('{{ route("zendo.track.pin", $delivery->tracking_token, false) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ device_token: savedToken })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.redirect_url) {
                                window.location.href = data.redirect_url;
                            }
                        })
                        .catch(() => {});
                    }
                } catch (e) {}
            }

            if (isCourierMode) {
                startCourierGps();
            } else {
                setInterval(pollTracking, 3500);
            }
        });
    </script>

    {{-- MODAL INPUT PIN KURIR (1 LINK TUNGGAL) --}}
    <div id="courierPinModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-[#1c1c24] border border-white/20 rounded-3xl max-w-xs w-full p-6 shadow-2xl space-y-4 text-center animate-fade-in">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-amber-400 flex items-center justify-center text-xl mx-auto">
                <i class="fa-solid fa-key"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-white">Aktifkan Mode Kurir</h3>
                <p class="text-xs text-gray-400 mt-1">Khusus kurir Zendo. Masukkan PIN 1 kali, perangkat ini akan otomatis terakses selamanya.</p>
            </div>
            <form onsubmit="submitCourierPin(event)" class="space-y-3">
                <input type="password" id="inputCourierPin" inputmode="numeric" maxlength="10" placeholder="PIN (Default: 1234)" autofocus required
                       class="w-full bg-black/60 border border-white/15 focus:border-emerald-400 rounded-xl px-4 py-2.5 text-center text-lg font-mono font-bold tracking-widest text-emerald-400 focus:outline-none">
                <p id="pinErrorMsg" class="hidden text-xs text-rose-400 font-semibold"></p>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeCourierPinModal()"
                            class="flex-1 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitPin"
                            class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-green-400 text-black font-black text-xs uppercase tracking-wider transition">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
