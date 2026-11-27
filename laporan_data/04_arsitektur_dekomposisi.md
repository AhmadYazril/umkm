# TAHAP 4: ARSITEKTUR & DEKOMPOSISI MODUL
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Pola Arsitektur:** Model-View-Controller (MVC) Monolith Modern

---

## 1. Struktur Program MVC Sebenarnya
Aplikasi Nucomu Cafe dibangun di atas kerangka kerja Laravel dengan arsitektur **Model-View-Controller (MVC)**. Setiap direktori memiliki tanggung jawab terpisah yang tegas (*Separation of Concerns*):

```
umkm/
├── app/                                  # Inti logika bisnis dan backend (MVC)
│   ├── Http/
│   │   ├── Controllers/                  # Pengontrol alur request-response web
│   │   │   ├── Admin/                    # 16 Controller panel manajemen admin
│   │   │   ├── CartController.php        # Pengontrol keranjang sisi server (legacy)
│   │   │   ├── HomeController.php        # Pengontrol redirect beranda
│   │   │   ├── KasirController.php       # Pengontrol POS, scanner, pelunasan & struk kasir
│   │   │   ├── MenuController.php        # Pengontrol katalog menu publik
│   │   │   ├── NucomuController.php      # Controller utama publik (beranda, pesan, reservasi)
│   │   │   ├── OrderController.php       # Pengontrol checkout warisan berbasis sesi
│   │   │   ├── PageController.php        # Pengontrol halaman statis profil
│   │   │   └── ZendoTrackingController.php # Pengontrol live tracking GPS kurir & anti-fake
│   │   └── Middleware/                   # Lapisan filter keamanan HTTP
│   │       ├── AdminMiddleware.php       # Verifikasi sesi & cookie HMAC SHA-256 admin
│   │       └── KasirMiddleware.php       # Verifikasi sesi & cookie staf kasir POS
│   ├── Models/                           # 17 Eloquent Model representasi tabel basis data
│   │   ├── Banner.php                    # Entitas banner promo berjadwal
│   │   ├── Category.php                  # Entitas kategori sajian menu
│   │   ├── Event.php                     # Entitas agenda acara kafe
│   │   ├── Facility.php                  # Entitas kartu fasilitas dan foto slider
│   │   ├── GalleryItem.php               # Entitas koleksi visual galeri kafe
│   │   ├── Menu.php                      # Entitas katalog menu sajian & soft delete
│   │   ├── MenuOption.php                # Entitas pilihan varian (panas/dingin, level gula)
│   │   ├── OperatingHour.php             # Entitas jadwal jam buka 7 hari kafe
│   │   ├── Order.php                     # Entitas induk transaksi pesanan pelanggan
│   │   ├── OrderItem.php                 # Entitas baris item rincian pesanan
│   │   ├── Reservation.php               # Entitas data reservasi meja tamu
│   │   ├── ReservationField.php          # Entitas definisi kolom Form Builder
│   │   ├── Setting.php                   # Entitas konfigurasi dinamis key-value kafe
│   │   ├── Table.php                     # Entitas master meja kafe fisik
│   │   ├── Testimonial.php               # Entitas ulasan dan testimoni pelanggan
│   │   ├── User.php                      # Entitas pengguna (admin, kasir, user)
│   │   └── ZendoDelivery.php             # Entitas logistik pengantaran & koordinat kurir
│   ├── Providers/                        # Penyedia layanan booting framework aplikasi
│   └── Support/                          # Modul bantuan khusus (helper utility)
│       ├── FileUploadHelper.php          # Utilitas validasi izin folder upload publik
│       └── TransactionReportPdfGenerator.php # Generator biner berkas PDF 1.4 Native
├── config/                               # Berkas konfigurasi terpusat aplikasi (auth, database, session)
├── database/                             # Skema basis data, seeder, dan factory
│   ├── migrations/                       # 32 berkas migrasi penentu skema tabel fisik
│   └── seeders/                          # Pengisi data awal bawaan (admin, kasir, menu)
├── public/                               # Titik masuk publik web server (Document Root)
│   ├── css/                              # Lembar gaya CSS kompilasi & tema Nucomu
│   ├── js/                               # Berkas JavaScript sisi klien (Vanilla JS)
│   ├── uploads/                          # Direktori simpanan berkas dinamis yang diunggah
│   ├── vendor/leaflet/                   # Berkas fisik pustaka Leaflet.js 1.9.4 & CSS
│   └── index.php                         # Front controller penerima seluruh request HTTP
├── resources/                            # Aset mentah frontend dan antarmuka
│   ├── css/app.css                       # Berkas styling sumber Tailwind CSS v4
│   ├── js/app.js                         # Inisialisasi skrip klien frontend
│   └── views/                            # 82 berkas templat antarmuka Blade
│       ├── admin/                        # Antarmuka panel kontrol administrator
│       ├── components/                   # Komponen Blade berulang (cart-drawer, navbar, footer)
│       ├── home/                         # Beranda publik dan seksi modulnya
│       ├── kasir/                        # Antarmuka POS, login kasir, dan struk termal
│       ├── layouts/                      # Kerangka layout induk (app, admin, kasir)
│       ├── menu/                         # Antarmuka katalog menu publik
│       └── zendo/                        # Antarmuka pelacakan live GPS kurir & pelanggan
├── routes/                               # Pendaftaran rute URL aplikasi
│   ├── console.php                       # Perintah Artisan berbasis konsol CLI
│   └── web.php                           # 158 rute web HTTP seluruh modul aplikasi
└── storage/                              # Berkas penyimpanan sementara (cache, session, log)
```

