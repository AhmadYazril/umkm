# TAHAP 7: ALGORITMA DAN LOGIKA BISNIS SISTEM
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Metode Analisis:** Pembacaan Kode Aktual (Fakta Empiris dengan Bukti File dan Baris Kode)

---

## 1. Algoritma Pembuatan Kode Pesanan Unik NCM (Order Code Generation)
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/NucomuController.php` (Baris 1849-1851)
- `app/Http/Controllers/KasirController.php` (Baris 558-564)

### Langkah-Langkah Logika & Algoritma:
Sistem membaca tanggal kalender hari ini menggunakan `Carbon::now()->format('Ymd')` (menghasilkan 8 digit tanggal, misalnya `20261102`).
Sistem menghitung jumlah pesanan yang sudah tercatat pada hari ini di basis data menggunakan query `Order::whereDate('created_at', today())->count() + 1`.
Urutan nomor pesanan hari ini diubah menjadi teks dan dipadatkan menjadi minimal 3 digit menggunakan fungsi PHP `str_pad($count, 3, '0', STR_PAD_LEFT)` (misal urutan 1 menjadi `001`, urutan 25 menjadi `025`).
Sistem menggabungkan prefiks `NCM-`, tanggal `Ymd`, dan nomor urut: `$code = 'NCM-'.$date.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);`.
Kode unik yang dihasilkan (contoh: `NCM-20261102-001`) disimpan ke kolom `orders.code` yang memiliki constraint `UNIQUE` di basis data.

> **Analisis Pengesahan:** Format terstandar ini memudahkan pencarian kasir, pemindaian slip QR, dan penomoran pada struk termal.

---

## 2. Pengurangan Stok dan Logika Penguncian (Concurrency & Stock Deduction)
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/NucomuController.php` (Baris 1848, 1893-1897)
- `app/Http/Controllers/KasirController.php` (Baris 605-654, fungsi `deductStockForOrderItems`)

### Langkah-Langkah Logika & Algoritma:
Proses inisiasi pesanan dibungkus di dalam transaksi basis data `DB::transaction(...)` pada `NucomuController.php` baris 1848.
**Fakta Kode Mengenai `lockForUpdate`:** Perintah baris `lockForUpdate()` **TIDAK DITEMUKAN** di dalam berkas kontroler maupun model proyek ini.
Setelah baris order dan order_items tersimpan, sistem memanggil fungsi statis `KasirController::deductStockForOrderItems((array) $request->items)` di baris 1894.
Di dalam `deductStockForOrderItems`, sistem mengambil peta stok tersimpan dari konfigurasi `settings` via `getStockMap()`.
Untuk setiap item pesanan, model menu dicari berdasarkan `id`, `slug`, atau nama menu.
Sisa stok dihitung secara matematis dengan rumus `$newStock = max(0, $currentStock - $qty);`.
Jika sisa stok mencapai 0, sistem otomatis mengubah penanda ketersediaan menjadi tidak tersedia (`$isAvailable = false`).
Sistem menjalankan `$menuModel->update(['stock' => $newStock, 'is_available' => $isAvailable])` dan memperbarui peta cadangan stok di tabel `settings`.

> **Analisis Pengesahan:** Pengurangan stok dibungkus blok `try-catch` sehingga jika terjadi kegagalan pemotongan stok fisik, proses pencatatan pesanan pelanggan tetap dapat diselesaikan dengan anggun (*gracefully*).

---

## 3. Penghitungan Subtotal dan Penanganan Harga Varian (Client vs Server)
**Bukti Berkas & Baris Kode:**
- Sisi Server: `app/Http/Controllers/NucomuController.php` (Baris 1721-1743)
- Sisi Klien: `resources/views/components/cart-drawer.blade.php` (Baris 1100-1180)

