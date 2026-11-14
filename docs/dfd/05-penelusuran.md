# MATRIKS PENELUSURAN KODE, ASUMSI & FITUR SETENGAH JADI
**Standar Analisis:** Matriks Penelusuran Persyaratan (*Requirements Traceability Matrix - RTM*)  
**Tujuan:** Membuktikan secara empiris bahwa setiap kotak fitur dan alur data di dalam diagram berjenjang serta DFD benar-benar bersumber dari kode program, rute URL, pengontrol (*controllers*), dan tabel basis data.

---

## 1. Matriks Penelusuran Fitur (*Traceability Matrix*)

Tabel berikut menghubungkan kode hierarki fitur dari dokumen `01-diagram-berjenjang.md` dengan implementasi rute, pengontrol, tampilan Blade, dan tabel database yang disentuh:

| Kode Hierarki | Nama Fitur / Modul | Metode & Rute URL | Pengontrol & Method PHP | Tampilan View Blade | Tabel yang Disentuh (Operasi) |
| :---: | :--- | :--- | :--- | :--- | :--- |
| **1.1.1** | Pantau 4 Metrik Penjualan | `GET /admin/dashboard` | `Admin\DashboardController@index` | `admin.dashboard` | `orders` (R), `reservations` (R), `menus` (R) |
| **1.1.2** | Grafik Tren Keuangan Harian/Mingguan/Bulanan | `GET /admin/dashboard` | `Admin\DashboardController@buildFinancialSalesChartData` | `admin.dashboard` | `orders` (R), `settings` (R) |
| **1.1.3** | Menu Terlaris (Top 5) | `GET /admin/dashboard` | `Admin\DashboardController@index` | `admin.dashboard` | `order_items` (R) |
| **1.1.4** | Saklar Sembunyi/Tampil Grafik | `POST /admin/pengaturan/dashboard-chart` | `Admin\SettingController@toggleDashboardChart` | `admin.dashboard` | `settings` (W: `hide_dashboard_chart`) |
| **1.2.1** | Tambah & Upload Banner Promo | `POST /admin/banner` | `Admin\BannerController@store` | `admin.banners.create` | `banners` (W) |
| **1.2.2** | Jadwal & Auto-Expired Banner | `GET /admin/banner` | `Admin\BannerController@index` | `admin.banners.index` | `banners` (R) |
| **1.2.3** | Perpanjang Masa Tayang Banner | `POST /admin/banner/{banner}/perpanjang` | `Admin\BannerController@extendDuration` | `admin.banners.index` | `banners` (W: `end_time`) |
| **1.2.4** | Operasional Edit & Hapus Banner | `PUT/DELETE /admin/banner/{banner}` | `Admin\BannerController@update / @destroy` | `admin.banners.edit` | `banners` (W/D) |
| **1.2.5** | Deteksi Banner Segera Habis (30 Mnt) | `GET /admin/banner/expiring-check` | `Admin\BannerController@checkExpiring` | Modal Notifikasi JSON | `banners` (R) |
| **1.3.1** | Kelola Our Story / Tentang Kafe | `POST /admin/about` | `Admin\AboutController@update` | `admin.about.index` | `settings` (W: `about_*`) |
| **1.3.2** | Kelola Fasilitas & Foto Slider | `POST /admin/fasilitas` | `Admin\FacilityController@store / @update` | `admin.facilities.*` | `facilities` (W: `photos` JSON) |
| **1.3.3** | Kelola Koleksi Galeri Visual | `POST /admin/galeri` | `Admin\GalleryController@store / @destroy` | `admin.gallery.index` | `gallery_items` (W/D) |
| **1.3.4** | Kelola Agenda & Event Kafe | `POST /admin/event` | `Admin\SettingController@storeEvent` | `admin.events.index` | `events` (W/D) |
| **1.4.1** | Tambah & Proteksi Hapus Kategori | `DELETE /admin/kategori/{category}` | `Admin\CategoryController@destroy` | `admin.categories.index` | `categories` (D), `menus` (R: count) |
| **1.4.2** | Tambah & Edit Menu (Foto 1:1) | `POST /admin/menu` | `Admin\MenuController@store / @update` | `admin.menus.form` | `menus` (W), `categories` (R) |
| **1.4.3** | Kontrol Stok & Ketersediaan Menu | `POST /admin/menu/{menu}/toggle-available` | `Admin\MenuController@toggleAvailable` | `admin.menus.index` | `menus` (W: `is_available`, `stock`) |
| **1.4.4** | Proteksi Hapus Menu Pernah Dipesan | `DELETE /admin/menu/{menu}` | `Admin\MenuController@destroy` | `admin.menus.index` | `menus` (W: `deleted_at`), `order_items` (R: count) |
| **1.4.5** | Pulihkan Menu Terhapus (Restore) | `POST /admin/menu/{id}/restore` | `Admin\MenuController@restore` | `admin.menus.index` | `menus` (W: `deleted_at=NULL`) |
| **1.5.1** | Filter & Daftar Ulasan Pelanggan | `GET /admin/testimoni` | `Admin\TestimonialController@index` | `admin.testimonials.index` | `testimonials` (R) |
| **1.5.2** | Sematkan Ulasan ke Beranda (Pin) | `POST /admin/testimoni/{testimonial}/toggle` | `Admin\TestimonialController@togglePin` | `admin.testimonials.index` | `testimonials` (W: `is_pinned=true`, `is_active=true`) |
| **1.5.3** | Lepas Sematan Ulasan (Unpin) | `POST /admin/testimoni/{testimonial}/toggle` | `Admin\TestimonialController@togglePin` | `admin.testimonials.index` | `testimonials` (W: `is_pinned=false`) |
| **1.5.4** | Sembunyikan Ulasan (Toggle Active) | `POST /admin/testimoni/{testimonial}/toggle` | `Admin\TestimonialController@toggleActive` | `admin.testimonials.index` | `testimonials` (W: `is_active`, `is_pinned=false`) |
| **1.5.5** | Hapus Ulasan Spam & Berkas Foto | `DELETE /admin/testimoni/{testimonial}` | `Admin\TestimonialController@destroy` | `admin.testimonials.index` | `testimonials` (D) |
| **1.6.1** | Pantau Reservasi Masuk | `GET /admin/reservasi` | `Admin\ReservationController@index` | `admin.reservations.index` | `reservations` (R), `reservation_fields` (R) |
| **1.6.2** | Ubah Status & Hapus Reservasi | `POST /admin/reservasi/{res}/status` | `Admin\ReservationController@updateStatus` | `admin.reservations.index` | `reservations` (W/D) |
| **1.6.3** | Atur Nomor WA Tujuan Reservasi | `POST /admin/reservasi/whatsapp` | `Admin\ReservationController@updateWhatsapp` | `admin.reservations.index` | `settings` (W: `reservation_whatsapp`) |
| **1.6.4** | Tambah & Atur Kolom Form Builder | `POST /admin/reservasi/fields` | `Admin\ReservationController@storeField` | `admin.reservations.index` | `reservation_fields` (W) |
| **1.7.1** | Konfigurasi 3 Mode Transaksi | `POST /admin/pesanan/settings` | `Admin\OrderController@updateSettings` | `admin.orders.index` | `settings` (W: `order_mode_*`) |
| **1.7.2** | Sub-Pengaturan Tunai & Foto QRIS | `POST /admin/pesanan/settings` | `Admin\OrderController@updateSettings` | `admin.orders.index` | `settings` (W: `qris_image`, `order_dine_in_*`) |
| **1.7.3** | Edit & Reset Template Chat WA | `POST /admin/pesanan/settings/reset-template` | `Admin\OrderController@resetTemplate` | `admin.orders.index` | `settings` (W: `zendo_chat_template`) |
| **1.7.4** | Operasional Pesanan & Status | `POST /admin/pesanan/{order}/status` | `Admin\OrderController@updateStatus` | `admin.orders.show` | `orders` (W: `status`, `payment_status`) |
| **1.7.5** | Ekspor Laporan Rekap PDF 1.4 Native | `GET /admin/pesanan/rekap` | `Admin\OrderController@exportRecap` | `TransactionReportPdfGenerator` | `orders` (R), `order_items` (R), `settings` (R) |
| **1.7.6** | Pemindai Kamera Slip QR Kasir | `GET /admin/pesanan/scan` | `Admin\OrderController@scanPage` | `admin.orders.scan` | `orders` (R) |
| **1.8.1** | Kelola Daftar & Nomor HP Kurir | `POST /admin/zendo/couriers` | `Admin\SettingController@updateZendoCouriers` | `admin.zendo.index` | `settings` (W: `zendo_couriers`) |
| **1.8.2** | Konfigurasi Tarif Ongkir Jarak | `POST /admin/zendo/tariff` | `Admin\SettingController@updateZendoTariff` | `admin.zendo.index` | `settings` (W: `zendo_tariff_*`) |
| **1.8.3** | Reset Akses / Cabut Device Kurir | `POST /admin/zendo/devices/{id}/reset` | `Admin\SettingController@resetZendoDevice` | `admin.zendo.index` | `settings` (W: `zendo_revoked_*`) |
| **1.9.1** | Profil Admin & Ganti Password | `POST /admin/profile/password` | `Admin\ProfileController@updatePassword` | `admin.profile.index` | `users` (W: `password` hash bcrypt) |
| **1.9.2** | Agregasi & Tandai Baca Notifikasi | `POST /admin/notifications/toggle-read` | `Admin\NotificationController@toggleRead` | `admin.notifications.index` | `settings` (W: `admin_read_notification_keys`) |
| **1.9.3** | Pusat Bantuan & Manual Penggunaan | `GET /admin/bantuan` | `Admin\HelpController@index` | `admin.help.index` | Tanpa database (statis panduan 9 modul) |
| **1.9.4** | Jam Buka & Hari Libur Kafe | `POST /admin/pengaturan/jam-operasional` | `Admin\SettingController@updateHours` | `admin.settings.index` | `operating_hours` (W) |
| **2.1.1** | Login Kasir Sandi & Cookie 1 Thn | `POST /kasir/login` | `KasirController@login` | `kasir.login` | `users` (R), set cookie `nucomu_kasir_auth_v2` |
| **2.2.1** | Antarmuka Kasir POS & Menu Grid | `GET /kasir` | `KasirController@index` | `kasir.index` | `categories` (R), `menus` (R), `tables` (R) |
| **2.2.2** | Entri Pesanan Langsung di Meja | `POST /kasir/pesanan` | `KasirController@storeOrder` | `kasir.index` | `orders` (W), `order_items` (W), `menus` (W: stok) |
| **2.3.1** | Scan / Cari Kode Slip QR Kasir | `GET /kasir/pesanan/cari` | `KasirController@lookupOrder` | `kasir.index` (JSON) | `orders` (R), `order_items` (R) |
| **2.4.1** | Pelunasan Pesanan Kasir | `POST /kasir/pesanan/{order}/status` | `KasirController@updateOrderStatus` | `kasir.index` (JSON) | `orders` (W: `payment_status='paid'`) |
| **2.4.2** | Cetak Struk Kasir Thermal | `GET /kasir/pesanan/{order}/struk` | `KasirController@receipt` | `kasir.receipt` | `orders` (R), `order_items` (R), `settings` (R) |
| **2.4.3** | Update Cepat Stok Fisik Menu | `POST /kasir/stok` | `KasirController@updateStock` | `kasir.index` (JSON) | `menus` (W: `stock`, `is_available`) |
| **2.4.4** | Polling Sinkronisasi POS Real-Time| `GET /kasir/sinkron` | `KasirController@syncData` | JSON Payload | `orders` (R), `menus` (R) |
| **3.1.1** | Tampilan Beranda & Banner Promo | `GET /` | `NucomuController@index` | `home.index` | `banners` (R), `settings` (R) |
| **3.2.1** | Katalog Menu per Kategori | `GET /menu` atau `/daftar-menu` | `NucomuController@allMenu` | `nucomu.all-menu` | `categories` (R), `menus` (R), `menu_options` (R) |
| **3.2.2** | Halaman Rinci Sajian Menu | `GET /menu/{menu:slug}` | `MenuController@show` | `public.menu-detail` | `menus` (R), `menu_options` (R) |
| **3.3.1** | Tampilan Ulasan Terkurasi (Pin) | `GET /` | `NucomuController@getCombinedReviews` | `home.index` | `testimonials` (R: `is_pinned=true`), Cache API |
| **3.3.2** | Kirim Ulasan Baru Pelanggan | `POST /nucomu/ulasan` | `NucomuController@storeReview` | `home.index` | `testimonials` (W: `is_pinned=false`) |
| **3.4.1** | Kirim Formulir Reservasi Dinamis | `POST /reservasi` | `NucomuController@submitReservation` | `public.reservation` / Modal | `reservations` (W: `form_data` JSON), `settings` (R) |
| **3.5.1** | Keranjang Belanja Pelanggan | Navigasi / Modal | `components.cart-drawer` (JS) | `components.cart-drawer` | Sesi / LocalStorage Browser Klien |
| **3.5.2** | Checkout Mode 1 (Makan di Kafe) | `POST /pesan` | `NucomuController@storeOrder` | `nucomu.order-slip` | `orders` (W), `order_items` (W), `menus` (W: stok) |
| **3.5.3** | Checkout Mode 2 (Antar Chat WA) | `POST /pesan` | `NucomuController@storeOrder` | Redirect `wa.me` | `orders` (W), `order_items` (W), `settings` (R) |
| **3.5.4** | Checkout Mode 3 (Antar Lacak GPS)| `POST /pesan` | `NucomuController@storeOrder` | Redirect `zendo.track` | `orders` (W), `order_items` (W), `zendo_deliveries` (W) |
| **3.6.1** | Halaman Slip Pesanan QR Pelanggan| `GET /pesan/slip/{code}` | `NucomuController@orderSlip` | `nucomu.order-slip` | `orders` (R), `order_items` (R) |
| **3.6.2** | Peta Live Tracking GPS Pelanggan| `GET /zendo/lacak/{token}` | `ZendoTrackingController@track` | `zendo.track` | `zendo_deliveries` (R), `orders` (R) |
| **4.1.1** | Verifikasi PIN Kurir & Binding | `POST /zendo/lacak/{token}/pin` | `ZendoTrackingController@verifyPin` | `zendo.track` | `zendo_deliveries` (R), `settings` (W: cookie) |
| **4.2.1** | Streaming Koordinat GPS Kurir | `POST /zendo/lacak/{token}/lokasi` | `ZendoTrackingController@updateLocation` | JSON Response | `zendo_deliveries` (W: `courier_lat`, `route_trail`) |
| **4.3.1** | Pemeriksaan Anti-Fake GPS | Di dalam `updateLocation` | `ZendoTrackingController@updateLocation` | JSON Warning | `zendo_deliveries` (W: `gps_suspicious_count`) |
| **4.4.1** | Perubahan Status Pengantaran | `POST /zendo/lacak/{token}/status` | `ZendoTrackingController@updateStatus` | JSON Response | `zendo_deliveries` (W: `status`), `orders` (W) |

