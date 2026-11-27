# TAHAP 0: INVENTARIS PROYEK
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Tanggal Ekstraksi:** 2026-11-24
**Status Mode:** Analisis Ekstraksi Fakta Kode (Read-Only)

---

## 1. Versi Perangkat Lunak & Pustaka Dependensi Sebenarnya

### 1.1 Bahasa Pemrograman & Runtime
- **PHP Runtime Terpasang:** PHP 8.5.9 (`D:\laragon\bin\php\php-8.5.9-Win32-vs17-x64\php.exe`)
- **PHP Requirement (`composer.json`):** `^8.3`

### 1.2 Dependensi PHP (`composer.json` & `composer.lock`)
| Paket | Versi di `composer.json` | Versi Terkunci (`composer.lock`) | Kategori | Keterangan / Fungsi |
|---|---|---|---|---|
| `laravel/framework` | `^13.17` | `v13.35.0` | Production | Framework utama aplikasi web |
| `laravel/tinker` | `^3.0` | `v3.0.2` | Production | REPL CLI debugging Laravel |
| `fakerphp/faker` | `^1.23` | `v1.24.1` | Development | Generator data dummy (factory/seeder) |
| `laravel/boost` | `^2.10` | `v2.10.3` | Development | Ekstensi toolchain agen Laravel Boost |
| `laravel/pail` | `^1.2.5` | `v1.2.7` | Development | Tool log tailing real-time |
| `laravel/pao` | `^1.0.6` | `v1.1.5` | Development | Tool pembantu artisan |
| `laravel/pint` | `^1.27` | `v1.32.1` | Development | Code formatter standar PSR-12 / Laravel |
| `mockery/mockery` | `^1.6` | `1.6.15` | Development | Library mocking untuk pengujian |
| `nunomaduro/collision` | `^8.6` | `v8.9.5` | Development | Error reporting CLI handler |
| `phpunit/phpunit` | `^12.5.12` | `12.5.38` | Development | Framework automated unit & feature test |

> [!IMPORTANT]
> **Temuan Versi Framework:** Laporan sebelumnya menyebutkan 'Laravel 11'. Faktanya `composer.lock` mengunci `laravel/framework v13.35.0` (kebutuhan `^13.17`). Perlu diselaraskan pada laporan resmi.

### 1.3 Dependensi JavaScript & Frontend (`package.json`)
| Paket | Versi (`package.json`) | Kategori | Keterangan / Fungsi |
|---|---|---|---|
| `@tailwindcss/vite` | `^4.0.0` | devDependencies | Pustaka aset frontend / build tool |
| `concurrently` | `^10.0.3` | devDependencies | Pustaka aset frontend / build tool |
| `laravel-vite-plugin` | `^3.1` | devDependencies | Pustaka aset frontend / build tool |
| `tailwindcss` | `^4.0.0` | devDependencies | Pustaka aset frontend / build tool |
| `vite` | `^8.0.0` | devDependencies | Pustaka aset frontend / build tool |
| `@laravel/multiplex` | `^0.4.1` | optionalDependencies | Utilitas Laravel CLI |

> [!NOTE]
> **Status Alpine.js:** Pustaka `alpinejs` **TIDAK DITEMUKAN** di `package.json` maupun di aset vendor. Frontend sepenuhnya menggunakan **Vanilla JavaScript**.

### 1.4 Pustaka Peta / Geolokasi (Leaflet.js)
- **Versi:** `1.9.4 (Local vendor file)`
- **Lokasi File Fisik:** `public/vendor/leaflet/leaflet.js` & `public/vendor/leaflet/leaflet.css`
- **Fallback CDN:** `https://unpkg.com/leaflet@1.9.4/dist/leaflet.js` (digunakan pada view pelacakan kurir `tracking.blade.php`)
- **Penyedia Peta:** OpenStreetMap Tile Layer (`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`)

---

## 2. Struktur Direktori Proyek
```
umkm/
├── app/                     # Logika inti aplikasi (MVC)
│   ├── Http/
│   │   ├── Controllers/     # 25 Controller (Customer, Kasir POS, Admin CRUD)
│   │   └── Middleware/      # 2 Custom Middleware (AdminMiddleware, KasirMiddleware)
│   ├── Models/              # 17 Eloquent Model representasi entitas data
│   └── Providers/           # Service Provider aplikasi
├── bootstrap/               # File booting framework (app.php, providers.php)
├── config/                  # Konfigurasi aplikasi (app, auth, database, session, dll.)
├── database/                # Migrations, seeders, factories
│   ├── factories/           # Factory generator data model
│   ├── migrations/          # 32 migration file basis data
│   └── seeders/             # Database seeder (Category, Menu, Table, User, dll.)
├── docs/                    # Dokumentasi rancangan awal (DFD, Sitemap, Diagram Visual)
├── images/                  # Aset gambar galeri
├── lang/                    # Lokalisasi bahasa
├── laporan_data/            # Direktori luaran data ekstraksi fakta laporan SAS
├── public/                  # Document root web server (CSS, JS, vendor Leaflet, index.php)
├── resources/               # Aset mentah frontend & view
│   ├── css/                 # File styling Tailwind / Nucomu
│   ├── js/                  # Script interaktif frontend Vanilla JS
│   └── views/               # 82 Template Blade (Admin, Kasir, Home, Komponen)
├── routes/                  # Definisi rute URL (web.php, console.php)
├── storage/                 # Berkas dinamis (app, logs, cache, uploads)
├── tests/                   # Automated feature & unit tests (PHPUnit)
└── vendor/                  # Pustaka eksternal PHP Composer
```

---

