# TAHAP 10: VERIFIKASI KLAIM LAPORAN (FAKTA VS KODE)
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Metode Pengujian:** Verifikasi Empiris Berdasarkan Kode Sumber Sebenarnya

---

## Klaim 1: Sistem tidak memakai Alpine.js (hanya Vanilla JavaScript).
- **Status Verifikasi:** **`SESUAI`**
- **Bukti Kode Empiris:**
- Berkas `package.json` tidak mencantumkan dependensi `alpinejs` baik di `dependencies` maupun `devDependencies`.
- Berkas skrip interaktif seperti `resources/views/components/cart-drawer.blade.php`, `resources/views/components/menu-modal.blade.php`, dan `public/js/` sepenuhnya menggunakan sintaks **Vanilla JavaScript DOM API** standar (`document.getElementById`, `addEventListener`, `fetch`, `localStorage`).
- **Kesimpulan Analisis:** Klaim laporan benar. Tidak ada framework reaktif JavaScript yang terpasang.

---

## Klaim 2: Versi: PHP 8.5, Laravel 11, Tailwind CSS v4, Vite 8.0, Leaflet.js + OpenStreetMap.
- **Status Verifikasi:** **`TIDAK SESUAI (Sebagian)`**
- **Bukti Kode Empiris:**
Rincian verifikasi per pustaka:
- **PHP 8.5:** **SESUAI** (Runtime CLI aktif adalah `PHP 8.5.9 x64`, konfigurasi `composer.json` mensyaratkan `"php": "^8.3"`).
- **Laravel 11:** **TIDAK SESUAI**. Berkas terkunci `composer.lock` mencatat `"name": "laravel/framework", "version": "v13.35.0"` dengan batasan `composer.json` `"laravel/framework": "^13.17"`. Proyek ini berjalan di Laravel generasi 13, bukan Laravel 11.
- **Tailwind CSS v4:** **SESUAI** (`package.json` mencatat `"tailwindcss": "^4.0.0"` dan `"@tailwindcss/vite": "^4.0.0"`).
- **Vite 8.0:** **SESUAI** (`package.json` mencatat `"vite": "^8.0.0"`).
- **Leaflet.js + OpenStreetMap:** **SESUAI** (Berkas fisik lokal `public/vendor/leaflet/leaflet.js` versi 1.9.4 dengan CDN fallback `https://unpkg.com/leaflet@1.9.4` dan tile layer OpenStreetMap).
- **Kesimpulan Analisis:** Laporan resmi harus mengoreksi klaim versi Laravel dari Laravel 11 menjadi Laravel 13.

---

## Klaim 3: Basis data memiliki 17 tabel fisik.
- **Status Verifikasi:** **`SESUAI BERSYARAT (Perlu Penjelasan Konteks)`**
- **Bukti Kode Empiris:**
- Tepat terdapat **17 tabel entitas aplikasi** yang memiliki representasi Model Eloquent 1:1 di direktori `app/Models/` (`users`, `categories`, `menus`, `menu_options`, `tables`, `orders`, `order_items`, `reservations`, `reservation_fields`, `settings`, `operating_hours`, `facilities`, `gallery_items`, `testimonials`, `events`, `zendo_deliveries`, `banners`).
- Namun, secara fisik di dalam migrasi Laravel (`database/migrations/`), terdapat total **24 tabel** yang diciptakan oleh `Schema::create` karena framework menambahkan 7 tabel penunjang infrastruktur (`password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`).
- **Kesimpulan Analisis:** Klaim '17 tabel fisik' pada laporan merujuk kepada 17 tabel model domain bisnis kafe, bukan seluruh tabel skema MySQL.

---

