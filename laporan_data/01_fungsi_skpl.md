# TAHAP 1: KEBUTUHAN FUNGSIONAL SISTEM (SKPL)
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe
**Standar Format:** Penomoran SKPL-NCM.K-xxxx & Matriks Rute-Controller-View-Tabel

---

## 1. Tabel Kebutuhan Fungsional Lengkap (Berdasarkan Kode Nyata)

| No | Kode SKPL | Nama Fungsi | Aktor | Metode & Rute URL | Controller @ Method | View Blade Terkait | Tabel Basis Data yang Digunakan |
|---|---|---|---|---|---|---|---|
| 1 | `SKPL-NCM.K-0001` | Profil Kafe & Nilai Brand | Pelanggan | `GET /` | `NucomuController@index` | `resources/views/home/index.blade.php` | `settings` |
| 2 | `SKPL-NCM.K-0002` | Katalog Fasilitas Kafe | Pelanggan | `GET /` | `NucomuController@index` | `resources/views/home/sections/fasilitas.blade.php` | `facilities` |
| 3 | `SKPL-NCM.K-0003` | Galeri Visual & Suasana Kafe | Pelanggan | `GET /` | `NucomuController@index` | `resources/views/home/sections/gallery.blade.php` | `gallery_items` |
| 4 | `SKPL-NCM.K-0004` | Tampilan Ulasan Pelanggan | Pelanggan | `GET /` | `NucomuController@index` | `resources/views/home/sections/review.blade.php` | `testimonials, settings` |
| 5 | `SKPL-NCM.K-0005` | Kirim Ulasan / Testimoni Mandiri | Pelanggan | `POST /testimoni` | `NucomuController@storeTestimonial` | `Modal Form (home/sections/review.blade.php)` | `testimonials` |
| 6 | `SKPL-NCM.K-0006` | Agenda Acara / Event Kafe | Pelanggan | `GET /` | `NucomuController@index` | `resources/views/home/sections/events.blade.php` | `events` |
| 7 | `SKPL-NCM.K-0007` | Pengajuan Reservasi Meja Dinamis | Pelanggan | `POST /reservasi` | `NucomuController@storeReservation` | `resources/views/home/sections/reservasi.blade.php` | `reservations, reservation_fields` |
| 8 | `SKPL-NCM.K-0008` | Pencarian dan Filter Kategori Menu | Pelanggan | `GET / & GET /menu` | `NucomuController@index & menuList` | `resources/views/menu/index.blade.php` | `menus, categories` |
| 9 | `SKPL-NCM.K-0009` | Detail Menu dan Pilihan Varian | Pelanggan | `GET /menu/{menu}` | `NucomuController@showMenu` | `resources/views/components/menu-modal.blade.php` | `menus, menu_options` |
| 10 | `SKPL-NCM.K-0010` | Keranjang Belanja Mandiri (Cart Drawer) | Pelanggan | `Client-side (DOM)` | `NucomuController@index (katalog)` | `resources/views/components/cart-drawer.blade.php` | `menus (LocalStorage browser)` |
| 11 | `SKPL-NCM.K-0011` | Checkout Mode 1 (Dine In / Take Away Mandiri) | Pelanggan | `POST /pesan` | `NucomuController@storeOrder` | `resources/views/components/cart-drawer.blade.php` | `orders, order_items, menus` |
| 12 | `SKPL-NCM.K-0012` | Tampilan Slip QR Pesanan Mandiri (NCM-xxx) | Pelanggan | `GET /qr/pesanan/{code}` | `Admin\OrderController@qrDetail` | `resources/views/admin/orders/qr-detail.blade.php` | `orders, order_items` |
| 13 | `SKPL-NCM.K-0013` | Checkout Mode 2 (Chat WhatsApp Zendo) | Pelanggan | `POST /pesan` | `NucomuController@storeOrder` | `resources/views/components/cart-drawer.blade.php` | `orders, order_items, settings` |
| 14 | `SKPL-NCM.K-0014` | Checkout Mode 3 (Antar Kurir Zendo GPS) | Pelanggan | `POST /pesan` | `NucomuController@storeOrder` | `resources/views/components/cart-drawer.blade.php` | `orders, order_items, zendo_deliveries, settings` |
| 15 | `SKPL-NCM.K-0015` | Tautan dan Modal Pesan GrabFood | Pelanggan | `GET / & modal link` | `NucomuController@index` | `resources/views/home/sections/best-menu.blade.php` | `settings` |
| 16 | `SKPL-NCM.K-0016` | Pilihan Pembayaran Tunai & Panduan QRIS | Pelanggan | `POST /pesan` | `NucomuController@storeOrder` | `resources/views/components/cart-drawer.blade.php` | `orders, settings` |
| 17 | `SKPL-NCM.K-0017` | Pelacakan Status Pesanan & Live Tracking GPS | Pelanggan | `GET /zendo/lacak/{token} & GET /lacak/{code}` | `ZendoTrackingController@trackDelivery` | `resources/views/zendo/tracking.blade.php` | `orders, zendo_deliveries, settings` |
| 18 | `SKPL-NCM.K-0018` | Checkout Formulir Sesi Warisan (Legacy Checkout) | Pelanggan | `GET /checkout & POST /checkout` | `OrderController@checkout & store` | `resources/views/order/checkout.blade.php` | `orders, order_items, menus` |
| 19 | `SKPL-NCM.K-0019` | Login Staf Kasir | Kasir | `GET /kasir/login & POST /kasir/login` | `KasirController@showLogin & login` | `resources/views/kasir/login.blade.php` | `users` |
| 20 | `SKPL-NCM.K-0020` | Logout Staf Kasir | Kasir | `POST /kasir/logout` | `KasirController@logout` | `Redirect ke /kasir/login` | `users` |
| 21 | `SKPL-NCM.K-0021` | Katalog Penjualan Kasir POS | Kasir | `GET /kasir` | `KasirController@index` | `resources/views/kasir/index.blade.php` | `menus, categories, orders` |
| 22 | `SKPL-NCM.K-0022` | Pencarian dan Pemindaian Slip QR Kasir | Kasir | `GET /kasir/pesanan/cari` | `KasirController@lookupOrder` | `resources/views/kasir/index.blade.php (modal)` | `orders, order_items` |
| 23 | `SKPL-NCM.K-0023` | Entri Pesanan Langsung di Meja Kasir POS | Kasir | `POST /kasir/pesanan` | `KasirController@storeOrder` | `resources/views/kasir/index.blade.php` | `orders, order_items, menus` |
| 24 | `SKPL-NCM.K-0024` | Pelunasan & Perubahan Status Pesanan Kasir | Kasir | `POST /kasir/pesanan/{order}/status` | `KasirController@updateOrderStatus` | `resources/views/kasir/index.blade.php` | `orders` |
| 25 | `SKPL-NCM.K-0025` | Cetak Struk Transaksi Termal Kasir | Kasir | `GET /kasir/pesanan/{order}/struk` | `KasirController@receipt` | `resources/views/kasir/receipt.blade.php` | `orders, order_items, settings` |
| 26 | `SKPL-NCM.K-0026` | Update Stok Cepat Menu oleh Kasir | Kasir | `POST /kasir/stok` | `KasirController@updateStock` | `resources/views/kasir/index.blade.php (modal stok)` | `menus` |
| 27 | `SKPL-NCM.K-0027` | Sinkronisasi Data Offline/Online Kasir | Kasir | `GET /kasir/sinkron` | `KasirController@syncData` | `JSON Response` | `menus, categories, orders` |
| 28 | `SKPL-NCM.K-0028` | Verifikasi PIN Kurir & Pengikatan Perangkat | Kurir | `POST /zendo/lacak/{token}/verifikasi-pin` | `ZendoTrackingController@verifyPinAndBindDevice` | `resources/views/zendo/tracking.blade.php (modal)` | `zendo_deliveries, settings` |
| 29 | `SKPL-NCM.K-0029` | Pelepasan Ikatan Perangkat Kurir | Kurir | `POST /zendo/lacak/{token}/lepas-perangkat` | `ZendoTrackingController@unbindDevice` | `resources/views/zendo/tracking.blade.php` | `zendo_deliveries` |
| 30 | `SKPL-NCM.K-0030` | Streaming Pembaruan Koordinat GPS Kurir | Kurir | `POST /zendo/lacak/{token}/lokasi` | `ZendoTrackingController@updateLocation` | `resources/views/zendo/tracking.blade.php` | `zendo_deliveries` |
| 31 | `SKPL-NCM.K-0031` | Pemeriksaan Validasi Anti-Fake GPS | Kurir | `POST /zendo/lacak/{token}/lokasi` | `ZendoTrackingController@updateLocation` | `JSON Response Warning` | `zendo_deliveries` |
| 32 | `SKPL-NCM.K-0032` | Pembaruan Status Pengantaran oleh Kurir | Kurir | `POST /zendo/lacak/{token}/status` | `ZendoTrackingController@updateStatus` | `resources/views/zendo/tracking.blade.php` | `zendo_deliveries, orders` |
| 33 | `SKPL-NCM.K-0033` | Login Staf Admin | Admin | `GET /admin/login & POST /admin/login` | `Admin\AuthController@showLogin & login` | `resources/views/admin/login.blade.php` | `users` |
| 34 | `SKPL-NCM.K-0034` | Logout Staf Admin | Admin | `POST /admin/logout` | `Admin\AuthController@logout` | `Redirect ke /admin/login` | `users` |
| 35 | `SKPL-NCM.K-0035` | Dasbor Analitik & 4 Metrik Penjualan | Admin | `GET /admin/dashboard` | `Admin\DashboardController@index` | `resources/views/admin/dashboard.blade.php` | `orders, order_items, reservations, menus, settings` |
| 36 | `SKPL-NCM.K-0036` | Saklar Sembunyi/Tampil Grafik Penjualan Dasbor | Admin | `POST /admin/pengaturan/dashboard-chart` | `Admin\SettingController@toggleDashboardChart` | `resources/views/admin/dashboard.blade.php` | `settings` |
| 37 | `SKPL-NCM.K-0037` | Kelola Banner Promosi Berjadwal | Admin | `GET /admin/banner & POST /admin/banner` | `Admin\BannerController@index & store` | `resources/views/admin/banners/index.blade.php` | `banners` |
| 38 | `SKPL-NCM.K-0038` | Perpanjang Masa Tayang Banner Promo | Admin | `POST /admin/banner/{banner}/perpanjang` | `Admin\BannerController@extendDuration` | `resources/views/admin/banners/index.blade.php` | `banners` |
| 39 | `SKPL-NCM.K-0039` | Operasional Edit, Hapus, & Toggle Status Banner | Admin | `PUT/DELETE/POST /admin/banner/{banner}` | `Admin\BannerController@update, destroy, toggleStatus` | `resources/views/admin/banners/edit.blade.php` | `banners` |
| 40 | `SKPL-NCM.K-0040` | Deteksi Otomatis Banner Menjelang Berakhir (30 Mnt) | Admin | `GET /admin/banner/expiring-check` | `Admin\BannerController@checkExpiring` | `Modal Alert JSON` | `banners` |
| 41 | `SKPL-NCM.K-0041` | Kelola Our Story / Konten Profil Kafe | Admin | `GET /admin/about & POST /admin/about` | `Admin\AboutController@index & update` | `resources/views/admin/about/index.blade.php` | `settings` |
| 42 | `SKPL-NCM.K-0042` | CRUD Fasilitas Kafe & Foto Slider | Admin | `GET/POST/PUT/DELETE /admin/fasilitas` | `Admin\FacilityController@index, store, update, destroy` | `resources/views/admin/facilities/index.blade.php` | `facilities` |
| 43 | `SKPL-NCM.K-0043` | CRUD Koleksi Foto Galeri Kafe | Admin | `GET/POST/DELETE /admin/galeri` | `Admin\GalleryController@index, store, destroy` | `resources/views/admin/gallery/index.blade.php` | `gallery_items` |
| 44 | `SKPL-NCM.K-0044` | CRUD Agenda Acara & Event Kafe | Admin | `GET/POST/PUT/DELETE /admin/event` | `Admin\SettingController@events, storeEvent, updateEvent, deleteEvent` | `resources/views/admin/events/index.blade.php` | `events` |
| 45 | `SKPL-NCM.K-0045` | CRUD Kategori Menu & Proteksi Relasi | Admin | `GET/POST/PUT/DELETE /admin/kategori` | `Admin\CategoryController@index, store, update, destroy` | `resources/views/admin/categories/index.blade.php` | `categories, menus` |
| 46 | `SKPL-NCM.K-0046` | CRUD Menu Kafe (Tambah & Edit) | Admin | `GET/POST/PUT /admin/menu` | `Admin\MenuController@create, store, edit, update` | `resources/views/admin/menus/form.blade.php` | `menus, categories` |
| 47 | `SKPL-NCM.K-0047` | Soft Delete Menu Kafe | Admin | `DELETE /admin/menu/{menu}` | `Admin\MenuController@destroy` | `resources/views/admin/menus/index.blade.php` | `menus, order_items` |
| 48 | `SKPL-NCM.K-0048` | Pulihkan Menu Terhapus (Restore) | Admin | `POST /admin/menu/{id}/restore` | `Admin\MenuController@restore` | `resources/views/admin/menus/index.blade.php` | `menus` |
| 49 | `SKPL-NCM.K-0049` | Hapus Permanen Menu (Force Delete) | Admin | `DELETE /admin/menu/{id}/force-delete` | `Admin\MenuController@forceDelete` | `resources/views/admin/menus/index.blade.php` | `menus` |
| 50 | `SKPL-NCM.K-0050` | Kontrol Ketersediaan, Stok, & Status Featured Menu | Admin | `POST /admin/menu/{menu}/toggle-* & /stock` | `Admin\MenuController@toggleAvailable, updateStock, toggleFeatured, toggleBestSeller` | `resources/views/admin/menus/index.blade.php` | `menus` |
| 51 | `SKPL-NCM.K-0051` | Kurasi Ulasan & Testimoni Pelanggan | Admin | `GET /admin/testimoni` | `Admin\TestimonialController@index` | `resources/views/admin/testimonials/index.blade.php` | `testimonials` |
| 52 | `SKPL-NCM.K-0052` | Sematkan / Lepas Sematan Ulasan ke Beranda (Pin/Unpin) | Admin | `POST /admin/testimoni/{testimonial}/toggle-pin` | `Admin\TestimonialController@togglePin` | `resources/views/admin/testimonials/index.blade.php` | `testimonials` |
| 53 | `SKPL-NCM.K-0053` | Toggle Status Aktif & Hapus Ulasan Spam | Admin | `POST/DELETE /admin/testimoni/{testimonial}` | `Admin\TestimonialController@toggleActive & destroy` | `resources/views/admin/testimonials/index.blade.php` | `testimonials` |
| 54 | `SKPL-NCM.K-0054` | Pemantauan & Pembaruan Status Reservasi Masuk | Admin | `GET/POST/DELETE /admin/reservasi` | `Admin\ReservationController@index, updateStatus, destroy` | `resources/views/admin/reservations/index.blade.php` | `reservations, reservation_fields` |
| 55 | `SKPL-NCM.K-0055` | Konfigurasi Nomor WhatsApp Notifikasi Reservasi | Admin | `POST /admin/reservasi/whatsapp` | `Admin\ReservationController@updateWhatsapp` | `resources/views/admin/reservations/index.blade.php` | `settings` |
| 56 | `SKPL-NCM.K-0056` | Form Builder Dinamis Kolom Tambahan Reservasi | Admin | `POST/PUT/DELETE/MATCH /admin/reservasi/fields` | `Admin\ReservationController@storeField, updateField, destroyField, toggleField` | `resources/views/admin/reservations/index.blade.php` | `reservation_fields` |
| 57 | `SKPL-NCM.K-0057` | Konfigurasi 3 Mode Transaksi & Foto QRIS | Admin | `POST /admin/pesanan/settings` | `Admin\OrderController@updateSettings` | `resources/views/admin/orders/index.blade.php` | `settings` |
| 58 | `SKPL-NCM.K-0058` | Kustomisasi & Reset Template Chat WA Zendo | Admin | `POST /admin/pesanan/settings/reset-template` | `Admin\OrderController@resetTemplate` | `resources/views/admin/orders/index.blade.php` | `settings` |
| 59 | `SKPL-NCM.K-0059` | Pemantauan, Filter, & Detail Transaksi Pesanan | Admin | `GET /admin/pesanan & /admin/pesanan/{order}` | `Admin\OrderController@index & show` | `resources/views/admin/orders/index.blade.php & show.blade.php` | `orders, order_items` |
| 60 | `SKPL-NCM.K-0060` | Pembaruan Status Operasional & Pembayaran Pesanan | Admin | `POST /admin/pesanan/{order}/status` | `Admin\OrderController@updateStatus` | `resources/views/admin/orders/show.blade.php` | `orders` |
| 61 | `SKPL-NCM.K-0061` | Cetak Struk Transaksi Sisi Admin | Admin | `GET /admin/pesanan/{order}/struk` | `Admin\OrderController@printReceipt` | `resources/views/admin/orders/receipt.blade.php` | `orders, order_items, settings` |
| 62 | `SKPL-NCM.K-0062` | Pemindai Kamera Slip QR Sisi Admin | Admin | `GET /admin/pesanan/scan` | `Admin\OrderController@scanPage` | `resources/views/admin/orders/scan.blade.php` | `orders` |
| 63 | `SKPL-NCM.K-0063` | Ekspor Laporan Rekapitulasi Penjualan PDF 1.4 Native | Admin | `GET /admin/pesanan/rekap?format=pdf` | `Admin\OrderController@exportRecap` | `TransactionReportPdfGenerator (Stream PDF)` | `orders, order_items, settings` |
| 64 | `SKPL-NCM.K-0064` | Ekspor Laporan Rekapitulasi Penjualan CSV / Excel | Admin | `GET /admin/pesanan/rekap?format=csv` | `Admin\OrderController@exportRecap` | `Stream CSV UTF-8 BOM` | `orders, order_items, settings` |
| 65 | `SKPL-NCM.K-0065` | Pengaturan Daftar & Nomor WhatsApp Kurir Zendo | Admin | `POST /admin/zendo/couriers` | `Admin\SettingController@updateZendoCouriers` | `resources/views/admin/zendo/index.blade.php` | `settings` |
| 66 | `SKPL-NCM.K-0066` | Pengaturan Tarif Ongkir Bertingkat Zendo | Admin | `POST /admin/zendo/tariff` | `Admin\SettingController@updateZendoTariff` | `resources/views/admin/zendo/index.blade.php` | `settings` |
| 67 | `SKPL-NCM.K-0067` | Reset Ikatan Perangkat & Reset PIN Kurir Zendo | Admin | `POST /admin/zendo/deliveries/{token}/reset-*` | `Admin\SettingController@resetCourierDevice & resetCourierPin` | `resources/views/admin/zendo/index.blade.php` | `zendo_deliveries` |
| 68 | `SKPL-NCM.K-0068` | CRUD Master Data Nomor Meja Kafe | Admin | `GET/POST/PUT/DELETE /admin/pengaturan/meja` | `Admin\SettingController@tables, storeTable, updateTable, deleteTable` | `resources/views/admin/settings/tables.blade.php` | `tables` |
| 69 | `SKPL-NCM.K-0069` | CRUD Jadwal Jam Kerja & Hari Operasional | Admin | `GET/POST /admin/pengaturan/jam-operasional` | `Admin\SettingController@operatingHours & updateOperatingHours` | `resources/views/admin/settings/operating-hours.blade.php` | `operating_hours` |
| 70 | `SKPL-NCM.K-0070` | Kelola Informasi Kontak, Lokasi, & Sosial Media | Admin | `GET/POST /admin/contact` | `Admin\ContactController@index & update` | `resources/views/admin/contact/index.blade.php` | `settings` |
| 71 | `SKPL-NCM.K-0071` | Pengaturan Profil Pribadi Admin (Nama, Email, Avatar) | Admin | `GET/POST /admin/profile` | `Admin\ProfileController@index, update, removeAvatar` | `resources/views/admin/profile/index.blade.php` | `users` |
| 72 | `SKPL-NCM.K-0072` | Ubah Kata Sandi Pribadi Akun Admin | Admin | `POST /admin/profile/password` | `Admin\ProfileController@updatePassword` | `resources/views/admin/profile/index.blade.php` | `users` |
| 73 | `SKPL-NCM.K-0073` | Pemantauan Notifikasi Real-time & Polling Masuk | Admin | `GET/POST /admin/notifications & /notifications/poll` | `Admin\NotificationController@index, poll, toggleRead, markAllRead` | `resources/views/admin/notifications/index.blade.php` | `orders, reservations` |
| 74 | `SKPL-NCM.K-0074` | Dokumentasi & Bantuan Operasional Sistem | Admin | `GET /admin/help` | `Admin\HelpController@index` | `resources/views/admin/help/index.blade.php` | `(Tanpa tabel / Static View)` |