### Langkah-Langkah Logika & Algoritma:
**Di Sisi Klien (JavaScript Cart Drawer):** Pelanggan memilih varian ukuran atau topping. Skrip JavaScript menjumlahkan harga pokok menu dengan biaya tambahan varian (`option.price`) dan menampilkan subtotal di layar ponsel.
Saat checkout dikirim ke server (`POST /pesan`), payload membawa array `items` beserta objek `options` dan harga baris.
**Di Sisi Server (PHP NucomuController):** Server memeriksa apakah menu terdaftar di database via `Menu::find($id)`.
Jika menu ditemukan di database, server menetapkan harga satuan secara mutlak dari harga pokok menu di database: `$unitPrice = (int) $menu->price;` (Baris 1721).
Server menghitung baris total: `$lineTotal = $unitPrice * $qty;` (Baris 1730) dan subtotal: `$calcSubtotal = (int) collect($normalizedItems)->sum('line_total');` (Baris 1742).
**Temuan Kritis:** Server **TIDAK menjumlahkan ulang** selisih harga varian (`MenuOption.price`) ke dalam `$unitPrice`. Nilai varian hanya disimpan sebagai catatan JSON teks di kolom `order_items.options`.

> **Analisis Pengesahan:** Perhitungan selisih harga varian hanya hidup di sisi klien; harga pokok yang dijamin konsistensinya oleh server adalah harga dasar tabel `menus`.

---

## 4. Kalkulasi Ongkos Kirim Bertingkat Kurir Zendo (Tiered Delivery Fee)
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/NucomuController.php` (Baris 1640-1680, fungsi `calculateZendoDeliveryFee`)
- `config/nucomu.php` dan tabel `settings` (kunci `zendo_tariff`)

### Langkah-Langkah Logika & Algoritma:
Jika pelanggan memilih metode pengantaran Kurir Zendo dan menyematkan titik peta GPS, server menghitung jarak garis lurus kafe-pelanggan menggunakan formula Haversine (`calculateDistanceKm`).
Server membandingkan jarak rute jalan yang dikirim peramban (`distance_km`). Jika wajar (antara jarak lurus s.d. 2.5x jarak lurus + 2 km), jarak tersebut digunakan; jika tidak wajar/nol, server menggunakan jarak garis lurus murni.
Nilai jarak dibulatkan ke satu desimal: `$d = max(0.0, round($distanceKm, 1));`.
Server mengevaluasi aturan tarif bertingkat (*Tiered Pricing*):
  - **Zona 1 (Jarak 0.0 s.d. 3.0 km):** Tarif tetap Rp 10.000 (`tier_0_3`).
  - **Zona 2 (Jarak 3.1 s.d. 4.0 km):** Tarif tetap Rp 12.000 (`tier_3_4`).
  - **Zona 3 (Jarak 4.1 s.d. 5.0 km):** Tarif tetap Rp 15.000 (`tier_4_5`).
  - **Zona 4 (Jarak 5.1 s.d. 6.0 km):** Tarif tetap Rp 17.000 (`tier_5_6`).
  - **Zona 5 (Jarak 6.1 s.d. 7.0 km):** Tarif tetap Rp 18.000 (`tier_6_7`).
  - **Zona 6 (Jarak > 7.0 km):** Tarif dasar Rp 18.000 ditambah kelebihan jarak per kilometer: `$extraKm = (int) ceil($d - 7.0); return $tier6_7 + ($extraKm * $extraPerKm);` dengan biaya tambahan Rp 2.500 per km.
Jika pesanan mode chat tanpa pin koordinat peta, berlaku tarif flat zona terdekat Rp 10.000.

> **Analisis Pengesahan:** Logika ini mencegah manipulasi jarak sepihak oleh klien dan menjamin kepastian ongkos antar kurir lokal Tulungagung.

---

## 5. Rekonsiliasi Kas Saat Tutup Shift Kasir
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/KasirController.php`
- `database/migrations/`