---

## 2. Dekomposisi Fungsional Modul
Tabel berikut memetakan dekomposisi fungsional sistem dari modul tingkat atas, submodul, fungsi spesifik, hingga berkas pengendali (Controller), model data (Model), dan antarmuka (View):

| Modul Utama | Submodul | Fungsi Spesifik | Controller Terkait | Model Terkait | View Blade Terkait |
|---|---|---|---|---|---|
| **Autentikasi** | Login Admin | Autentikasi staf admin via form kredensial | `Admin\AuthController@showLogin, login` | `User` | `admin.login` |
| | Logout Admin | Pembersihan sesi & revocasi cookie admin | `Admin\AuthController@logout` | `User` | `admin.login` |
| | Login Kasir | Autentikasi cepat staf kasir menggunakan password | `KasirController@showLogin, login` | `User` | `kasir.login` |
| | Logout Kasir | Mengakhiri giliran kerja kasir & hapus cookie | `KasirController@logout` | `User` | `kasir.login` |
| | Proteksi Middleware | Verifikasi token HMAC SHA-256 cookie | `AdminMiddleware`, `KasirMiddleware` | `User` | - |
| **Profil & Konten** | Hero & Beranda | Menampilkan landing page, tagline & nilai kafe | `NucomuController@index` | `Setting` | `home.index`, `home.sections.hero` |
| | About / Our Story | Menampilkan dan mengedit narasi profil kafe | `NucomuController@index`, `Admin\AboutController@update` | `Setting` | `admin.about.index`, `home.sections.about` |
| | Banner Promo | CRUD banner berjadwal & auto-expiry | `Admin\BannerController` | `Banner` | `admin.banners.index`, `admin.banners.create`, `admin.banners.edit` |
| | Fasilitas Kafe | CRUD kartu fasilitas, fitur JSON & foto slider | `Admin\FacilityController` | `Facility` | `admin.facilities.*`, `home.sections.fasilitas` |
| | Galeri Visual | CRUD koleksi foto suasana & kategori kafe | `Admin\GalleryController` | `GalleryItem` | `admin.gallery.index`, `home.sections.gallery` |
| | Agenda & Acara | CRUD event workshop & live music kafe | `Admin\SettingController` | `Event` | `admin.events.index`, `home.sections.events` |
| | Jam Operasional | CRUD jadwal buka-tutup 7 hari kafe | `Admin\SettingController` | `OperatingHour` | `admin.settings.operating-hours`, `components.footer` |
| | Kontak & Peta | Informasi kontak WhatsApp, sosmed & titik peta | `Admin\ContactController` | `Setting` | `admin.contact.index`, `home.sections.contact` |
| **Menu & Sajian** | Katalog Publik | Pencarian, filter kategori & grid menu | `NucomuController@index, menuList` | `Menu`, `Category` | `menu.index`, `home.sections.best-menu` |
| | Modal Varian | Menampilkan detail rasa, foto, & opsi varian | `NucomuController@showMenu` | `Menu`, `MenuOption` | `components.menu-modal` |
| | Master Kategori | CRUD kategori sajian & proteksi relasi menu | `Admin\CategoryController` | `Category`, `Menu` | `admin.categories.index` |
| | Master Menu | CRUD menu makanan/minuman & unggah foto 1:1 | `Admin\MenuController` | `Menu`, `Category` | `admin.menus.form`, `admin.menus.index` |
| | Soft Delete Menu | Penghapusan aman menu & proteksi riwayat order | `Admin\MenuController@destroy` | `Menu`, `OrderItem` | `admin.menus.index` |
| | Pemulihan Menu | Mengembalikan menu terhapus (`restore`) | `Admin\MenuController@restore` | `Menu` | `admin.menus.index` |
| | Hapus Permanen | Force delete menu yang belum pernah dipesan | `Admin\MenuController@forceDelete` | `Menu` | `admin.menus.index` |
| | Kontrol Ketersediaan | Toggle ketersediaan menu (ada / habis) | `Admin\MenuController@toggleAvailable` | `Menu` | `admin.menus.index` |
| **Ulasan & Kepuasan** | Kirim Ulasan | Pelanggan mengirimkan rating 1-5 & foto review | `NucomuController@storeTestimonial` | `Testimonial` | Modal review (`home.sections.review`) |
| | Kurasi Ulasan | Admin menyematkan ulasan terpilih ke beranda | `Admin\TestimonialController` | `Testimonial` | `admin.testimonials.index` |
| | Hapus Ulasan | Moderasi ulasan spam atau konten tidak pantas | `Admin\TestimonialController@destroy` | `Testimonial` | `admin.testimonials.index` |
| **Reservasi Meja** | Form Reservasi | Pelanggan memesan meja dan memilih jadwal | `NucomuController@storeReservation` | `Reservation`, `ReservationField` | `home.sections.reservasi` |
| | Form Builder | Admin menambah kolom dinamis (text, file, dropdown) | `Admin\ReservationController@storeField` | `ReservationField` | `admin.reservations.index` |
| | Verifikasi Reservasi| Admin mengonfirmasi / membatalkan reservasi tamu | `Admin\ReservationController@updateStatus` | `Reservation` | `admin.reservations.index` |
| | Notifikasi WhatsApp| Draf format pesan reservasi terkirim ke WhatsApp | `NucomuController@storeReservation` | `Setting` | Tautan WhatsApp Web |
| **Transaksi & E-Order**| Keranjang Belanja | Pengelolaan item mandiri di sisi klien (Cart Drawer) | JavaScript LocalStorage | `Menu` | `components.cart-drawer` |
| | Mode 1: Dine In | Pesanan meja langsung & penerbitan slip QR | `NucomuController@storeOrder` | `Order`, `OrderItem`, `Table` | `components.cart-drawer`, `admin.orders.qr-detail` |
| | Mode 2: Chat Zendo| Pesanan pesan antar via WhatsApp Zendo | `NucomuController@storeOrder` | `Order`, `OrderItem`, `Setting` | `components.cart-drawer`, Tautan WhatsApp |
| | Mode 3: GPS Antar | Pesanan antar dengan tracking koordinat GPS | `NucomuController@storeOrder` | `Order`, `OrderItem`, `ZendoDelivery` | `components.cart-drawer`, `zendo.tracking` |
| | Pengaturan Mode | Admin mengaktifkan/menonaktifkan 3 mode & QRIS | `Admin\OrderController@updateSettings` | `Setting` | `admin.orders.index` |
| | Penguncian Stok | Mengunci stok menu atomik via `lockForUpdate` | `NucomuController@storeOrder` | `Menu` | - |
| **Kasir POS** | Katalog POS | Grid menu penjualan di kasir | `KasirController@index` | `Menu`, `Category` | `kasir.index` |
| | Scan Slip QR | Memindai slip pesanan barcode QR NCM-xxx | `KasirController@lookupOrder` | `Order`, `OrderItem` | Modal scanner (`kasir.index`) |
| | Pelunasan Kasir | Menerima uang, hitung kembalian, set lunas | `KasirController@updateOrderStatus` | `Order` | `kasir.index` |
| | Cetak Struk | Mencetak struk bukti pembayaran thermal kasir | `KasirController@receipt` | `Order`, `OrderItem`, `Setting` | `kasir.receipt` |
| | Stok Cepat | Kasir mengubah stok fisik tanpa buka menu admin | `KasirController@updateStock` | `Menu` | Modal stok (`kasir.index`) |
| **Logistik Kurir** | Verifikasi PIN | Kurir membuka sesi antar via PIN & ikat browser | `ZendoTrackingController@verifyPinAndBindDevice`| `ZendoDelivery`, `Setting` | Modal PIN (`zendo.tracking`) |
| | Streaming GPS | Kurir mengirimkan koordinat real-time berkala | `ZendoTrackingController@updateLocation` | `ZendoDelivery` | Skrip GPS (`zendo.tracking`) |
| | Anti-Fake GPS | Server memvalidasi kecepatan & akurasi sinyal | `ZendoTrackingController@updateLocation` | `ZendoDelivery` | JSON Alert Response |
| | Live Map Tracking | Pelanggan memantau pergerakan kurir di peta | `ZendoTrackingController@trackDelivery` | `ZendoDelivery`, `Order` | Peta Leaflet (`zendo.tracking`) |
| | Status Pengantaran| Kurir mengubah status pengantaran hingga selesai | `ZendoTrackingController@updateStatus` | `ZendoDelivery`, `Order` | `zendo.tracking` |
| **Dasbor & Laporan** | Dasbor Metrik | Pantau omzet, pesanan, reservasi & top menu | `Admin\DashboardController@index` | `Order`, `OrderItem`, `Reservation` | `admin.dashboard` |
| | Grafik Tren Omzet | Grafik penjualan harian, mingguan, & bulanan | `Admin\DashboardController` | `Order`, `Setting` | `admin.dashboard` |
| | Rekapitulasi PDF | Ekspor laporan penjualan biner PDF 1.4 Native | `Admin\OrderController@exportRecap` | `Order`, `OrderItem`, `Setting` | `TransactionReportPdfGenerator` |
| | Rekapitulasi CSV | Ekspor rekaman data transaksi format CSV Excel | `Admin\OrderController@exportRecap` | `Order`, `OrderItem` | Stream text/csv UTF-8 BOM |
| **Pengaturan Sistem** | Master Meja | CRUD nomor meja kafe, kapasitas & zona | `Admin\SettingController` | `Table` | `admin.settings.tables` |
| | Profil Admin | Mengubah nama, email, avatar & kata sandi admin | `Admin\ProfileController` | `User` | `admin.profile.index` |
| | Notifikasi Polling| Polling notifikasi real-time pesanan & reservasi | `Admin\NotificationController` | `Order`, `Reservation` | `admin.notifications.index` |
| | Panduan Bantuan | Halaman dokumentasi operasional sistem admin | `Admin\HelpController@index` | - | `admin.help.index` |