---

## 2. Analisis Perbandingan: Kode vs Daftar Laporan Pengguna

### (a) Fungsi di Kode yang TIDAK ADA di Daftar Laporan Pengguna
Fungsi-fungsi berikut telah diimplementasikan secara aktif di dalam kode program, namun belum tercantum dalam ringkasan laporan awal pengguna:
1. **Pulihkan Menu Terhapus (`restore`) & Hapus Permanen (`forceDelete`):** Fitur soft delete di `Admin\MenuController@restore` dan `@forceDelete` memungkinkan pengembalian menu yang terhapus.
2. **Perpanjang Masa Tayang Banner Promo:** Di `Admin\BannerController@extendDuration`, admin dapat menambah durasi tayang banner secara cepat tanpa mengedit seluruh form.
3. **Deteksi Banner Promo Segera Berakhir:** Endpoint JSON `Admin\BannerController@checkExpiring` memeriksa otomatis banner yang akan habis dalam waktu 30 menit.
4. **Saklar Sembunyi/Tampil Grafik Penjualan Dasbor:** Di `Admin\SettingController@toggleDashboardChart`, admin dapat mematikan chart jika ingin memuat dasbor lebih ringan.
5. **Pelepasan Ikatan Perangkat Kurir (`unbindDevice`):** Di `ZendoTrackingController@unbindDevice` dan `Admin\SettingController@resetCourierDevice`, perangkat kurir dapat dilepas ikatannya dari sesi pengantaran.
6. **Manajemen Master Meja Kafe (`tables`):** CRUD nomor meja di `Admin\SettingController@tables` untuk pendaftaran nomor meja fisik kafe.
7. **Manajemen Jam & Hari Operasional (`operating_hours`):** CRUD jam buka-tutup per hari di `Admin\SettingController@operatingHours`.
8. **Pusat Notifikasi & Polling Masuk:** Di `Admin\NotificationController`, terdapat polling real-time untuk mendeteksi pesanan dan reservasi baru.
9. **Halaman Dokumentasi Bantuan Admin:** Di `Admin\HelpController@index`, panduan operasional kafe disediakan langsung di panel admin.
10. **Sinkronisasi Data Offline/Online Kasir:** Di `KasirController@syncData`, kasir dapat mengunduh snapshot menu dan pesanan untuk sinkronisasi lokal.
11. **Formulir Checkout Warisan (Legacy):** Rute `/checkout` di `OrderController` yang berbasis sesi server masih ada di kode.