### Langkah-Langkah Logika & Algoritma:
Pemeriksaan menyeluruh terhadap basis data dan kode kontroler menunjukkan status: **TIDAK DITEMUKAN**.
Di dalam kode tidak ditemukan tabel shift, fungsi `closeShift`, input modal awal kasir (*cash float*), maupun penghitungan selisih kas fisik (*cash discrepancy calculation*).
Aktivitas kasir saat ini sepenuhnya berorientasi transaksi per transaksi (*stateless transactions*): login, scan slip QR, terima pembayaran, dan cetak struk.
Akumulasi penerimaan kas dihitung secara global melalui agregasi tabel `orders` berstatus `'paid'`.

> **Analisis Pengesahan:** Rekomendasi pengembangan: Dapat diusulkan penambahan tabel `shifts` (id, user_id, start_cash, end_cash, cash_difference, closed_at) pada rilis berikutnya.

---

## 6. Anti-Fake GPS: Ambang Batas, Kecepatan & Satuan (GPS Lock Mechanism)
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/ZendoTrackingController.php` (Baris 300-360, 490-545)
- `config/nucomu.php` (Array `zendo_tracking`)

### Langkah-Langkah Logika & Algoritma:
Perangkat smartphone kurir mengirimkan koordinat via endpoint `POST /zendo/lacak/{token}/lokasi` dengan membawa parameter: `latitude`, `longitude`, `accuracy`, dan opsional `speed`.
**Satuan Kecepatan:** Sensor Geolocation API peramban mengembalikan parameter `speed` dalam satuan **meter per detik (m/s)**. Pada baris 575, kontroler mengonversinya ke km/jam untuk tampilan manusia dengan rumus: `$speed_kmh = round($speed * 3.6, 1);`.
**Evaluasi Ambang Batas Kecepatan:** Kontroler memeriksa baris 512: `if (isset($reading['speed']) && (float) $reading['speed'] > (float) ($config['max_speed_mps'] ?? 45))`.
  - Ambang batas kecepatan maksimal di kode adalah **45 m/s** (setara dengan **162 km/jam**). Kecepatan di atas angka ini dianggap tidak wajar untuk sepeda motor kurir kafe dan dinyatakan curiga (`suspicious`).
**Evaluasi Ambang Batas Akurasi:**
  - Jika `accuracy <= 0 m`: Dinyatakan `suspicious` (indikasi kuat aplikasi Mock Location yang menyuntikkan akurasi 0 sempurna).
  - Jika `accuracy > 150 m` (`max_accuracy_m`): Dinyatakan sinyal lemah (`weak`).
**Deteksi Teleportasi / Lompatan Lokasi:** Jika jarak perpindahan kurir > 300 meter dan kecepatan perpindahan (`jump / elapsed`) > 45 m/s, sistem menandai sebagai `suspicious` ("Lokasi melompat dalam X detik — indikasi teleport / Fake GPS").
**Mekanisme GPS Lock:** Setiap kali terdeteksi `suspicious`, kolom `gps_suspicious_count` bertambah 1. Jika mencapai ambang batas 3 kali (`suspicious_lock_threshold = 3`), sesi kurir dikunci otomatis selama 5 menit (`lock_minutes = 5`). Selama terkunci, kurir tidak dapat mengubah status pesanan.

> **Analisis Pengesahan:** Klaim laporan sebelumnya yang menyebut ambang kecepatan '100' perlu diluruskan: jika angka 100 diartikan m/s nilainya setara 360 km/jam. Nilai konfigurasi resmi di kode saat ini adalah `max_speed_mps = 45` (162 km/jam).

---

## 7. Generator PDF 1.4 Native (TransactionReportPdfGenerator)
**Bukti Berkas & Baris Kode:**
- `app/Support/TransactionReportPdfGenerator.php` (Lengkap, 335 baris kode)
- `app/Http/Controllers/Admin/OrderController.php` (Baris 240-275)

### Langkah-Langkah Logika & Algoritma:
Generator tidak menggunakan pustaka pihak ketiga eksternal seperti Dompdf atau Snappy/Wkhtmltopdf, melainkan menyusun dokumen biner PDF 1.4 murni secara native melalui kode PHP.
Sistem menginisialisasi ukuran halaman standar A4 vertikal: `$pageWidth = 595.28; $pageHeight = 841.89;` (satuan PostScript points).
Header dokumen PDF disusun dengan banner berwarna gelap (`#1C1E1C` -> RGB `[0.11, 0.12, 0.11]`) menggunakan instruksi operator PDF `re` (rectangle) dan `f` (fill).
Teks judul, tanggal cetak, dan metadata dirender menggunakan operator teks PDF `BT`, `Tf` (font Helvetica/F1 dan Helvetica-Bold/F2), `Tm` (text matrix posisi koordinat), dan `Tj` (tampilkan string teks yang di-escape).
Generator merakit 4 kartu metrik keuangan (Total Transaksi, Omzet Lunas, Rata-rata Nilai Pesanan, dan Transaksi Non-Tunai) secara berdampingan.
Tabel rincian pesanan dirender baris demi baris dengan perhitungan posisi `$y`. Jika sisa tinggi halaman di bawah 52 poin, fungsi closure `$startNewPage()` otomatis dipanggil untuk membuat halaman baru.
Setelah seluruh halaman selesai, generator merakit struktur biner penutup berkas PDF:
  - Objek Katalog (`/Type /Catalog`), Objek Outlines, Objek Pages (`/Type /Pages`), dan objek individual Page.
  - Tabel referensi silang offset biner (`xref`) bernomor urut ketat.
  - Trailer dictionary yang menunjuk objek root dan penanda akhir berkas `%%EOF`.