## 3. Daftar Controller (25 File)
| No | Path Controller | Nama Class | Fungsi / Lingkup |
|---|---|---|---|
| 1 | `app/Http/Controllers/CartController.php` | `CartController` | Publik / Kasir / Global |
| 2 | `app/Http/Controllers/Controller.php` | `Controller` | Publik / Kasir / Global |
| 3 | `app/Http/Controllers/HomeController.php` | `HomeController` | Publik / Kasir / Global |
| 4 | `app/Http/Controllers/KasirController.php` | `KasirController` | Publik / Kasir / Global |
| 5 | `app/Http/Controllers/MenuController.php` | `MenuController` | Publik / Kasir / Global |
| 6 | `app/Http/Controllers/NucomuController.php` | `NucomuController` | Publik / Kasir / Global |
| 7 | `app/Http/Controllers/OrderController.php` | `OrderController` | Publik / Kasir / Global |
| 8 | `app/Http/Controllers/PageController.php` | `PageController` | Publik / Kasir / Global |
| 9 | `app/Http/Controllers/ZendoTrackingController.php` | `ZendoTrackingController` | Publik / Kasir / Global |
| 10 | `app/Http/Controllers/Admin/AboutController.php` | `AboutController` | Admin |
| 11 | `app/Http/Controllers/Admin/AuthController.php` | `AuthController` | Admin |
| 12 | `app/Http/Controllers/Admin/BannerController.php` | `BannerController` | Admin |
| 13 | `app/Http/Controllers/Admin/CategoryController.php` | `CategoryController` | Admin |
| 14 | `app/Http/Controllers/Admin/ContactController.php` | `ContactController` | Admin |
| 15 | `app/Http/Controllers/Admin/DashboardController.php` | `DashboardController` | Admin |
| 16 | `app/Http/Controllers/Admin/FacilityController.php` | `FacilityController` | Admin |
| 17 | `app/Http/Controllers/Admin/GalleryController.php` | `GalleryController` | Admin |
| 18 | `app/Http/Controllers/Admin/HelpController.php` | `HelpController` | Admin |
| 19 | `app/Http/Controllers/Admin/MenuController.php` | `MenuController` | Admin |
| 20 | `app/Http/Controllers/Admin/NotificationController.php` | `NotificationController` | Admin |
| 21 | `app/Http/Controllers/Admin/OrderController.php` | `OrderController` | Admin |
| 22 | `app/Http/Controllers/Admin/ProfileController.php` | `ProfileController` | Admin |
| 23 | `app/Http/Controllers/Admin/ReservationController.php` | `ReservationController` | Admin |
| 24 | `app/Http/Controllers/Admin/SettingController.php` | `SettingController` | Admin |
| 25 | `app/Http/Controllers/Admin/TestimonialController.php` | `TestimonialController` | Admin |

---

## 4. Daftar Model Eloquent (17 File)
| No | Path Model | Nama Model | Tabel Terkait | Keterangan |
|---|---|---|---|---|
| 1 | `app/Models/Banner.php` | `Banner` | `banners` | Model entitas data |
| 2 | `app/Models/Category.php` | `Category` | `categorys` | Model entitas data |
| 3 | `app/Models/Event.php` | `Event` | `events` | Model entitas data |
| 4 | `app/Models/Facility.php` | `Facility` | `facilitys` | Model entitas data |
| 5 | `app/Models/GalleryItem.php` | `GalleryItem` | `gallery_items` | Model entitas data |
| 6 | `app/Models/Menu.php` | `Menu` | `menus` | Model entitas data |
| 7 | `app/Models/MenuOption.php` | `MenuOption` | `menu_options` | Model entitas data |
| 8 | `app/Models/OperatingHour.php` | `OperatingHour` | `operating_hours` | Model entitas data |
| 9 | `app/Models/Order.php` | `Order` | `orders` | Model entitas data |
| 10 | `app/Models/OrderItem.php` | `OrderItem` | `order_items` | Model entitas data |
| 11 | `app/Models/Reservation.php` | `Reservation` | `reservations` | Model entitas data |
| 12 | `app/Models/ReservationField.php` | `ReservationField` | `reservation_fields` | Model entitas data |
| 13 | `app/Models/Setting.php` | `Setting` | `settings` | Model entitas data |
| 14 | `app/Models/Table.php` | `Table` | `tables` | Model entitas data |
| 15 | `app/Models/Testimonial.php` | `Testimonial` | `testimonials` | Model entitas data |
| 16 | `app/Models/User.php` | `User` | `users` | Model entitas data |
| 17 | `app/Models/ZendoDelivery.php` | `ZendoDelivery` | `zendo_deliveries` | Model entitas data |

---

## 5. Daftar Middleware (2 File)
| No | Path Middleware | Nama Class | Peran / Fungsi Keamanan |
|---|---|---|---|
| 1 | `app/Http/Middleware/AdminMiddleware.php` | `AdminMiddleware` | Verifikasi sesi & cookie admin via HMAC SHA-256 |
| 2 | `app/Http/Middleware/KasirMiddleware.php` | `KasirMiddleware` | Verifikasi sesi kasir & status shift kasir aktif |

---

## 6. Daftar Views Blade (82 File)
Views dikelompokkan berdasarkan subfolder modul:
### Subfolder: `resources/views/admin` (31 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/admin/dashboard.blade.php` | Admin UI |
| 2 | `resources/views/admin/about/index.blade.php` | Admin UI |
| 3 | `resources/views/admin/auth/login.blade.php` | Admin UI |
| 4 | `resources/views/admin/banners/create.blade.php` | Admin UI |
| 5 | `resources/views/admin/banners/edit.blade.php` | Admin UI |
| 6 | `resources/views/admin/banners/index.blade.php` | Admin UI |
| 7 | `resources/views/admin/categories/index.blade.php` | Admin UI |
| 8 | `resources/views/admin/contact/index.blade.php` | Admin UI |
| 9 | `resources/views/admin/events/index.blade.php` | Admin UI |
| 10 | `resources/views/admin/facilities/create.blade.php` | Admin UI |
| 11 | `resources/views/admin/facilities/edit.blade.php` | Admin UI |
| 12 | `resources/views/admin/facilities/index.blade.php` | Admin UI |
| 13 | `resources/views/admin/gallery/index.blade.php` | Admin UI |
| 14 | `resources/views/admin/help/index.blade.php` | Admin UI |
| 15 | `resources/views/admin/menus/create.blade.php` | Admin UI |
| 16 | `resources/views/admin/menus/edit.blade.php` | Admin UI |
| 17 | `resources/views/admin/menus/form.blade.php` | Admin UI |
| 18 | `resources/views/admin/menus/index.blade.php` | Admin UI |
| 19 | `resources/views/admin/notifications/index.blade.php` | Admin UI |
| 20 | `resources/views/admin/orders/index.blade.php` | Admin UI |
| 21 | `resources/views/admin/orders/qr-detail.blade.php` | Admin UI |
| 22 | `resources/views/admin/orders/receipt.blade.php` | Admin UI |
| 23 | `resources/views/admin/orders/report-print.blade.php` | Admin UI |
| 24 | `resources/views/admin/orders/scan.blade.php` | Admin UI |
| 25 | `resources/views/admin/orders/show.blade.php` | Admin UI |
| 26 | `resources/views/admin/preview/index.blade.php` | Admin UI |
| 27 | `resources/views/admin/profile/index.blade.php` | Admin UI |
| 28 | `resources/views/admin/reservations/index.blade.php` | Admin UI |
| 29 | `resources/views/admin/settings/index.blade.php` | Admin UI |
| 30 | `resources/views/admin/testimonials/index.blade.php` | Admin UI |
| 31 | `resources/views/admin/zendo/index.blade.php` | Admin UI |