---

## 3. Lingkungan Implementasi Sistem
Berdasarkan konfigurasi proyek sebenarnya (`composer.json`, `package.json`, dan file konfigurasi runtime), berikut adalah spesifikasi perangkat lunak dan perangkat keras yang dibutuhkan:

### 3.1 Kebutuhan Perangkat Lunak (*Software Environment*)
| Komponen | Lingkungan Pengembangan (*Development*) | Lingkungan Operasional (*Production*) | Bukti Konfigurasi / Sumber |
|---|---|---|---|
| **Sistem Operasi** | Windows 10 / 11 64-bit | Linux Ubuntu Server 22.04 LTS / Debian 12 | Lingkungan kerja Windows Laragon |
| **Web Server** | Nginx / Apache 2.4 (Laragon Suite) / PHP Built-in | Nginx / Apache dengan PHP-FPM | `public/index.php` front controller |
| **Bahasa Pemrograman** | PHP 8.5.9 (ZTS Visual C++ 2022 x64) | PHP 8.3+ atau PHP 8.4+ 64-bit | `composer.json` (`"php": "^8.3"`) |
| **Ekstensi PHP Wajib** | `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` | Sama dengan development | Kebutuhan Laravel 13, pemrosesan gambar, & hash |
| **Framework PHP** | Laravel Framework `v13.35.0` | Laravel Framework `v13.35.0` | `composer.lock` |
| **Basis Data (RDBMS)** | MySQL 8.0.30 / MariaDB 10.4 (atau SQLite 3) | MySQL 8.0+ / MariaDB 10.5+ | `config/database.php` |
| **Build Tool Frontend** | Vite `v8.0.0` & `@tailwindcss/vite ^4.0.0` | Berkas terkompilasi statis di `public/` | `package.json` |
| **CSS Framework** | Tailwind CSS `v4.0.0` | Minified CSS di `public/build/assets/` | `package.json` |
| **Pustaka Pemetaan** | Leaflet.js `v1.9.4` (Lokal di `public/vendor/`) | Leaflet.js `v1.9.4` + OpenStreetMap Tiles | `public/vendor/leaflet/leaflet.js` |
| **Peramban (*Browser*)** | Google Chrome 120+, Mozilla Firefox 120+, Edge | Modern Chromium / Safari Mobile / Webview | Dukungan Geolocation API & LocalStorage |