String biner dikembalikan dan dialirkan langsung sebagai respons HTTP ber-header `Content-Type: application/pdf`.

> **Analisis Pengesahan:** Pendekatan native ini menghasilkan file PDF berukuran sangat ringkas (di bawah 50 KB), waktu rendering instan (< 100 ms), dan bebas dari ketergantungan ekstensi server eksternal.

---

## 8. Mekanisme Cache dan Kurasi Ulasan Google Places
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/NucomuController.php` (Baris 268-350, fungsi `getCombinedReviews`)
- `config/nucomu.php` (Array `integrations` dan `reviews`)

### Langkah-Langkah Logika & Algoritma:
Sistem membaca kunci `GOOGLE_PLACES_API_KEY` dan `GOOGLE_PLACE_ID` dari file `.env` / konfigurasi.
Jika kunci tersedia, sistem memanggil cache Laravel: `Cache::remember('nucomu_google_places_reviews', now()->addHours(6), function () { ... })`.
**Durasi Cache:** Durasi penyimpanan sementara data ulasan Google Places di cache server adalah **TEPAT 6 JAM** (`now()->addHours(6)`). Ini menghemat kuota penagihan API Google Cloud.
HTTP Client Laravel mengeksekusi request GET ke Google Place Details API dengan timeout 4 detik.
**Filter Bintang:** Berdasarkan kode aktual, **TIDAK ADA filter bintang** (tidak ada klausul `where('rating', 5)`). Seluruh ulasan yang dikembalikan oleh API dimuat apa adanya: `'rating' => (int) ($item['rating'] ?? 5)`.
Ulasan dari Google digabungkan dengan ulasan cadangan lokal di `config('nucomu.reviews')` dan ulasan mandiri dari database (`testimonials`) yang aktif.
Seluruh ulasan disatukan dalam satu koleksi terpadu untuk ditampilkan pada slider testimoni beranda.

> **Analisis Pengesahan:** Klaim laporan bahwa sistem 'hanya mengambil ulasan bintang lima dari Google' TIDAK SESUAI dengan kode. API memuat seluruh ulasan yang dikembalikan Google (default 5 ulasan teratas dari Place Details).

---

## 9. Logika Banner Auto-Expiry dan Peringatan Menjelang Berakhir (30 Menit)
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/Admin/BannerController.php` (Baris 31-55, 355-385)
- `app/Models/Banner.php` (Scope `expiringSoon` dan accessor `is_expiring_soon`)