---

## 2. Asumsi dan Hal yang Belum Terverifikasi dari Kode Sumber

Untuk menjaga kejujuran akademis dalam tugas SMK RPL, bagian ini mencatat hal-hal yang **tidak dapat dipastikan secara 100% hanya dari kode sumber** tanpa pengujian pada lingkungan *production*:

1. **Ketersediaan Kredensial Nyata Google Places API:**
   - *Status Kode:* Berkas `config/nucomu.php` dan `NucomuController::getCombinedReviews` mendukung konsumsi API `https://maps.googleapis.com/maps/api/place/details/json` menggunakan variabel lingkungan `GOOGLE_PLACES_API_KEY` dan `GOOGLE_PLACE_ID`.
   - *Asumsi/Belum Terverifikasi:* Pada berkas konfigurasi lokal saat ini, kedua kunci bernilai `null`. Sistem mengandalkan data ulasan bawaan (*fallback*) dari array `config('nucomu.reviews')`. Apakah akun Google Places resmi kafe sudah memiliki kuota penagihan aktif di Google Cloud Console belum dapat diverifikasi dari repositori.

2. **Integrasi Gateway WhatsApp Otomatis (Fonnte API):**
   - *Status Kode:* Metode `NucomuController::dispatchZendoWhatsappMessage` memiliki implementasi panggilan HTTP POST ke endpoint `https://api.fonnte.com/send` menggunakan `zendo_wa_api_token`.
   - *Asumsi/Belum Terverifikasi:* Pada pengaturan default, token Fonnte kosong (`''`), sehingga sistem secara otomatis beralih ke metode *fallback* yaitu membuka URL WhatsApp Web/App langsung (`https://wa.me/...`). Apakah kafe menggunakan langganan Fonnte berbayar di live server adalah asumsi yang belum terkonfirmasi.