## Klaim 4: Cookie autentikasi `nucomu_admin_auth_v1` dan `nucomu_kasir_auth_v2` dengan HMAC SHA-256.
- **Status Verifikasi:** **`SESUAI`**
- **Bukti Kode Empiris:**
- Admin: Di `app/Http/Controllers/Admin/AuthController.php` (L17), didefinisikan konstanta `const ADMIN_COOKIE = 'nucomu_admin_auth_v1'` dengan generator token `hash_hmac('sha256', 'nucomu_admin|'.$user->id.'|'.$user->email, config('app.key'))` dan verifikasi `hash_equals()`.
- Kasir: Di `app/Http/Controllers/KasirController.php` (L38), didefinisikan konstanta `const KASIR_COOKIE = 'nucomu_kasir_auth_v2'` dengan generator token `hash_hmac('sha256', 'nucomu_kasir|'.$user->id.'|'.$user->email, config('app.key'))` dan masa aktif 365 hari.
- **Kesimpulan Analisis:** Klaim laporan terverifikasi akurat dan sesuai dengan implementasi kode.

---

## Klaim 5: Harga varian: apakah server menghitung selisih harga varian saat checkout, atau hanya klien?
- **Status Verifikasi:** **`HANYA KLIEN (Server Mengabaikan Selisih Varian)`**
- **Bukti Kode Empiris:**
- Di sisi server (`app/Http/Controllers/NucomuController.php` baris 1721-1730), harga satuan item pesanan ditetapkan secara mutlak dari harga dasar menu: `$unitPrice = (int) $menu->price;` dan subtotal dihitung `$lineTotal = $unitPrice * $qty;`.
- Objek `options` hanya disimpan sebagai metadata JSON teks di kolom `order_items.options` tanpa pernah menjumlahkan `MenuOption.price` ke dalam tagihan server.
- Perhitungan selisih biaya varian hanya terjadi di peramban klien pada skrip `resources/views/components/cart-drawer.blade.php`.
- **Kesimpulan Analisis:** Klaim bahwa server menghitung harga varian adalah keliru; faktanya server hanya mengunci harga pokok dari tabel `menus`.

---

## Klaim 6: Satuan kecepatan pada Anti-Fake GPS (m/s atau km/jam) dan apakah ambang 100 benar sesuai satuannya.
- **Status Verifikasi:** **`TIDAK SESUAI (Ambang Batas Resmi Adalah 45 m/s)`**
- **Bukti Kode Empiris:**
- Sensor Geolocation API peramban mengembalikan parameter `speed` dalam satuan **meter per detik (m/s)**.
- Pada berkas konfigurasi resmi `config/nucomu.php`, kunci pengatur kecepatan dinamai `'max_speed_mps' => 45`.
- Pada `app/Http/Controllers/ZendoTrackingController.php` baris 512, evaluasi anomali kecepatan berbunyi: `if (isset($reading['speed']) && (float) $reading['speed'] > (float) ($config['max_speed_mps'] ?? 45))`.
- Nilai 45 m/s setara dengan **162 km/jam** (batas wajar kecepatan kurir motor). Jika angka '100' digunakan dengan satuan m/s, nilainya setara 360 km/jam (tidak realistis). Kontroler mengonversi ke km/jam hanya saat merender data: `$speed * 3.6`.
- **Kesimpulan Analisis:** Klaim laporan tentang ambang '100' tidak sesuai dengan kode program yang secara tegas menggunakan nilai 45 m/s.

---

## Klaim 7: Struk: apakah ada CSS `@media print`; dan alamat pada struk (alamat resmi: Manggisan, Plosokandang, Kec. Kedungwaru, Kab. Tulungagung; di kode sebelumnya masih tertulis 'Jl. Mayor Sujadi').
- **Status Verifikasi:** **`TIDAK SESUAI (Alamat Masih Belum Resmi) & SESUAI (CSS Print Ada)`**
- **Bukti Kode Empiris:**
- CSS `@media print` **ADA** pada berkas templat struk `resources/views/admin/orders/receipt.blade.php` (baris 15-40).
- Namun mengenai alamat kafe, pada baris 51 berkas `receipt.blade.php` masih tertulis hardcoded: `<div style="font-size: 9px;">Jl. Panglima Sudirman No. 45, Kebonsari</div>` (sebelumnya Jl. Mayor Sujadi). Alamat resmi sekolah/kafe ("Manggisan, Plosokandang, Kec. Kedungwaru, Kab. Tulungagung") **BELUM TERTULIS** pada templat struk fisik.
- **Kesimpulan Analisis:** Templat struk perlu diperbarui agar mencetak alamat resmi Nucomu Cafe di Plosokandang, Kedungwaru.

