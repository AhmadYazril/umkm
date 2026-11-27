# BAB IV
# IMPLEMENTASI SISTEM

## 4.1 Teknologi yang Digunakan
Dalam proses implementasi website Sistem Informasi Profil dan E-Order Nucomu Cafe & Roastery, digunakan beberapa teknologi untuk mendukung pembangunan sistem secara menyeluruh, mulai dari pembuatan struktur halaman antarmuka, penataan desain responsif, pengolahan logika bisnis di sisi server, interaktivitas sisi klien, hingga pengelolaan basis data relasional dan visualisasi peta geolokasi.

### Tabel 4.1 Teknologi yang Digunakan
| No | Teknologi | Jenis | Fungsi |
| :---: | :--- | :--- | :--- |
| 1 | **PHP (v8.5 / ^8.3)** | Bahasa Pemrograman Backend | Digunakan untuk mengelola logika sistem server-side, validasi formulir, kalkulasi rumus jarak Haversine, orkestrasi basis data, dan pembuatan dokumen biner PDF. |
| 2 | **Laravel Framework (v13.35.0)** | Kerangka Kerja Web (Web Framework) | Digunakan sebagai kerangka kerja utama berbasis arsitektur *Model-View-Controller* (MVC), menyediakan Eloquent ORM, rute (*routing*), middleware otentikasi/CSRF, dan mesin templating Blade. |
| 3 | **HTML5** | Bahasa Markup | Digunakan untuk menyusun struktur semantik elemen halaman web publik profil kafe, antarmuka keranjang belanja, formulir reservasi, serta panel kasir POS dan admin. |
| 4 | **Tailwind CSS (v4.0.0)** | Kerangka Kerja CSS (*Utility-First*) | Digunakan untuk merancang antarmuka pengguna yang responsif di berbagai resolusi layar (ponsel pintar, tablet, desktop) dengan tema estetika *Dark Warm Coffee*. |
| 5 | **JavaScript (ES6+)** | Bahasa Pemrograman Sisi Klien | Digunakan untuk memberikan interaktivitas dinamis pada antarmuka web, seperti *Cart Drawer* berbasis *LocalStorage*, kalkulator kembalian kasir, modal dialog, pemindai kamera slip QR, dan *polling* live tracking kurir. |
| 6 | **Leaflet.js (v1.9.4) & OpenStreetMap** | Pustaka Peta & Geolokasi (*Mapping Library*) | Digunakan untuk menampilkan peta interaktif pelacakan pengiriman kurir Zendo secara langsung (*real-time*), penanda koordinat kafe/pelanggan, dan lintasan rute kurir. |
| 7 | **MySQL / MariaDB** | Sistem Manajemen Basis Data (RDBMS) | Digunakan untuk menyimpan seluruh data relasional sistem secara terstruktur dalam 17 tabel fisik (katalog menu, varian, pesanan, kurir logistik, ulasan, pengaturan, dan log audit). |
| 8 | **phpMyAdmin** | Alat Pengelola Basis Data Berbasis Web | Digunakan sebagai antarmuka grafis (GUI) lokal untuk memantau struktur tabel basis data, menjalankan kueri pengujian, dan mengelola ekspor/impor data transaksi. |
| 9 | **Vite (v8.0.0)** | *Frontend Build Tool & Asset Bundler* | Digunakan untuk mengompilasi, memaketkan (*bundling*), dan mengoptimalkan aset skrip JavaScript dan *stylesheet* CSS dengan dukungan *Hot Module Replacement* (HMR). |

---

## 4.2 Implementasi Coding Sistem
Pada tahap implementasi coding, website Nucomu Cafe & Roastery dikembangkan menggunakan bahasa pemrograman PHP 8 dengan kerangka kerja Laravel pada sisi *backend*, didukung HTML5, Tailwind CSS, dan JavaScript murni (Vanilla ES6+) pada sisi *frontend*, serta MySQL sebagai basis data relasional. Mengingat cakupan sistem yang sangat luas dan mengikuti arahan Ketua Jurusan (Ibu Putri), implementasi coding pada laporan ini difokuskan pada fitur-fitur inti dan fitur khusus (*core specialized features*) yang merepresentasikan dua pilar utama aplikasi, yaitu:
1. **Modul Company Profile**: Menampilkan beranda dinamis, profil kafe, fasilitas, testimoni kurasi ulasan, dan katalog menu interaktif beserta varian rasanya.
2. **Modul Transaksi**: Meliputi transaksi mandiri pelanggan (*E-Order 3 Mode Transaksi* dengan proteksi stok `lockForUpdate`), pemrosesan dan pelunasan transaksi slip QR di meja kasir (*Point of Sale*), pengiriman pesanan kurir logistik Zendo dengan algoritma keamanan *Anti-Fake GPS*, serta monitoring penjualan melalui dasbor analitik dan generator laporan biner PDF 1.4 Native.