### Langkah-Langkah Logika & Algoritma:
Tabel `banners` memiliki kolom penanda waktu: `start_time` (mulai tayang) dan `end_time` (akhir tayang).
**Penayangan Publik Beranda:** Model memfilter banner aktif dengan kriteria: `is_active = 1` DAN (`start_time IS NULL OR start_time <= now()`) DAN (`end_time IS NULL OR end_time >= now()`). Banner yang melewati `end_time` otomatis hilang dari beranda tanpa perlu dihapus manual (*Auto-Expiry*).
**Deteksi Peringatan 30 Menit:** Pada kontroler admin, metode `checkExpiring()` dan scope `expiringSoon()` memfilter banner dengan query:
  `where('end_time', '>', Carbon::now())->where('end_time', '<=', Carbon::now()->addMinutes(30))`.
**Fakta Ambang Waktu:** Nilai ambang batas deteksi banner menjelang habis adalah **BENAR TEPAT 30 MENIT** (`addMinutes(30)`).
Antarmuka admin melakukan polling berkala ke `/admin/banner/expiring-check`. Jika ditemukan banner dalam rentang 30 menit tersebut, modal notifikasi melayang muncul di dasbor admin.
Admin disediakan tombol pintas 'Perpanjang 30 Menit' (`/admin/banner/{id}/perpanjang`) yang mengeksekusi `$banner->update(['end_time' => $baseTime->addMinutes(30)])`.

> **Analisis Pengesahan:** Fitur ini mencegah banner promosi diskon penting kafe tiba-tiba nonaktif tanpa disadari oleh staf pengelola.

---

## 10. Agregasi Metrik Finansial Dasbor (Harian, Mingguan, Bulanan)
**Bukti Berkas & Baris Kode:**
- `app/Http/Controllers/Admin/DashboardController.php` (Baris 74-220, fungsi `buildFinancialSalesChartData`)

### Langkah-Langkah Logika & Algoritma:
Admin membuka dasbor. Jika pengaturan grafik aktif (`show_dashboard_chart = true`), server mengeksekusi metode agregasi data keuangan.
Server menarik seluruh data pesanan non-batal dalam 12 bulan terakhir: `Order::where('created_at', '>=', $earliestDate)->where('status', '!=', 'cancelled')->get()`.
Server menginisialisasi 3 wadah penampung data (*Data Buckets*):
  - **Wadah Harian (7 Hari Terakhir):** Melakukan iterasi 7 hari ke belakang dari hari ini (`$today->subDays($i)`). Kunci wadah adalah format `Y-m-d`.
  - **Wadah Mingguan (4 Minggu Terakhir):** Melakukan iterasi 4 minggu ke belakang (`$today->subWeeks($w)`), dengan rentang hari Senin (*startOfWeek*) hingga Minggu (*endOfWeek*).
  - **Wadah Bulanan (12 Bulan Terakhir):** Melakukan iterasi 12 bulan ke belakang (`$today->subMonths($m)`). Kunci wadah adalah format `Y-m`.
Data pesanan di-loop satu per satu untuk mengisi wadah:
  - Jika pesanan berstatus `'paid'`, nilai tagihan diakumulasikan ke variabel `revenue` (omzet riil).
  - Seluruh pesanan non-batal diakumulasikan ke variabel `gross_sales` (penjualan kotor) dan `orders_count`.
Hasil kompilasi dikonversi menjadi array titik koordinat grafik (`labels`, `series_revenue`, `series_orders`) yang siap dirender oleh pustaka Chart.js di antarmuka admin.

> **Analisis Pengesahan:** Agregasi dilakukan dalam memori server dari satu query tunggal untuk meminimalkan beban I/O basis data.

---