---

## Klaim 8: Integrasi Midtrans: apakah ada kode sama sekali, atau hanya disiapkan?
- **Status Verifikasi:** **`TIDAK DITEMUKAN (Tidak Ada Kode Maupun Dependensi)`**
- **Bukti Kode Empiris:**
- Tidak ada pustaka `midtrans/midtrans-php` di `composer.json`.
- Tidak ada variabel `MIDTRANS_SERVER_KEY` atau `MIDTRANS_CLIENT_KEY` di `config/`.
- Tidak ada kontroler, rute webhook penanganan notifikasi pembayaran, maupun skrip Snap.js di seluruh proyek.
- Status pembayaran `payment_status` bernilai `'pending'` atau `'paid'` yang diubah secara manual oleh kasir kafe saat menerima uang tunai atau memeriksa bukti statis gambar QRIS.
- **Kesimpulan Analisis:** Klaim adanya integrasi payment gateway Midtrans adalah fiktif/tidak ditemukan di repositori.

---

## Klaim 9: Tiga mode checkout: tuliskan apa yang sebenarnya dibedakan di kode (order_type dan nilai-nilainya).
- **Status Verifikasi:** **`TERVERIFIKASI FAKTA KODE`**
- **Bukti Kode Empiris:**
Di skema basis data `orders.order_type`, tipe data kolom hanyalah ENUM: `'dine_in'` dan `'take_away'`.
Pembedaan 3 mode dilakukan melalui logika kombinasi kolom di `NucomuController.php` (baris 1857-1867):
1. **Mode 1 (Dine In / Take Away Mandiri):** `order_type = 'dine_in'` (jika ada meja) atau `'take_away'`. Catatan memuat nomor meja fisik, sistem mencetak slip QR kasir.
2. **Mode 2 (Chat WhatsApp Zendo):** `order_type = 'take_away'`, `delivery_address` terisi teks alamat, sistem merakit pesan otomatis ke URL `https://wa.me/`.
3. **Mode 3 (Antar Kurir GPS Live Tracking):** `order_type = 'take_away'`, `delivery_address` terisi, `delivery_fee` terhitung bertingkat, dan sistem menerbitkan rekaman baru di tabel `zendo_deliveries` lengkap dengan token GPS.
- **Kesimpulan Analisis:** Mode 2 dan Mode 3 sama-sama berstatus `take_away` pada kolom `order_type`, namun dibedakan oleh keberadaan rekaman di tabel `zendo_deliveries`.

---

## Klaim 10: Google Places: apakah hanya ulasan bintang lima yang diambil?
- **Status Verifikasi:** **`TIDAK SESUAI (Semua Ulasan Diambil Tanpa Filter Bintang)`**
- **Bukti Kode Empiris:**
- Pada fungsi `NucomuController::getCombinedReviews` (`app/Http/Controllers/NucomuController.php` baris 268-305), hasil respon API `result.reviews` di-loop seluruhnya ke dalam koleksi tanpa klausul filter bintang (`where('rating', 5)`).
- Nilai rating dibaca apa adanya dari API Google: `'rating' => (int) ($item['rating'] ?? 5)`.
- Hanya pada ulasan *dummy fallback* di `config/nucomu.reviews` seluruh data contoh kebetulan diberi nilai rating 5.
- **Kesimpulan Analisis:** Sistem memuat seluruh ulasan yang dikembalikan oleh API Google Places terlepas dari jumlah bintangnya.

---

## Klaim 11: Peringatan banner 30 menit sebelum berakhir.
- **Status Verifikasi:** **`SESUAI`**
- **Bukti Kode Empiris:**
- Pada `app/Http/Controllers/Admin/BannerController.php` (baris 32 & 400), filter `expiring` mendefinisikan batas waktu: `$threshold = Carbon::now()->addMinutes(30);`.
- Scope `Banner::expiringSoon()` menyaring banner aktif yang memiliki `end_time` antara waktu sekarang hingga 30 menit ke depan.
- Endpoint `GET /admin/banner/expiring-check` mengembalikan daftar banner ini untuk memicu modal melayang peringatan di dasbor admin.
- **Kesimpulan Analisis:** Klaim laporan akurat dan terverifikasi secara matematis pada baris kode.