### Subfolder: `resources/views/components` (10 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/components/cart-drawer.blade.php` | Components UI |
| 2 | `resources/views/components/field.blade.php` | Components UI |
| 3 | `resources/views/components/footer.blade.php` | Components UI |
| 4 | `resources/views/components/form-actions.blade.php` | Components UI |
| 5 | `resources/views/components/form-section.blade.php` | Components UI |
| 6 | `resources/views/components/help-panel.blade.php` | Components UI |
| 7 | `resources/views/components/media-picker.blade.php` | Components UI |
| 8 | `resources/views/components/navbar.blade.php` | Components UI |
| 9 | `resources/views/components/page-header.blade.php` | Components UI |
| 10 | `resources/views/components/upload-guide.blade.php` | Components UI |

### Subfolder: `resources/views/errors` (1 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/errors/419.blade.php` | Errors UI |

### Subfolder: `resources/views/home` (16 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/home/index.blade.php` | Home UI |
| 2 | `resources/views/home/modals/lightbox-modal.blade.php` | Home UI |
| 3 | `resources/views/home/modals/reservation-modal.blade.php` | Home UI |
| 4 | `resources/views/home/modals/service-detail-modal.blade.php` | Home UI |
| 5 | `resources/views/home/partials/intro.blade.php` | Home UI |
| 6 | `resources/views/home/sections/about.blade.php` | Home UI |
| 7 | `resources/views/home/sections/all-menu.blade.php` | Home UI |
| 8 | `resources/views/home/sections/best-menu.blade.php` | Home UI |
| 9 | `resources/views/home/sections/contact.blade.php` | Home UI |
| 10 | `resources/views/home/sections/gallery.blade.php` | Home UI |
| 11 | `resources/views/home/sections/hero-default-slides.blade.php` | Home UI |
| 12 | `resources/views/home/sections/hero.blade.php` | Home UI |
| 13 | `resources/views/home/sections/layanan.blade.php` | Home UI |
| 14 | `resources/views/home/sections/reviews.blade.php` | Home UI |
| 15 | `resources/views/home/sections/showcase.blade.php` | Home UI |
| 16 | `resources/views/home/sections/facilities/gallery.blade.php` | Home UI |

### Subfolder: `resources/views/kasir` (2 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/kasir/index.blade.php` | Kasir UI |
| 2 | `resources/views/kasir/login.blade.php` | Kasir UI |

### Subfolder: `resources/views/layouts` (2 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/layouts/admin.blade.php` | Layouts UI |
| 2 | `resources/views/layouts/app.blade.php` | Layouts UI |

### Subfolder: `resources/views/menu` (1 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/menu/all.blade.php` | Menu UI |

### Subfolder: `resources/views/nucomu` (3 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/nucomu/index.blade.php` | Nucomu UI |
| 2 | `resources/views/nucomu/order-slip.blade.php` | Nucomu UI |
| 3 | `resources/views/nucomu/order.blade.php` | Nucomu UI |

### Subfolder: `resources/views/public` (13 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/public/cart.blade.php` | Public UI |
| 2 | `resources/views/public/contact.blade.php` | Public UI |
| 3 | `resources/views/public/events.blade.php` | Public UI |
| 4 | `resources/views/public/facilities.blade.php` | Public UI |
| 5 | `resources/views/public/gallery.blade.php` | Public UI |
| 6 | `resources/views/public/home.blade.php` | Public UI |
| 7 | `resources/views/public/menu-detail.blade.php` | Public UI |
| 8 | `resources/views/public/order-confirmation.blade.php` | Public UI |
| 9 | `resources/views/public/reservation.blade.php` | Public UI |
| 10 | `resources/views/public/services.blade.php` | Public UI |
| 11 | `resources/views/public/track-order.blade.php` | Public UI |
| 12 | `resources/views/public/partials/footer.blade.php` | Public UI |
| 13 | `resources/views/public/partials/navbar.blade.php` | Public UI |

### Subfolder: `resources/views/root` (1 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/welcome.blade.php` | Root UI |

### Subfolder: `resources/views/zendo` (2 template)
| No | Path View Blade | Keterangan Modul |
|---|---|---|
| 1 | `resources/views/zendo/courier.blade.php` | Zendo UI |
| 2 | `resources/views/zendo/track.blade.php` | Zendo UI |


---