3. **Alokasi Meja Fisik pada Reservasi Meja:**
   - *Status Kode:* Tabel `reservations` memiliki kolom `table_id` (kunci asing ke `tables.id`). Namun pada antarmuka admin `/admin/reservasi`, tidak terdapat *dropdown* pemilih meja saat admin mengubah status reservasi (hanya tombol ubah status `confirmed`, `completed`, `cancelled`).
   - *Asumsi:* Pengalokasian nomor meja fisik kepada tamu reservasi saat ini masih dilakukan secara manual oleh staf kafe di lokasi (*on-the-spot assignment*), bukan dikunci oleh sistem basis data sejak awal pemesanan.

4. **Pengembalian Dana Pembatalan Pesanan (*Refund*):**
   - *Status Kode:* Kolom `payment_status` pada tabel `orders` mendukung nilai `'refunded'`. Namun tidak ada integrasi *payment gateway* dua arah (seperti Midtrans atau Xendit) di dalam kode.
   - *Asumsi:* Seluruh pengembalian uang atas pesanan yang dibatalkan dilakukan secara manual tunai atau transfer langsung oleh kasir kafe kepada pelanggan.

---

## 3. Daftar Fitur yang Tampaknya Tidak Terpakai atau Setengah Jadi (*Dead Code / Incomplete*)