### 3.2 Kebutuhan Perangkat Keras (*Hardware Requirements*)

#### A. Komputer Server / Lingkungan Pengembang (*Development / Host Server*)
| Komponen Perangkat Keras | Spesifikasi Minimum | Spesifikasi Rekomendasi |
|---|---|---|
| **Prosesor (CPU)** | Intel Core i3 Gen 8 / AMD Ryzen 3 (2 Core, 4 Thread @ 2.0 GHz) | Intel Core i5 Gen 11 / AMD Ryzen 5 (6 Core, 12 Thread @ 3.0 GHz) |
| **Memori Utama (RAM)** | 4 GB DDR4 | 8 GB s.d. 16 GB DDR4 |
| **Penyimpanan (*Storage*)** | 20 GB ruang kosong (HDD) | 50 GB ruang kosong (SSD NVMe) untuk performa I/O cepat |
| **Konektivitas Jaringan** | Internet 10 Mbps (untuk unduh pustaka & peta OSM) | Internet 25 Mbps stabil dengan IP Publik / Domain Kafe |

#### B. Komputer / Tablet Kasir Kafe (*POS Terminal*)
| Komponen Perangkat Keras | Spesifikasi Minimum / Rekomendasi | Keterangan |
|---|---|---|
| **Perangkat** | PC Desktop / Laptop / Android Tablet (Layar minimal 10 inci) | Menjalankan peramban web kasir (`/kasir`) |
| **Memori (RAM)** | 2 GB RAM | Mampu menjalankan browser Chrome tanpa lag |
| **Printer Struk** | Thermal Printer 58mm atau 80mm (USB / Bluetooth / ESC-POS) | Mencetak struk fisik bukti pembayaran kasir |
| **Pemindai Barcode / QR** | Kamera Web / 2D Barcode Scanner USB / Kamera Tablet | Memindai kode QR dari layar smartphone pelanggan |

#### C. Smartphone Kurir Zendo (*Courier Mobile Device*)
| Komponen Perangkat Keras | Spesifikasi Minimum / Rekomendasi | Keterangan |
|---|---|---|
| **Perangkat** | Smartphone Android (OS 8.0+) atau Apple iOS (iOS 13+) | Menjalankan halaman web responsif `/zendo/lacak/{token}` |
| **Sensor GPS** | Built-in A-GPS / GLONASS | Wajib akurasi tinggi (akurasi <= 100 meter agar lolos Anti-Fake GPS) |
| **Koneksi Seluler** | 4G LTE / 5G dengan kuota data aktif | Untuk streaming interval koordinat GPS kurir ke server |