## 7. Daftar Migration Basis Data (32 File)
| No | Nama File Migration | Target Tabel / Aksi |
|---|---|---|
| 1 | `database/migrations/0001_01_01_000000_create_users_table.php` | Skema migrasi |
| 2 | `database/migrations/0001_01_01_000001_create_cache_table.php` | Skema migrasi |
| 3 | `database/migrations/0001_01_01_000002_create_jobs_table.php` | Skema migrasi |
| 4 | `database/migrations/2026_10_09_000001_create_categories_table.php` | Skema migrasi |
| 5 | `database/migrations/2026_10_09_000002_create_menus_table.php` | Skema migrasi |
| 6 | `database/migrations/2026_10_09_000003_create_menu_options_table.php` | Skema migrasi |
| 7 | `database/migrations/2026_10_09_000004_create_tables_table.php` | Skema migrasi |
| 8 | `database/migrations/2026_10_09_000005_create_orders_table.php` | Skema migrasi |
| 9 | `database/migrations/2026_10_09_000006_create_order_items_table.php` | Skema migrasi |
| 10 | `database/migrations/2026_10_09_000007_create_reservations_table.php` | Skema migrasi |
| 11 | `database/migrations/2026_10_09_000008_create_settings_table.php` | Skema migrasi |
| 12 | `database/migrations/2026_10_09_000009_create_operating_hours_table.php` | Skema migrasi |
| 13 | `database/migrations/2026_10_09_000010_create_facilities_table.php` | Skema migrasi |
| 14 | `database/migrations/2026_10_09_000011_create_gallery_items_table.php` | Skema migrasi |
| 15 | `database/migrations/2026_10_09_000012_create_testimonials_table.php` | Skema migrasi |
| 16 | `database/migrations/2026_10_09_000013_create_events_table.php` | Skema migrasi |
| 17 | `database/migrations/2026_10_09_000014_alter_categories_add_slug_columns.php` | Skema migrasi |
| 18 | `database/migrations/2026_10_21_091910_alter_gallery_items_add_nucomu_fields.php` | Skema migrasi |
| 19 | `database/migrations/2026_10_21_091910_alter_testimonials_add_nucomu_fields.php` | Skema migrasi |
| 20 | `database/migrations/2026_10_25_133754_add_source_to_testimonials_table.php` | Skema migrasi |
| 21 | `database/migrations/2026_10_25_181624_create_zendo_deliveries_table.php` | Skema migrasi |
| 22 | `database/migrations/2026_11_02_035907_create_banners_table.php` | Skema migrasi |
| 23 | `database/migrations/2026_11_02_050005_alter_facilities_add_image_and_description_columns.php` | Skema migrasi |
| 24 | `database/migrations/2026_11_02_064358_update_facilities_for_carousel_slides.php` | Skema migrasi |
| 25 | `database/migrations/2026_11_02_072456_alter_gallery_items_add_is_active_column.php` | Skema migrasi |
| 26 | `database/migrations/2026_11_02_074222_alter_testimonials_add_is_pinned_column.php` | Skema migrasi |
| 27 | `database/migrations/2026_11_02_075352_create_reservation_fields_table.php` | Skema migrasi |
| 28 | `database/migrations/2026_11_02_075359_alter_reservations_add_form_data_column.php` | Skema migrasi |
| 29 | `database/migrations/2026_11_02_122004_add_avatar_to_users_table.php` | Skema migrasi |
| 30 | `database/migrations/2026_11_02_173900_alter_orders_add_delivery_and_form_data_columns.php` | Skema migrasi |
| 31 | `database/migrations/2026_11_03_192500_add_layout_mode_to_banners_table.php` | Skema migrasi |
| 32 | `database/migrations/2026_11_13_164612_add_image_to_categories_table.php` | Skema migrasi |

---

## 8. Inventaris Tabel Fisik Basis Data
Berdasarkan analisis file migration (`Schema::create`), terdapat **24 tabel** yang didefinisikan secara fisik:

### 8.1 Tabel Domain / Aplikasi (16 Tabel)
| No | Nama Tabel | Migration Pencipta | Model Eloquent Terkait |
|---|---|---|---|
| 1 | `categories` | `2026_10_09_000001_create_categories_table.php` | Terhubung dengan Model domain |
| 2 | `menus` | `2026_10_09_000002_create_menus_table.php` | Terhubung dengan Model domain |
| 3 | `menu_options` | `2026_10_09_000003_create_menu_options_table.php` | Terhubung dengan Model domain |
| 4 | `tables` | `2026_10_09_000004_create_tables_table.php` | Terhubung dengan Model domain |
| 5 | `orders` | `2026_10_09_000005_create_orders_table.php` | Terhubung dengan Model domain |
| 6 | `order_items` | `2026_10_09_000006_create_order_items_table.php` | Terhubung dengan Model domain |
| 7 | `reservations` | `2026_10_09_000007_create_reservations_table.php` | Terhubung dengan Model domain |
| 8 | `settings` | `2026_10_09_000008_create_settings_table.php` | Terhubung dengan Model domain |
| 9 | `operating_hours` | `2026_10_09_000009_create_operating_hours_table.php` | Terhubung dengan Model domain |
| 10 | `facilities` | `2026_10_09_000010_create_facilities_table.php` | Terhubung dengan Model domain |
| 11 | `gallery_items` | `2026_10_09_000011_create_gallery_items_table.php` | Terhubung dengan Model domain |
| 12 | `testimonials` | `2026_10_09_000012_create_testimonials_table.php` | Terhubung dengan Model domain |
| 13 | `events` | `2026_10_09_000013_create_events_table.php` | Terhubung dengan Model domain |
| 14 | `zendo_deliveries` | `2026_10_25_181624_create_zendo_deliveries_table.php` | Terhubung dengan Model domain |
| 15 | `banners` | `2026_11_02_035907_create_banners_table.php` | Terhubung dengan Model domain |
| 16 | `reservation_fields` | `2026_11_02_075352_create_reservation_fields_table.php` | Terhubung dengan Model domain |

### 8.2 Tabel Pengguna & Autentikasi (1 Tabel)
| No | Nama Tabel | Migration Pencipta | Model Eloquent Terkait |
|---|---|---|---|
| 1 | `users` | `0001_01_01_000000_create_users_table.php` | `App\Models\User` |

> [!TIP]
> **Penjelasan Klaim '17 Tabel Fisik' pada Laporan:**
> Jika 16 tabel domain di atas digabungkan dengan tabel `users`, totalnya tepat **17 tabel entitas aplikasi** yang masing-masing berpasangan 1:1 dengan 17 Model Eloquent di `app/Models/`.

### 8.3 Tabel Infrastruktur Framework Laravel (7 Tabel)
| No | Nama Tabel | Migration Pencipta | Fungsi Sistem |
|---|---|---|---|
| 1 | `password_reset_tokens` | `0001_01_01_000000_create_users_table.php` | Tabel internal bawaan Laravel framework |
| 2 | `sessions` | `0001_01_01_000000_create_users_table.php` | Tabel internal bawaan Laravel framework |
| 3 | `cache` | `0001_01_01_000001_create_cache_table.php` | Tabel internal bawaan Laravel framework |
| 4 | `cache_locks` | `0001_01_01_000001_create_cache_table.php` | Tabel internal bawaan Laravel framework |
| 5 | `jobs` | `0001_01_01_000002_create_jobs_table.php` | Tabel internal bawaan Laravel framework |
| 6 | `job_batches` | `0001_01_01_000002_create_jobs_table.php` | Tabel internal bawaan Laravel framework |
| 7 | `failed_jobs` | `0001_01_01_000002_create_jobs_table.php` | Tabel internal bawaan Laravel framework |

---