Berdasarkan pembacaan mendalam di seluruh basis kode, ditemukan beberapa artefak, rute, atau potongan skrip yang tidak tersambung ke alur operasional utama:

1. **Panggilan Endpoint `/api/transactions/process` pada Drawer Keranjang:**
   - *Temuan:* Pada berkas [resources/views/components/cart-drawer.blade.php](file:///d:/laragon/www/umkm/resources/views/components/cart-drawer.blade.php) baris 1414, fungsi JavaScript `submitDrawerCheckout` memanggil:
     ```javascript
     const res = await fetch('/api/transactions/process', { method: 'POST', ... });
     ```
   - *Kondisi Riil:* Rute `/api/transactions/process` **tidak pernah didefinisikan** di [routes/web.php](file:///d:/laragon/www/umkm/routes/web.php), dan proyek ini **tidak memiliki berkas `routes/api.php`**.
   - *Analisis:* Ini merupakan potongan kode eksperimen atau peninggalan pengujian awal *in-drawer checkout*. Alur pemesanan yang berfungsi penuh dan digunakan secara nyata di aplikasi adalah pengiriman formulir pesanan ke rute `POST /pesan` (`NucomuController::storeOrder`) dan `POST /checkout` (`OrderController::store`).

2. **Rute Checkout Warisan (*Legacy OrderController Checkout*):**
   - *Temuan:* Pada [routes/web.php](file:///d:/laragon/www/umkm/routes/web.php) baris 46-47 terdapat rute:
     ```php
     Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
     Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
     ```
   - *Kondisi Riil:* Rute ini mengandalkan data keranjang berbasis sesi server (`session('cart')`), sedangkan antarmuka Nucomu Cafe modern mengelola keranjang secara interaktif di sisi peramban (*LocalStorage* dan *Cart Drawer*) yang langsung memposting transaksi ke `/pesan` (`NucomuController::storeOrder`). Halaman `/checkout` masih ada namun jarang dilalui pada alur pengguna normal.

3. **Pembersihan Cookie `nucomu_kasir_session` saat Logout Kasir:**
   - *Temuan:* Pada `KasirController::logout` baris 213 terdapat baris:
     ```php
     Cookie::queue(Cookie::forget('nucomu_kasir_session'));
     ```
   - *Kondisi Riil:* Tidak ada bagian kode di mana cookie bernama `nucomu_kasir_session` dipasang (*queued*). Cookie autentikasi kasir yang sesungguhnya dipasang adalah `nucomu_kasir_auth_v2` (`KasirController::KASIR_COOKIE`). Penghapusan cookie tersebut tidak berbahaya, namun merupakan sisa dari versi cookie versi sebelumnya.

4. **Berkas Cadangan (*Backup Files*) di Pohon Repositori:**
   - *Temuan:* Di dalam repositori terdapat sejumlah berkas berekstensi `.bak`:
     * `app/Models/User.php.bak`
     * `app/Http/Controllers/NucomuController.php.bak`
     * `app/Http/Controllers/Admin/MenuController.php.bak`
     * `resources/views/components/footer.blade.php.bak`
     * `resources/views/layouts/admin.blade.php.bak`
   - *Analisis:* Berkas-berkas ini adalah salinan manual dari sesi pengembangan sebelumnya dan tidak dieksekusi oleh mesin runtime Laravel.