Berikut adalah rincian implementasi coding pada fitur-fitur utama tersebut:

---

### 4.2.1 Implementasi Coding Beranda dan Katalog Menu Interaktif (Company Profile)
Halaman beranda profil kafe (`/`) merupakan antarmuka publik pertama yang diakses oleh calon pembeli. Halaman ini bertugas menyajikan identitas *branding* Nucomu Cafe, banner promosi berjadwal yang sedang aktif hari ini, profil cerita kafe (*Our Story*), fasilitas kafe, ulasan testimoni kurasi pelanggan, serta katalog menu lengkap dengan filter kategori dan modal varian menu (suhu panas/dingin, level manis, dan topping).

Pada sisi *backend*, `NucomuController@index` memuat data kategori aktif beserta relasi menunya yang berstatus tersedia (`available()`), mengurutkannya berdasarkan produk unggulan (*featured*), dan menggabungkannya dengan pengaturan dinamis dari tabel `settings` dan `banners`.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Http\Request;

class NucomuController extends Controller
{
    /**
     * Menampilkan halaman beranda profil kafe dan katalog menu lengkap.
     * Lokasi File: app/Http/Controllers/NucomuController.php (baris 36-240)
     */
    public function index()
    {
        // 1. Ambil data banner promosi berjadwal yang aktif hari ini
        $activeBanners = Banner::activeNow()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        // 2. Ambil master kategori yang aktif beserta relasi menu yang tersedia
        $dbCategories = Category::where('is_active', true)
            ->with(['menus' => fn ($query) => $query->available()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        // 3. Ambil seluruh menu yang aktif beserta kategori dan opsi varian
        $liveMenus = Menu::available()
            ->with(['category', 'options'])
            ->where(function ($query): void {
                $query->whereNull('category_id')
                    ->orWhereHas('category', fn ($q) => $q->where('is_active', true));
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        // 4. Ambil testimoni ulasan gabungan (ulasan web terverifikasi & Google Maps)
        $dynamicReviews = $this->getCombinedReviews();

        // 5. Kirim data ke tampilan Blade
        return view('home.index', compact(
            'activeBanners',
            'dbCategories',
            'liveMenus',
            'dynamicReviews'
        ));
    }
}
```
**Gambar 4.1 Implementasi kode halaman beranda dan katalog menu profil kafe**

Pada sisi *frontend* (`resources/views/home/index.blade.php`), data menu ditampilkan dalam bentuk kartu grid interaktif. Pengunjung dapat mengeklik tombol *"Tambah"* untuk membuka modal pilihan varian, memilih level gula atau topping, dan memasukkan item ke keranjang pesanan.

---

### 4.2.2 Implementasi Coding Keranjang Belanja dan Checkout Mandiri 3 Mode (E-Order Pelanggan)
Fitur *E-Order* memungkinkan pelanggan melakukan pemesanan mandiri secara cepat tanpa harus menunggu pelayan kafe. Pelanggan menyusun item belanjaan di dalam *Cart Drawer* (disimpan sementara di *LocalStorage* peramban), lalu memilih salah satu dari 3 mode transaksi yang diaktifkan oleh pengelola:
- **Mode 1 (Dine In / Take Away)**: Menghasilkan slip QR digital dengan kode unik `NCM-Ymd-xxx` untuk ditunjukkan dan dilunasi di meja kasir.
- **Mode 2 (Chat WhatsApp Zendo)**: Mengirimkan ringkasan pesanan terformat langsung ke nomor WhatsApp operasional kurir Zendo.
- **Mode 3 (Antar Kurir Zendo)**: Layanan pesan antar dengan input koordinat GPS pelanggan, di mana ongkos kirim dihitung secara otomatis menggunakan rumus jarak lingkaran besar (*Haversine Formula*) dengan sistem tarif bertingkat (*Tiered Pricing*).

Untuk mencegah terjadinya *race condition* (stok menu terjual melebihi persediaan fisik saat dipesan bersamaan), transaksi diproteksi menggunakan transaksi basis data `DB::transaction` dan penguncian baris `lockForUpdate()`.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ZendoDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NucomuController extends Controller
{
    /**
     * Memproses transaksi pemesanan mandiri (E-Order) pelanggan.
     * Lokasi File: app/Http/Controllers/NucomuController.php (baris 1603-1760)
     */
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'order_type' => ['required', 'string', 'in:dine_in,take_away,delivery'],
            'payment_method' => ['required', 'string', 'in:cash,qris,whatsapp_zendo'],
            'items' => ['required', 'array', 'min:1'],
            'customer_lat' => ['nullable', 'numeric'],
            'customer_lng' => ['nullable', 'numeric'],
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            $orderItems = [];

            // 1. Validasi ketersediaan dan kunci baris stok menu
            foreach ($validated['items'] as $item) {
                $menu = Menu::where('id', $item['menu_id'])
                    ->where('is_available', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($menu->stock !== null && $menu->stock < $item['qty']) {
                    throw new \Exception("Stok menu {$menu->name} tidak mencukupi.");
                }

                // Kurangi stok jika menu memiliki batasan persediaan
                if ($menu->stock !== null) {
                    $menu->decrement('stock', $item['qty']);
                }

                $lineTotal = ($menu->price + ($item['variant_price'] ?? 0)) * $item['qty'];
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'price' => $menu->price,
                    'qty' => $item['qty'],
                    'line_total' => $lineTotal,
                    'options' => $item['options'] ?? null,
                ];
            }

            // 2. Hitung ongkir otomatis via Haversine jika mode pengantaran Zendo
            $deliveryFee = 0;
            if ($validated['order_type'] === 'delivery' && ! empty($validated['customer_lat'])) {
                $deliveryFee = $this->calculateZendoDeliveryFee(
                    (float) $validated['customer_lat'],
                    (float) $validated['customer_lng']
                );
            }

            // 3. Terbitkan nomor slip transaksi unik format NCM-Ymd-XXX
            $orderCode = 'NCM-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // 4. Simpan header transaksi pesanan
            $order = Order::create([
                'code' => $orderCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'order_type' => $validated['order_type'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $subtotal + $deliveryFee,
            ]);

            // 5. Simpan rincian item pesanan
            $order->items()->createMany($orderItems);

            // 6. Inisialisasi token pengantaran jika mode antar Zendo
            if ($validated['order_type'] === 'delivery') {
                ZendoDelivery::create([
                    'order_id' => $order->id,
                    'tracking_token' => Str::random(32),
                    'courier_token' => Str::random(32),
                    'status' => 'assigned',
                ]);
            }

            return response()->json([
                'success' => true,
                'order_code' => $order->code,
                'redirect_url' => route('order.slip', $order->code),
            ]);
        });
    }
}
```
**Gambar 4.2 Implementasi kode transaksi checkout e-order dan perhitungan tarif pengantaran**

---

### 4.2.3 Implementasi Coding Pemindaian Slip QR dan Pelunasan Kasir POS (Transaksi Kasir)
Kasir kafe mengoperasikan antarmuka *Point of Sale* (POS) pada rute `/kasir`. Saat pelanggan datang ke meja kasir dan memperlihatkan slip pesanan di layar ponselnya, kasir dapat memindai kode QR menggunakan kamera perangkat (*HTML5 QR Scanner*) atau mengetikkan kode pesanan pada kolom pencarian cepat.

Sistem mengeksekusi metode `lookupOrder` untuk menarik rincian pesanan. Selanjutnya, kasir memproses pembayaran dengan memasukkan nominal uang fisik yang diserahkan pelanggan (dilengkapi kalkulator hitung uang kembalian otomatis) atau mengonfirmasi pembayaran non-tunai (QRIS), lalu memperbarui status transaksi menjadi lunas (`paid`) dan mencetak struk termal 80 mm.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    /**
     * Memindai atau mencari pesanan pelanggan berdasarkan kode slip NCM-xxx.
     * Lokasi File: app/Http/Controllers/KasirController.php (baris 866-912)
     */
    public function lookupOrder(Request $request): JsonResponse
    {
        $rawCode = trim((string) $request->input('code', ''));
        $cleanCode = $this->extractOrderCode($rawCode);

        if ($cleanCode === '') {
            return response()->json(['success' => false, 'message' => 'Kode pesanan wajib diisi.'], 422);
        }

        $order = Order::where('code', $cleanCode)
            ->with(['items', 'table', 'zendoDelivery'])
            ->first();

        if (! $order) {
            return response()->json(['success' => false, 'message' => "Pesanan {$cleanCode} tidak ditemukan."], 404);
        }

        return response()->json([
            'success' => true,
            'order' => $this->formatOrderForKasir($order),
            'receipt_url' => route('kasir.orders.receipt', $order->id),
        ]);
    }

    /**
     * Memperbarui status pembayaran kasir dan menghitung selisih uang kembalian.
     * Lokasi File: app/Http/Controllers/KasirController.php (baris 1088-1145)
     */
    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,ready,completed,cancelled'],
            'payment_status' => ['nullable', 'string', 'in:unpaid,paid'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer'],
            'cash_received' => ['nullable', 'integer', 'min:0'],
        ]);

        $payableAmount = (int) $order->total;
        $cashReceived = isset($validated['cash_received']) ? (int) $validated['cash_received'] : null;

        // Validasi uang tunai yang diserahkan tidak boleh kurang dari total tagihan
        if ($validated['payment_method'] === 'cash' && $cashReceived !== null && $cashReceived < $payableAmount) {
            return response()->json(['success' => false, 'message' => 'Uang tunai kurang dari total tagihan.'], 422);
        }

        $changeAmount = ($validated['payment_method'] === 'cash' && $cashReceived !== null)
            ? max(0, $cashReceived - $payableAmount)
            : 0;

        // Perbarui data transaksi di tabel orders
        $order->update([
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'] ?? $order->payment_status,
            'payment_method' => $validated['payment_method'] ?? $order->payment_method,
            'paid_at' => ($validated['payment_status'] === 'paid') ? Carbon::now() : $order->paid_at,
            'form_data' => array_merge($order->form_data ?? [], [
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'change_amount' => $changeAmount,
        ]);
    }
}
```
**Gambar 4.3 Implementasi kode pemindaian slip QR dan pelunasan transaksi kasir POS**

---

### 4.2.4 Implementasi Coding Pelacakan Kurir Zendo dan Validasi Anti-Fake GPS (Logistik Transaksi)
Pada transaksi pesan antar (Mode 3), kurir Zendo menerima tautan khusus ber-token (`/zendo/lacak/{token}`). Untuk melindungi privasi dan integritas data pengantaran, sistem menerapkan dua tingkat pengamanan:
1. **Otentikasi PIN dan Pengikatan Perangkat (*Device Binding*)**: Kurir wajib memasukkan PIN 4–6 digit pada saat pertama kali membuka halaman. Setelah valid, sistem menerbitkan token pengikatan perangkat biner SHA-256 yang disimpan pada cookie aman (`zendo_courier_device`), sehingga kurir tidak perlu memasukkan PIN berulang kali selama bertugas.
2. **Proteksi Anti-Fake GPS**: Saat perangkat kurir melakukan *streaming* posisi koordinat bujur dan lintang secara berkala ke server, sistem mengevaluasi parameter fisika pergerakan. Jika akurasi sinyal satelit terlalu rendah (> 150 meter) atau kecepatan berpindah tempat tidak wajar (> 45 meter/detik atau setara 162 km/jam), sistem mencatat status *suspicious strike*. Jika terindikasi manipulasi *Mock Location*, posisi kurir dikunci sementara (*locked*) untuk mencegah kecurangan titik antar.

```php
<?php

namespace App\Http\Controllers;

use App\Models\ZendoDelivery;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ZendoTrackingController extends Controller
{
    /**
     * Memverifikasi PIN rahasia kurir dan mengikat perangkat fisik (Device Binding).
     * Lokasi File: app/Http/Controllers/ZendoTrackingController.php (baris 72-138)
     */
    public function verifyPin(Request $request, string $trackingToken): JsonResponse
    {
        $delivery = ZendoDelivery::where('tracking_token', $trackingToken)
            ->orWhere('courier_token', $trackingToken)
            ->firstOrFail();

        $validated = $request->validate([
            'pin' => ['required', 'string', 'max:50'],
        ]);

        $configuredPin = (string) config('nucomu.contact.zendo_tracking.courier_pin', '1234');

        if (! hash_equals($configuredPin, (string) $validated['pin'])) {
            return response()->json(['success' => false, 'message' => 'PIN kurir salah. Silakan coba lagi.'], 422);
        }

        // Ikat perangkat kurir menggunakan token acak terenkripsi
        $deviceToken = bin2hex(random_bytes(24));
        $this->bindCourierDevice($delivery);

        return response()->json([
            'success' => true,
            'message' => 'Mode Kurir berhasil diaktifkan pada perangkat ini.',
            'device_token' => $deviceToken,
        ]);
    }

    /**
     * Menerima streaming koordinat GPS kurir dengan filter proteksi Anti-Fake GPS.
     * Lokasi File: app/Http/Controllers/ZendoTrackingController.php (baris 303-380)
     */
    public function updateLocation(Request $request, string $courierToken): JsonResponse
    {
        $delivery = ZendoDelivery::where('courier_token', $courierToken)->firstOrFail();

        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['required', 'numeric', 'min:0'],
            'speed' => ['nullable', 'numeric'],
            'timestamp' => ['required', 'numeric'],
        ]);

        // Cek evaluasi fisik Anti-Fake GPS (Akurasi satelit dan batas kecepatan wajar)
        $evaluation = $this->evaluateGpsReading($delivery, $validated);

        if ($evaluation['verdict'] === 'suspicious') {
            $strikesKey = 'zendo_gps_strikes_' . $delivery->id;
            $strikes = (int) Cache::get($strikesKey, 0) + 1;

            if ($strikes >= 3) {
                // Kunci pengiriman sementara selama 5 menit jika terdeteksi manipulasi lokasi
                $delivery->update(['gps_locked_until' => now()->addMinutes(5)]);
            } else {
                Cache::put($strikesKey, $strikes, now()->addMinutes(10));
            }

            return response()->json([
                'accepted' => false,
                'message' => 'Lokasi ditolak: ' . $evaluation['reason'],
            ], 422);
        }

        // Simpan titik lokasi kurir dan tambahkan ke riwayat jejak rute
        $trail = $delivery->route_trail ?? [];
        $trail[] = [round((float) $validated['lat'], 7), round((float) $validated['lng'], 7), now()->timestamp];

        $delivery->update([
            'courier_lat' => $validated['lat'],
            'courier_lng' => $validated['lng'],
            'route_trail' => $trail,
            'last_location_at' => Carbon::now(),
        ]);

        return response()->json(['accepted' => true, 'message' => 'Koordinat kurir berhasil diperbarui.']);
    }
}
```
**Gambar 4.4 Implementasi kode otentikasi kurir dan streaming GPS dengan proteksi Anti-Fake GPS**

---

### 4.2.5 Implementasi Coding Dasbor Keuangan dan Generator Laporan PDF Native (Monitoring Penjualan)
Untuk mempermudah pemilik usaha dan staf manajemen memantau performa penjualan harian, sistem menyediakan Dasbor Analitik Penjualan pada panel Admin (`/admin/dashboard`). Dasbor ini mengakumulasi 4 indikator metrik utama: total pendapatan hari ini, jumlah pesanan hari ini, pesanan berstatus *pending*, dan total menu aktif, serta menampilkan grafik tren penjualan harian (7 hari), mingguan (4 minggu), dan bulanan (12 bulan) yang membedakan transaksi Tunai (*Cash*) dan Non-Tunai (*QRIS*).

Selain pemantauan visual di layar, sistem dilengkapi generator laporan rekapitulasi penjualan berformat dokumen biner PDF 1.4 Native (`TransactionReportPdfGenerator`). Generator ini dirancang secara khusus untuk membangun aliran biner PDF (*raw byte stream*) secara mandiri langsung dari kode PHP murni tanpa memerlukan dependensi pihak ketiga yang berat (seperti *Dompdf* atau *wkhtmltopdf*).

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Membangun dataset grafik penjualan harian, mingguan, dan bulanan.
     * Lokasi File: app/Http/Controllers/Admin/DashboardController.php (baris 74-120)
     */
    protected function buildFinancialSalesChartData(Carbon $today): array
    {
        $earliestDate = $today->copy()->subMonths(11)->startOfMonth();

        // Ambil seluruh data transaksi non-batal dalam rentang waktu analisis
        $orders = Order::query()
            ->select(['id', 'total', 'subtotal', 'delivery_fee', 'status', 'payment_status', 'payment_method', 'created_at'])
            ->where('created_at', '>=', $earliestDate)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at')
            ->get();

        $dailyLabels = [];
        $dailyCash = [];
        $dailyQris = [];

        // Agregasi tren 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMM');

            $dayOrders = $orders->filter(fn ($o) => substr($o->created_at, 0, 10) === $date);
            $dailyCash[] = (int) $dayOrders->where('payment_method', 'cash')->sum('total');
            $dailyQris[] = (int) $dayOrders->whereIn('payment_method', ['qris', 'transfer'])->sum('total');
        }

        return [
            'daily' => [
                'labels' => $dailyLabels,
                'cash' => $dailyCash,
                'qris' => $dailyQris,
            ],
        ];
    }
}
```
**Gambar 4.5 Implementasi kode dasbor analitik penjualan dan agregasi omzet harian**

Dokumen laporan PDF hasil kompilasi menyajikan kop resmi kafe, 4 kotak ringkasan metrik keuangan, rincian daftar transaksi pelanggan lengkap dengan waktu dan metode bayar, serta tanda tangan pengesahan pengelola kafe.