## 9. Daftar Seluruh Rute Aplikasi (158 Rute)
| No | Metode | URI | Nama Rute | Controller @ Method / Action | Middleware |
|---|---|---|---|---|---|
| 1 | `GET|HEAD` | `/` | `home` | `App\Http\Controllers\NucomuController@index` | `web` |
| 2 | `POST` | `_boost/browser-logs` | `boost.browser-logs` | `Closure` | `-` |
| 3 | `GET|HEAD` | `admin` | `admin.` | `Closure` | `web, admin` |
| 4 | `GET|HEAD` | `admin/about` | `admin.about.index` | `App\Http\Controllers\Admin\AboutController@index` | `web, admin` |
| 5 | `POST` | `admin/about` | `admin.about.update` | `App\Http\Controllers\Admin\AboutController@update` | `web, admin` |
| 6 | `GET|HEAD` | `admin/banner` | `admin.banners.index` | `App\Http\Controllers\Admin\BannerController@index` | `web, admin` |
| 7 | `POST` | `admin/banner` | `admin.banners.store` | `App\Http\Controllers\Admin\BannerController@store` | `web, admin` |
| 8 | `GET|HEAD` | `admin/banner/expiring-check` | `admin.banners.checkExpiring` | `App\Http\Controllers\Admin\BannerController@checkExpiring` | `web, admin` |
| 9 | `GET|HEAD` | `admin/banner/tambah` | `admin.banners.create` | `App\Http\Controllers\Admin\BannerController@create` | `web, admin` |
| 10 | `PUT` | `admin/banner/{banner}` | `admin.banners.update` | `App\Http\Controllers\Admin\BannerController@update` | `web, admin` |
| 11 | `DELETE` | `admin/banner/{banner}` | `admin.banners.destroy` | `App\Http\Controllers\Admin\BannerController@destroy` | `web, admin` |
| 12 | `GET|HEAD` | `admin/banner/{banner}/edit` | `admin.banners.edit` | `App\Http\Controllers\Admin\BannerController@edit` | `web, admin` |
| 13 | `POST` | `admin/banner/{banner}/perpanjang` | `admin.banners.extend` | `App\Http\Controllers\Admin\BannerController@extendDuration` | `web, admin` |
| 14 | `POST` | `admin/banner/{banner}/toggle` | `admin.banners.toggle` | `App\Http\Controllers\Admin\BannerController@toggleActive` | `web, admin` |
| 15 | `GET|HEAD` | `admin/bantuan` | `admin.` | `App\Http\Controllers\Admin\HelpController@index` | `web, admin` |
| 16 | `GET|HEAD` | `admin/contact` | `admin.contact.index` | `App\Http\Controllers\Admin\ContactController@index` | `web, admin` |
| 17 | `POST` | `admin/contact` | `admin.contact.update` | `App\Http\Controllers\Admin\ContactController@update` | `web, admin` |
| 18 | `GET|HEAD` | `admin/dashboard` | `admin.dashboard` | `App\Http\Controllers\Admin\DashboardController@index` | `web, admin` |
| 19 | `GET|HEAD` | `admin/event` | `admin.events.index` | `App\Http\Controllers\Admin\SettingController@events` | `web, admin` |
| 20 | `POST` | `admin/event` | `admin.events.store` | `App\Http\Controllers\Admin\SettingController@storeEvent` | `web, admin` |
| 21 | `DELETE` | `admin/event/{event}` | `admin.events.destroy` | `App\Http\Controllers\Admin\SettingController@destroyEvent` | `web, admin` |
| 22 | `GET|HEAD` | `admin/fasilitas` | `admin.facilities.index` | `App\Http\Controllers\Admin\FacilityController@index` | `web, admin` |
| 23 | `POST` | `admin/fasilitas` | `admin.facilities.store` | `App\Http\Controllers\Admin\FacilityController@store` | `web, admin` |
| 24 | `POST` | `admin/fasilitas/reorder` | `admin.facilities.reorder` | `App\Http\Controllers\Admin\FacilityController@reorder` | `web, admin` |
| 25 | `GET|HEAD` | `admin/fasilitas/tambah` | `admin.facilities.create` | `App\Http\Controllers\Admin\FacilityController@create` | `web, admin` |
| 26 | `PUT` | `admin/fasilitas/{facility}` | `admin.facilities.update` | `App\Http\Controllers\Admin\FacilityController@update` | `web, admin` |
| 27 | `DELETE` | `admin/fasilitas/{facility}` | `admin.facilities.destroy` | `App\Http\Controllers\Admin\FacilityController@destroy` | `web, admin` |
| 28 | `GET|HEAD` | `admin/fasilitas/{facility}/edit` | `admin.facilities.edit` | `App\Http\Controllers\Admin\FacilityController@edit` | `web, admin` |
| 29 | `POST` | `admin/fasilitas/{facility}/toggle` | `admin.facilities.toggle` | `App\Http\Controllers\Admin\FacilityController@toggleActive` | `web, admin` |
| 30 | `GET|HEAD` | `admin/galeri` | `admin.gallery.index` | `App\Http\Controllers\Admin\GalleryController@index` | `web, admin` |
| 31 | `POST` | `admin/galeri` | `admin.gallery.store` | `App\Http\Controllers\Admin\GalleryController@store` | `web, admin` |
| 32 | `PUT` | `admin/galeri/{galleryItem}` | `admin.gallery.update` | `App\Http\Controllers\Admin\GalleryController@update` | `web, admin` |
| 33 | `DELETE` | `admin/galeri/{galleryItem}` | `admin.gallery.destroy` | `App\Http\Controllers\Admin\GalleryController@destroy` | `web, admin` |
| 34 | `GET|HEAD` | `admin/galeri/{galleryItem}/edit` | `admin.gallery.edit` | `App\Http\Controllers\Admin\GalleryController@edit` | `web, admin` |
| 35 | `PATCH` | `admin/galeri/{galleryItem}/toggle-active` | `admin.gallery.toggle-active` | `App\Http\Controllers\Admin\GalleryController@toggleActive` | `web, admin` |
| 36 | `GET|HEAD` | `admin/help` | `admin.help.index` | `App\Http\Controllers\Admin\HelpController@index` | `web, admin` |
| 37 | `GET|HEAD` | `admin/kategori` | `admin.categories.index` | `App\Http\Controllers\Admin\CategoryController@index` | `web, admin` |
| 38 | `POST` | `admin/kategori` | `admin.categories.store` | `App\Http\Controllers\Admin\CategoryController@store` | `web, admin` |
| 39 | `POST` | `admin/kategori/reorder` | `admin.categories.reorder` | `App\Http\Controllers\Admin\CategoryController@reorder` | `web, admin` |
| 40 | `PUT` | `admin/kategori/{category}` | `admin.categories.update` | `App\Http\Controllers\Admin\CategoryController@update` | `web, admin` |
| 41 | `DELETE` | `admin/kategori/{category}` | `admin.categories.destroy` | `App\Http\Controllers\Admin\CategoryController@destroy` | `web, admin` |
| 42 | `POST` | `admin/kategori/{category}/toggle` | `admin.categories.toggle` | `App\Http\Controllers\Admin\CategoryController@toggleActive` | `web, admin` |
| 43 | `GET|HEAD` | `admin/kontak` | `admin.` | `App\Http\Controllers\Admin\ContactController@index` | `web, admin` |
| 44 | `POST` | `admin/kontak` | `admin.` | `App\Http\Controllers\Admin\ContactController@update` | `web, admin` |
| 45 | `GET|HEAD` | `admin/login` | `admin.login` | `App\Http\Controllers\Admin\AuthController@showLogin` | `web` |
| 46 | `POST` | `admin/login` | `admin.login.post` | `App\Http\Controllers\Admin\AuthController@login` | `web` |
| 47 | `POST` | `admin/logout` | `admin.logout` | `App\Http\Controllers\Admin\AuthController@logout` | `web` |
| 48 | `GET|HEAD` | `admin/menu` | `admin.menus.index` | `App\Http\Controllers\Admin\MenuController@index` | `web, admin` |
| 49 | `POST` | `admin/menu` | `admin.menus.store` | `App\Http\Controllers\Admin\MenuController@store` | `web, admin` |
| 50 | `GET|HEAD` | `admin/menu/tambah` | `admin.menus.create` | `App\Http\Controllers\Admin\MenuController@create` | `web, admin` |
| 51 | `POST` | `admin/menu/{id}/restore` | `admin.menus.restore` | `App\Http\Controllers\Admin\MenuController@restore` | `web, admin` |
| 52 | `PUT` | `admin/menu/{menu}` | `admin.menus.update` | `App\Http\Controllers\Admin\MenuController@update` | `web, admin` |
| 53 | `DELETE` | `admin/menu/{menu}` | `admin.menus.destroy` | `App\Http\Controllers\Admin\MenuController@destroy` | `web, admin` |
| 54 | `GET|HEAD` | `admin/menu/{menu}/edit` | `admin.menus.edit` | `App\Http\Controllers\Admin\MenuController@edit` | `web, admin` |
| 55 | `POST` | `admin/menu/{menu}/stock` | `admin.menus.updateStock` | `App\Http\Controllers\Admin\MenuController@updateStock` | `web, admin` |
| 56 | `POST` | `admin/menu/{menu}/toggle-available` | `admin.menus.toggleAvailable` | `App\Http\Controllers\Admin\MenuController@toggleAvailable` | `web, admin` |
| 57 | `POST` | `admin/menu/{menu}/toggle-best-seller` | `admin.menus.toggleBestSeller` | `App\Http\Controllers\Admin\MenuController@toggleBestSeller` | `web, admin` |
| 58 | `POST` | `admin/menu/{menu}/toggle-featured` | `admin.menus.toggleFeatured` | `App\Http\Controllers\Admin\MenuController@toggleFeatured` | `web, admin` |
| 59 | `GET|HEAD` | `admin/notifications` | `admin.notifications.index` | `App\Http\Controllers\Admin\NotificationController@index` | `web, admin` |
| 60 | `POST` | `admin/notifications/mark-all-read` | `admin.notifications.markAllRead` | `App\Http\Controllers\Admin\NotificationController@markAllRead` | `web, admin` |
| 61 | `GET|HEAD` | `admin/notifications/poll` | `admin.notifications.poll` | `App\Http\Controllers\Admin\NotificationController@poll` | `web, admin` |
| 62 | `POST` | `admin/notifications/toggle-read` | `admin.notifications.toggleRead` | `App\Http\Controllers\Admin\NotificationController@toggleRead` | `web, admin` |
| 63 | `GET|HEAD` | `admin/notifikasi` | `admin.` | `App\Http\Controllers\Admin\NotificationController@index` | `web, admin` |
| 64 | `GET|HEAD` | `admin/pengaturan` | `admin.` | `App\Http\Controllers\Admin\SettingController@index` | `web, admin` |
| 65 | `POST` | `admin/pengaturan` | `admin.` | `App\Http\Controllers\Admin\SettingController@update` | `web, admin` |
| 66 | `POST` | `admin/pengaturan/dashboard-chart` | `admin.settings.toggleChart` | `App\Http\Controllers\Admin\SettingController@toggleDashboardChart` | `web, admin` |
| 67 | `POST` | `admin/pengaturan/jam-operasional` | `admin.settings.hours` | `App\Http\Controllers\Admin\SettingController@updateHours` | `web, admin` |
| 68 | `POST` | `admin/pengaturan/test-wa` | `admin.settings.testWa` | `App\Http\Controllers\Admin\SettingController@testZendoWhatsapp` | `web, admin` |
| 69 | `GET|HEAD` | `admin/pesanan` | `admin.orders.index` | `App\Http\Controllers\Admin\OrderController@index` | `web, admin` |
| 70 | `GET|HEAD` | `admin/pesanan/rekap` | `admin.orders.export` | `App\Http\Controllers\Admin\OrderController@exportRecap` | `web, admin` |
| 71 | `GET|HEAD` | `admin/pesanan/rekap/export` | `admin.orders.recap` | `App\Http\Controllers\Admin\OrderController@exportRecap` | `web, admin` |
| 72 | `GET|HEAD` | `admin/pesanan/scan` | `admin.orders.scan` | `App\Http\Controllers\Admin\OrderController@scanPage` | `web, admin` |
| 73 | `GET|HEAD` | `admin/pesanan/scan/cari` | `admin.orders.scan.search` | `App\Http\Controllers\Admin\OrderController@scanPage` | `web, admin` |
| 74 | `POST` | `admin/pesanan/settings` | `admin.orders.updateSettings` | `App\Http\Controllers\Admin\OrderController@updateSettings` | `web, admin` |
| 75 | `POST` | `admin/pesanan/settings/reset-template` | `admin.orders.resetTemplate` | `App\Http\Controllers\Admin\OrderController@resetTemplate` | `web, admin` |
| 76 | `GET|HEAD` | `admin/pesanan/{order}` | `admin.orders.show` | `App\Http\Controllers\Admin\OrderController@show` | `web, admin` |
| 77 | `POST` | `admin/pesanan/{order}/status` | `admin.orders.updateStatus` | `App\Http\Controllers\Admin\OrderController@updateStatus` | `web, admin` |
| 78 | `GET|HEAD` | `admin/pesanan/{order}/struk` | `admin.orders.receipt` | `App\Http\Controllers\Admin\OrderController@printReceipt` | `web, admin` |
| 79 | `GET|HEAD` | `admin/preview-desain` | `admin.preview.design` | `App\Http\Controllers\Admin\BannerController@previewDesign` | `web, admin` |
| 80 | `GET|HEAD` | `admin/preview-judul` | `admin.preview.judul` | `App\Http\Controllers\Admin\BannerController@previewDesign` | `web, admin` |
| 81 | `GET|HEAD` | `admin/profile` | `admin.profile.index` | `App\Http\Controllers\Admin\ProfileController@index` | `web, admin` |
| 82 | `POST` | `admin/profile` | `admin.profile.update` | `App\Http\Controllers\Admin\ProfileController@update` | `web, admin` |
| 83 | `POST` | `admin/profile/password` | `admin.profile.password` | `App\Http\Controllers\Admin\ProfileController@updatePassword` | `web, admin` |
| 84 | `POST` | `admin/profile/remove-avatar` | `admin.profile.removeAvatar` | `App\Http\Controllers\Admin\ProfileController@removeAvatar` | `web, admin` |
| 85 | `GET|HEAD` | `admin/reservasi` | `admin.reservations.index` | `App\Http\Controllers\Admin\ReservationController@index` | `web, admin` |
| 86 | `POST` | `admin/reservasi/fields` | `admin.reservations.fields.store` | `App\Http\Controllers\Admin\ReservationController@storeField` | `web, admin` |
| 87 | `PUT` | `admin/reservasi/fields/{field}` | `admin.reservations.fields.update` | `App\Http\Controllers\Admin\ReservationController@updateField` | `web, admin` |
| 88 | `DELETE` | `admin/reservasi/fields/{field}` | `admin.reservations.fields.destroy` | `App\Http\Controllers\Admin\ReservationController@destroyField` | `web, admin` |
| 89 | `POST|PATCH` | `admin/reservasi/fields/{field}/toggle` | `admin.reservations.fields.toggle` | `App\Http\Controllers\Admin\ReservationController@toggleField` | `web, admin` |
| 90 | `POST` | `admin/reservasi/whatsapp` | `admin.reservations.whatsapp.update` | `App\Http\Controllers\Admin\ReservationController@updateWhatsapp` | `web, admin` |
| 91 | `POST` | `admin/reservasi/whatsapp/update` | `admin.reservations.updateWhatsapp` | `App\Http\Controllers\Admin\ReservationController@updateWhatsapp` | `web, admin` |
| 92 | `DELETE` | `admin/reservasi/{reservation}` | `admin.reservations.destroy` | `App\Http\Controllers\Admin\ReservationController@destroy` | `web, admin` |
| 93 | `POST` | `admin/reservasi/{reservation}/status` | `admin.reservations.updateStatus` | `App\Http\Controllers\Admin\ReservationController@updateStatus` | `web, admin` |
| 94 | `GET|HEAD` | `admin/settings` | `admin.settings.index` | `App\Http\Controllers\Admin\SettingController@index` | `web, admin` |
| 95 | `POST` | `admin/settings` | `admin.settings.update` | `App\Http\Controllers\Admin\SettingController@update` | `web, admin` |
| 96 | `GET|HEAD` | `admin/testimoni` | `admin.testimonials.index` | `App\Http\Controllers\Admin\SettingController@testimonials` | `web, admin` |
| 97 | `POST` | `admin/testimoni` | `admin.testimonials.store` | `App\Http\Controllers\Admin\SettingController@storeTestimonial` | `web, admin` |
| 98 | `POST` | `admin/testimoni/hapus-data-contoh` | `admin.testimonials.deleteSample` | `App\Http\Controllers\Admin\SettingController@deleteSampleTestimonials` | `web, admin` |
| 99 | `DELETE` | `admin/testimoni/{testimonial}` | `admin.testimonials.destroy` | `App\Http\Controllers\Admin\SettingController@destroyTestimonial` | `web, admin` |
| 100 | `POST` | `admin/testimoni/{testimonial}/toggle` | `admin.testimonials.toggle` | `App\Http\Controllers\Admin\SettingController@toggleTestimonial` | `web, admin` |
| 101 | `GET|HEAD` | `admin/zendo` | `admin.zendo.index` | `App\Http\Controllers\Admin\SettingController@zendoIndex` | `web, admin` |
| 102 | `POST` | `admin/zendo/couriers` | `admin.zendo.couriers.update` | `App\Http\Controllers\Admin\SettingController@updateZendoCouriers` | `web, admin` |
| 103 | `POST` | `admin/zendo/couriers/{index}/status` | `admin.zendo.couriers.status` | `App\Http\Controllers\Admin\SettingController@updateZendoCourierStatus` | `web, admin` |
| 104 | `POST` | `admin/zendo/devices/reset-all` | `admin.zendo.devices.resetAll` | `App\Http\Controllers\Admin\SettingController@resetAllZendoDevices` | `web, admin` |
| 105 | `POST` | `admin/zendo/devices/{id}/reset` | `admin.zendo.devices.reset` | `App\Http\Controllers\Admin\SettingController@resetZendoDevice` | `web, admin` |
| 106 | `POST` | `admin/zendo/orders/{order}/stage` | `admin.zendo.orders.stage` | `App\Http\Controllers\Admin\SettingController@updateZendoOrderStage` | `web, admin` |
| 107 | `POST` | `admin/zendo/tariff` | `admin.zendo.tariff.update` | `App\Http\Controllers\Admin\SettingController@updateZendoTariff` | `web, admin` |
| 108 | `POST` | `api/transactions/process` | `api.transactions.process` | `App\Http\Controllers\NucomuController@storeOrder` | `web` |
| 109 | `GET|HEAD` | `checkout` | `checkout` | `App\Http\Controllers\OrderController@checkout` | `web` |
| 110 | `POST` | `checkout` | `order.store` | `App\Http\Controllers\OrderController@store` | `web` |
| 111 | `GET|HEAD` | `daftar-menu` | `menu` | `App\Http\Controllers\NucomuController@allMenu` | `web` |
| 112 | `GET|HEAD` | `event` | `events` | `App\Http\Controllers\PageController@events` | `web` |
| 113 | `GET|HEAD` | `fasilitas` | `facilities` | `App\Http\Controllers\PageController@facilities` | `web` |
| 114 | `GET|HEAD` | `galeri` | `gallery` | `App\Http\Controllers\PageController@gallery` | `web` |
| 115 | `GET|HEAD` | `kasir` | `kasir.index` | `App\Http\Controllers\KasirController@index` | `web, kasir` |
| 116 | `GET|HEAD` | `kasir/login` | `kasir.login` | `App\Http\Controllers\KasirController@showLogin` | `web` |
| 117 | `POST` | `kasir/login` | `kasir.login.post` | `App\Http\Controllers\KasirController@login` | `web` |
| 118 | `POST` | `kasir/logout` | `kasir.logout` | `App\Http\Controllers\KasirController@logout` | `web` |
| 119 | `POST` | `kasir/pesanan` | `kasir.orders.store` | `App\Http\Controllers\KasirController@storeOrder` | `web, kasir` |
| 120 | `GET|HEAD` | `kasir/pesanan/cari` | `kasir.orders.lookup` | `App\Http\Controllers\KasirController@lookupOrder` | `web, kasir` |
| 121 | `POST` | `kasir/pesanan/{order}/status` | `kasir.orders.status` | `App\Http\Controllers\KasirController@updateOrderStatus` | `web, kasir` |
| 122 | `GET|HEAD` | `kasir/pesanan/{order}/struk` | `kasir.orders.receipt` | `App\Http\Controllers\KasirController@receipt` | `web, kasir` |
| 123 | `GET|HEAD` | `kasir/sinkron` | `kasir.sync` | `App\Http\Controllers\KasirController@syncData` | `web, kasir` |
| 124 | `POST` | `kasir/stok` | `kasir.stock.update` | `App\Http\Controllers\KasirController@updateStock` | `web, kasir` |
| 125 | `GET|HEAD` | `keranjang` | `cart` | `App\Http\Controllers\CartController@index` | `web` |
| 126 | `POST` | `keranjang/hapus` | `cart.remove` | `App\Http\Controllers\CartController@remove` | `web` |
| 127 | `POST` | `keranjang/kosongkan` | `cart.clear` | `App\Http\Controllers\CartController@clear` | `web` |
| 128 | `POST` | `keranjang/tambah/{menu:slug}` | `cart.add` | `App\Http\Controllers\CartController@add` | `web` |
| 129 | `GET|HEAD` | `kontak` | `contact` | `App\Http\Controllers\PageController@contact` | `web` |
| 130 | `GET|HEAD` | `lacak-pesanan` | `order.track` | `App\Http\Controllers\OrderController@track` | `web` |
| 131 | `GET|HEAD` | `layanan` | `services` | `App\Http\Controllers\PageController@services` | `web` |
| 132 | `GET|HEAD` | `menu` | `menu.all` | `App\Http\Controllers\NucomuController@allMenu` | `web` |
| 133 | `GET|HEAD` | `menu/{menu:slug}` | `menu.show` | `App\Http\Controllers\MenuController@show` | `web` |
| 134 | `GET|HEAD` | `nucomu` | `nucomu.index` | `App\Http\Controllers\NucomuController@nucomuPage` | `web` |
| 135 | `POST` | `nucomu/ulasan` | `nucomu.review.store` | `App\Http\Controllers\NucomuController@storeReview` | `web` |
| 136 | `GET|HEAD` | `pesan` | `nucomu.order` | `App\Http\Controllers\NucomuController@orderPage` | `web` |
| 137 | `POST` | `pesan` | `nucomu.order.store` | `App\Http\Controllers\NucomuController@storeOrder` | `web` |
| 138 | `GET|HEAD` | `pesan/slip/{code}` | `nucomu.order.slip` | `App\Http\Controllers\NucomuController@orderSlip` | `web` |
| 139 | `GET|HEAD` | `pesanan/konfirmasi/{code}` | `order.confirmation` | `App\Http\Controllers\OrderController@confirmation` | `web` |
| 140 | `GET|HEAD` | `qr/pesanan/{code}` | `admin.orders.qr` | `App\Http\Controllers\Admin\OrderController@qrDetail` | `web` |
| 141 | `POST` | `reservasi` | `reservation.submit` | `App\Http\Controllers\NucomuController@submitReservation` | `web` |
| 142 | `GET|HEAD` | `reservasi` | `reservation` | `App\Http\Controllers\PageController@reservation` | `web` |
| 143 | `POST` | `reservasi/store` | `reservation.store` | `App\Http\Controllers\NucomuController@submitReservation` | `web` |
| 144 | `GET|HEAD` | `storage/{path}` | `storage.local` | `Closure` | `-` |
| 145 | `PUT` | `storage/{path}` | `storage.local.upload` | `Closure` | `-` |
| 146 | `POST` | `transactions/process` | `transactions.process` | `App\Http\Controllers\NucomuController@storeOrder` | `web` |
| 147 | `GET|HEAD` | `up` | `-` | `Closure` | `-` |
| 148 | `POST` | `zendo/kurir/keluar` | `zendo.courier.unbind` | `App\Http\Controllers\ZendoTrackingController@unbindCourier` | `web` |
| 149 | `GET|HEAD` | `zendo/kurir/{token}` | `zendo.courier.show` | `App\Http\Controllers\ZendoTrackingController@courier` | `web` |
| 150 | `POST` | `zendo/kurir/{token}/lokasi` | `zendo.courier.location` | `App\Http\Controllers\ZendoTrackingController@updateLocation` | `web` |
| 151 | `POST` | `zendo/kurir/{token}/otp` | `zendo.courier.otp` | `App\Http\Controllers\ZendoTrackingController@sendOtp` | `web` |
| 152 | `POST` | `zendo/kurir/{token}/status` | `zendo.courier.status` | `App\Http\Controllers\ZendoTrackingController@updateStatus` | `web` |
| 153 | `POST` | `zendo/kurir/{token}/verifikasi` | `zendo.courier.verify` | `App\Http\Controllers\ZendoTrackingController@verify` | `web` |
| 154 | `GET|HEAD` | `zendo/lacak/{token}` | `zendo.track` | `App\Http\Controllers\ZendoTrackingController@track` | `web` |
| 155 | `GET|HEAD` | `zendo/lacak/{token}/data` | `zendo.track.data` | `App\Http\Controllers\ZendoTrackingController@trackData` | `web` |
| 156 | `POST` | `zendo/lacak/{token}/lokasi` | `zendo.track.location` | `App\Http\Controllers\ZendoTrackingController@updateLocation` | `web` |
| 157 | `POST` | `zendo/lacak/{token}/pin` | `zendo.track.pin` | `App\Http\Controllers\ZendoTrackingController@verifyPin` | `web` |
| 158 | `POST` | `zendo/lacak/{token}/status` | `zendo.track.status` | `App\Http\Controllers\ZendoTrackingController@updateStatus` | `web` |