---

## Klaim 12: Semua proses transaksi dan pelunasan kasir dibungkus DB::transaction.
- **Status Verifikasi:** **`SESUAI`**
- **Bukti Kode Empiris:**
- Pembuatan pesanan pelanggan: Dibungkus `DB::transaction(...)` di `app/Http/Controllers/NucomuController.php` baris 1848.
- Pembuatan pesanan langsung di kasir: Dibungkus `DB::transaction(...)` di `app/Http/Controllers/KasirController.php` baris 560.
- Perubahan status pesanan & pelunasan kasir: Dibungkus `DB::transaction(...)` di `KasirController.php` baris 690 dan `Admin\OrderController.php` baris 170.
- **Kesimpulan Analisis:** Prinsip atomisitas database (ACID) diterapkan secara konsisten pada alur transaksi.

---

## Klaim 13: Soft delete pada menu, dan kapan forceDelete dipakai.
- **Status Verifikasi:** **`SESUAI`**
- **Bukti Kode Empiris:**
- Model `App\Models\Menu` mengimplementasikan `use Illuminate\Database\Eloquent\SoftDeletes;` yang mengisi kolom `deleted_at` saat tombol hapus diklik (`Admin\MenuController@destroy`).
- Metode `forceDelete` (`Admin\MenuController@forceDelete`) menerapkan proteksi relasi ketat: HANYA dieksekusi jika menu tersebut belum pernah dipesan pelanggan sama sekali (`if ($menu->orderItems()->count() == 0)`). Jika menu sudah memiliki riwayat di `order_items`, sistem menolak force delete dan mewajibkan soft delete demi integritas rekap kasir.
- **Kesimpulan Analisis:** Klaim laporan terverifikasi akurat.

---

## Klaim 14: Dukungan cetak struk 58 mm dan 80 mm: apakah lebar bisa dipilih atau hanya satu ukuran?
- **Status Verifikasi:** **`HANYA SATU UKURAN (Terkunci pada 80 mm)`**
- **Bukti Kode Empiris:**
- Berkas templat cetak `resources/views/admin/orders/receipt.blade.php` mengunci ukuran kertas pada CSS print: `@page { size: 80mm auto; margin: 0; }` dan kontainer nota `width: 76mm;`.
- Tidak ditemukan saklar (*toggle*), dropdown pilihan ukuran kertas, maupun parameter URL (`?size=58` atau `?size=80`) di sisi kode pengontrol maupun antarmuka kasir.
- **Kesimpulan Analisis:** Laporan yang mengklaim 'mendukung pemilihan ukuran kertas 58mm dan 80mm' tidak sesuai dengan kondisi riil; ukuran nota saat ini paten pada format 80mm.

---

## Klaim 15: Daftar peran pengguna dan hak aksesnya (cocokkan dengan 4 peran: pelanggan, kasir, admin, kurir).
- **Status Verifikasi:** **`SESUAI FUNGSIONAL, BERBEDA STRUKTUR TABEL`**
- **Bukti Kode Empiris:**
- **Secara Fungsional Bisnis:** Terdapat 4 aktor yang berinteraksi dengan sistem (Pelanggan, Kasir, Kurir, dan Admin).
- **Secara Struktur Basis Data (`users`):** Kolom `users.role` hanya mengenal 3 nilai: `'admin'`, `'kasir'`, dan `'user'`.
- **Status Kurir Zendo:** Kurir **TIDAK MEMILIKI AKUN** di tabel `users`. Hak akses kurir diamankan melalui tautan unik berbasis token (`zendo_deliveries.courier_token`), verifikasi PIN kurir di tabel `settings`, dan pengikatan sidik jari peramban (`device_fingerprint`).
- **Kesimpulan Analisis:** Laporan perlu menjelaskan bahwa kurir adalah peran berbasis token logistik, bukan akun pengguna pada tabel `users`.

---