### (b) Fungsi di Daftar Laporan Pengguna yang TIDAK ADA di Kode
Fungsi-fungsi berikut tercantum di daftar laporan pengguna, namun **TIDAK DITEMUKAN** implementasinya di dalam basis kode:
1. **Shift Kasir (Buka/Tutup Shift, Modal Awal):**
   - *Status:* **TIDAK DITEMUKAN**.
   - *Fakta Kode:* Di `KasirController.php` maupun skema basis data tidak ada tabel shift, kolom modal awal, waktu buka/tutup shift kasir, maupun mekanisme penghitungan kas harian saat kasir pergantian giliran kerja.
2. **Log Aktivitas (Audit Log / Activity Log):**
   - *Status:* **TIDAK DITEMUKAN**.
   - *Fakta Kode:* Tidak ditemukan tabel seperti `activity_logs` atau `audit_trails`, serta tidak ada middleware atau observer yang mencatat riwayat penambahan/penghapusan data oleh admin ke dalam basis data.
3. **Manajemen Akun (User Management Multi-User CRUD):**
   - *Status:* **TIDAK DITEMUKAN (Hanya Profil Mandiri)**.
   - *Fakta Kode:* Pengguna di laporan mengira ada fitur 'manajemen akun' untuk membuat/menambah/menghapus akun staf admin/kasir lain. Faktanya, admin hanya memiliki `ProfileController` untuk mengedit datanya sendiri (nama, email, password, avatar). Akun kasir dan admin di-*seed* melalui migration/seeder (`ensureDefaultKasirUser`).
