# KAMUS DATA TEKNIS BASIS DATA (DATA DICTIONARY)
**Sistem:** Sistem Informasi & Transaksi Pemesanan Nucomu Cafe & Roastery  
**Basis Data:** Relasional (RDBMS) MySQL 8.0 / MariaDB  
**Metode Ekstraksi:** Berkas Migrasi Resmi Laravel (`database/migrations/`) & Model Eloquent (`app/Models/`)  
**Kebijakan Privasi:** Kolom sensitif (hash password, token rahasia) disebutkan nama dan tipenya tanpa mengekspos nilai rahasia.

---

## 1. Daftar dan Ringkasan Tabel Basis Data

Sistem Nucomu Cafe memiliki 17 tabel data store operasional utama (`D1` s/d `D17`) dan 4 kelompok tabel penunjang framework Laravel:

| No. Store | Nama Tabel | Jumlah Kolom | Fungsi Utama dalam Sistem |
| :---: | :--- | :---: | :--- |
| **D1** | `users` | 9 | Akun pengguna sistem dengan peran `admin`, `kasir`, dan `user`. |
| **D2** | `categories` | 7 | Kategori induk sajian menu (Coffee, Meals, Dessert, Beverage, dll). |
| **D3** | `menus` | 14 | Katalog menu sajian lengkap dengan harga, foto, stok fisik, dan soft deletes. |
| **D4** | `menu_options` | 7 | Pilihan varian penyajian (dingin/panas), level manis, dan topping per menu. |
| **D5** | `tables` | 8 | Meja fisik di kafe beserta kapasitas kursi, lokasi area, dan status keterisian. |
| **D6** | `orders` | 19 | Induk data transaksi pemesanan (dine-in, takeaway, Zendo delivery). |
| **D7** | `order_items` | 9 | Baris item pesanan yang dibeli, harga saat transaksi, kuantitas, dan opsi varian. |
| **D8** | `reservations` | 13 | Data pengajuan reservasi meja pelanggan dan struktur isian form builder. |
| **D9** | `reservation_fields` | 10 | Kolom dinamis formulir reservasi yang dikonfigurasi admin (*Form Builder*). |
| **D10** | `settings` | 7 | Penyimpanan konfigurasi dinamis kafe (*key-value*), tarif Zendo, narasi, dan kontak. |
| **D11** | `operating_hours` | 7 | Jadwal jam buka dan tutup kafe selama 7 hari dalam sepekan. |
| **D12** | `facilities` | 13 | Kartu fasilitas penunjang kafe, badge, ikon, checklist fitur, dan galeri slider. |
| **D13** | `gallery_items` | 7 | Koleksi visual foto kafe, kategori, path berkas foto, dan keterangan. |
| **D14** | `testimonials` | 12 | Ulasan dan testimoni pelanggan dari website dengan status kurasi semat (*is_pinned*). |
| **D15** | `events` | 7 | Agenda kegiatan khusus, promo tematik, dan jadwal acara kafe. |
| **D16** | `zendo_deliveries` | 27 | Logistik pengantaran kurir Zendo, koordinat GPS real-time, token, dan riwayat rute. |
| **D17** | `banners` | 12 | Banner promo hero slider beranda, berkas media, mode layout, dan jadwal kedaluwarsa. |
| **S1** | `password_reset_tokens` | 3 | Token reset kata sandi pengguna framework. |
| **S2** | `sessions` | 6 | Sesi peramban web pengguna saat menggunakan driver database. |
| **S3** | `cache` & `cache_locks` | 3 | Penyimpanan cache sementara sistem (termasuk cache Google Places API 6 jam). |
| **S4** | `jobs` & `failed_jobs` | 7 | Antrean tugas latar belakang sistem (*queue jobs*). |

---

## 2. Kamus Data Rinci Tabel Operasional Utama (D1 s/d D17)

### 2.1 Tabel `users` (Store D1)
Tabel untuk autentikasi dan manajemen akun pengelola (Admin) serta staf operasional (Kasir).

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik akun pengguna. |
| `name` | VARCHAR(255) | Tidak | - | - | - | Nama lengkap pengguna atau staf kafe. |
| `email` | VARCHAR(255) | Tidak | UQ | - | - | Alamat email unik untuk login sistem. |
| `email_verified_at`| TIMESTAMP | Ya | - | NULL | - | Stempel waktu verifikasi email. |
| `password` | VARCHAR(255) | Tidak | - | - | - | Hash sandi bcrypt via `Hash::make`. Sensitif. |
| `role` | VARCHAR(50) | Tidak | - | `'user'` | - | Peran akses pengguna: `'admin'`, `'kasir'`, `'user'`. |
| `avatar` | VARCHAR(255) | Ya | - | NULL | - | Nama berkas foto profil di `public/uploads/avatars/`. |
| `remember_token` | VARCHAR(100) | Ya | - | NULL | - | Token autentikasi bawaan Laravel. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu akun didaftarkan. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu data akun terakhir diperbarui. |

---

### 2.2 Tabel `categories` (Store D2)
Tabel master pengelompokan menu kafe.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik kategori menu. |
| `name` | VARCHAR(100) | Tidak | - | - | - | Nama kategori (misal: "Specialty Coffee", "Artisanal Dessert"). |
| `slug` | VARCHAR(255) | Ya | UQ | NULL | - | Slug URL unik ramah SEO (misal: "specialty-coffee"). |
| `sort_order` | INT | Tidak | - | `0` | - | Urutan penampilan tab kategori di halaman menu. |
| `is_active` | BOOLEAN | Tidak | - | `true` | - | Status aktif (true = tampil di web, false = disembunyikan). |
| `description` | TEXT | Ya | - | NULL | - | Penjelasan singkat narasi kategori menu. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu kategori dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu kategori terakhir diubah. |

---

### 2.3 Tabel `menus` (Store D3)
Tabel utama penyimpan seluruh data sajian makanan, minuman, dan pencuci mulut kafe.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik sajian menu. |
| `category_id` | BIGINT UNSIGNED | Tidak | FK | - | `categories.id` | ID kategori induk. Terhubung ke `categories`. |
| `name` | VARCHAR(255) | Tidak | - | - | - | Nama sajian menu (misal: "Classic Tiramisu"). |
| `slug` | VARCHAR(255) | Tidak | UQ | - | - | Slug unik untuk tautan detail `/menu/{slug}`. |
| `description` | TEXT | Ya | - | NULL | - | Deskripsi komposisi dan cita rasa sajian. |
| `price` | INT UNSIGNED | Tidak | - | - | - | Harga resmi dalam mata uang Rupiah bulat tanpa desimal. |
| `stock` | INT | Tidak | - | `25` | - | Jumlah persediaan fisik menu. Nilai `0` = habis. |
| `image` | VARCHAR(255) | Ya | - | NULL | - | Berkas foto sajian di `public/uploads/menus/` (rasio 1:1). |
| `is_available`| BOOLEAN | Tidak | - | `true` | - | Ketersediaan menu (true = dapat dipesan, false = habis). |
| `is_featured` | BOOLEAN | Tidak | - | `false` | - | Penanda menu unggulan di beranda kafe. |
| `is_best_seller`| BOOLEAN | Tidak | - | `false` | - | Penanda menu terlaris (*best seller*). |
| `is_sample` | BOOLEAN | Tidak | - | `true` | - | Penanda apakah data berasal dari seeder awal. |
| `sort_order` | INT | Tidak | - | `0` | - | Urutan penyajian kartu menu di katalog. |
| `deleted_at` | TIMESTAMP | Ya | IDX | NULL | - | Penanda waktu soft delete (proteksi riwayat pesanan). |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu pembuatan data menu. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu pembaruan menu terakhir. |

---

### 2.4 Tabel `menu_options` (Store D4)
Tabel opsi kustomisasi sajian untuk pilihan pelanggan (varian penyajian, kadar gula, tambahan).

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik opsi menu. |
| `menu_id` | BIGINT UNSIGNED | Tidak | FK | - | `menus.id` | Menu pemilik opsi ini. On delete cascade. |
| `name` | VARCHAR(255) | Tidak | - | - | - | Nama grup opsi (misal: "Penyajian", "Level Manis"). |
| `type` | VARCHAR(50) | Tidak | - | `'single'` | - | Jenis pilihan: `'single'` (radio) atau `'multiple'` (checkbox). |
| `is_required` | BOOLEAN | Tidak | - | `false` | - | Apakah pelanggan wajib memilih opsi ini saat order. |
| `options` | JSON | Tidak | - | - | - | Array JSON pilihan opsi (misal: `["Dingin", "Panas"]`). |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu opsi dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu opsi diperbarui. |

---

### 2.5 Tabel `tables` (Store D5)
Tabel master meja fisik di kafe untuk pemesanan makan di tempat (*dine-in*).

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik meja. |
| `table_number`| VARCHAR(50) | Tidak | UQ | - | - | Nomor/label meja (misal: "Meja 01", "Outdoor T1"). |
| `capacity` | INT | Tidak | - | `2` | - | Kapasitas jumlah kursi yang tersedia di meja. |
| `status` | VARCHAR(50) | Tidak | - | `'available'`| - | Status meja: `'available'`, `'occupied'`, `'reserved'`. |
| `location` | VARCHAR(50) | Tidak | - | `'indoor'` | - | Posisi meja: `'indoor'`, `'outdoor'`, `'second_floor'`, `'terrace'`. |
| `qr_code_path`| VARCHAR(255)| Ya | - | NULL | - | Path berkas QR cetak meja kafe. |
| `notes` | TEXT | Ya | - | NULL | - | Catatan fasilitas meja (misal: "Dekat stopkontak"). |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu meja didaftarkan. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu meja diubah. |

---

### 2.6 Tabel `orders` (Store D6)
Tabel utama penyimpan seluruh transaksi pesanan pelanggan (dine-in, takeaway, Zendo delivery).

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik transaksi pesanan. |
| `code` | VARCHAR(100) | Tidak | UQ | - | - | Kode unik transaksi pesanan (format: `NCM-Ymd-xxx`). |
| `user_id` | BIGINT UNSIGNED | Ya | FK | NULL | `users.id` | ID pengguna jika pelanggan login (opsional). |
| `customer_name`| VARCHAR(100)| Ya | - | NULL | - | Nama lengkap pemesan pesanan. |
| `customer_phone`| VARCHAR(30)| Ya | - | NULL | - | Nomor WhatsApp atau telepon pelanggan. |
| `table_id` | BIGINT UNSIGNED | Ya | FK | NULL | `tables.id` | Meja yang ditempati jika memilih dine-in. |
| `order_type` | VARCHAR(50) | Tidak | - | `'dine_in'` | - | Tipe pesanan: `'dine_in'`, `'take_away'`, `'delivery'`. |
| `status` | VARCHAR(50) | Tidak | IDX | `'pending'` | - | Status transaksi: `pending`, `processing`, `ready`, `completed`, `cancelled`. |
| `payment_status`| VARCHAR(50)| Tidak | IDX | `'unpaid'` | - | Status pembayaran: `'unpaid'`, `'paid'`, `'refunded'`. |
| `payment_method`| VARCHAR(50)| Tidak | - | `'cash'` | - | Metode pembayaran: `'cash'`, `'qris'`, `'transfer'`. |
| `subtotal` | INT UNSIGNED | Tidak | - | `0` | - | Total harga sajian menu sebelum ongkos kirim. |
| `delivery_fee` | INT UNSIGNED | Tidak | - | `0` | - | Ongkos kirim kurir Zendo (jika tipe delivery). |
| `discount` | INT UNSIGNED | Tidak | - | `0` | - | Potongan diskon promosi kafe. |
| `tax` | INT UNSIGNED | Tidak | - | `0` | - | Pajak pertambahan nilai (jika ada). |
| `total` | INT UNSIGNED | Tidak | - | `0` | - | Grand total pembayaran (subtotal + delivery_fee - discount). |
| `delivery_address`| TEXT | Ya | - | NULL | - | Alamat pengantaran tujuan untuk pesanan Zendo. |
| `notes` | TEXT | Ya | - | NULL | - | Catatan pesanan pelanggan atau metadata kurir. |
| `form_data` | JSON | Ya | - | NULL | - | Koordinat GPS pemesan (`customer_lat`, `customer_lng`), jarak km, dan nomor meja. |
| `completed_at`| TIMESTAMP | Ya | - | NULL | - | Stempel waktu saat pesanan diselesaikan/lunas. |
| `created_at` | TIMESTAMP | Ya | IDX | NULL | - | Waktu pesanan dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu pesanan terakhir diperbarui. |

---

### 2.7 Tabel `order_items` (Store D7)
Tabel rincian item sajian yang dipesan per pesanan. Menjaga integritas riwayat harga historis.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik baris item pesanan. |
| `order_id` | BIGINT UNSIGNED | Tidak | FK | - | `orders.id` | ID pesanan induk. On delete cascade. |
| `menu_id` | BIGINT UNSIGNED | Ya | FK | NULL | `menus.id` | ID menu asli. Set null jika menu terhapus permanen. |
| `menu_name` | VARCHAR(255) | Tidak | - | - | - | Nama menu saat transaksi (tidak berubah meski master menu diedit). |
| `unit_price` | INT UNSIGNED | Tidak | - | `0` | - | Harga satuan resmi per porsi saat transaksi berlangsung. |
| `quantity` | INT UNSIGNED | Tidak | - | `1` | - | Jumlah porsi yang dipesan (wajib integer >= 1). |
| `selected_options`| JSON | Ya | - | NULL | - | Pilihan varian penyajian atau tambahan dalam JSON. |
| `subtotal` | INT UNSIGNED | Tidak | - | `0` | - | Subtotal baris item (`unit_price * quantity`). |
| `notes` | VARCHAR(255) | Ya | - | NULL | - | Catatan khusus per sajian (misal: "Jangan terlalu manis"). |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu item pesanan disimpan. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu item diperbarui. |

---

### 2.8 Tabel `reservations` (Store D8)
Tabel penyimpan data pengajuan reservasi meja dan acara pelanggan.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik reservasi. |
| `user_id` | BIGINT UNSIGNED | Ya | FK | NULL | `users.id` | ID akun pelanggan jika login. |
| `table_id` | BIGINT UNSIGNED | Ya | FK | NULL | `tables.id` | Meja yang dipesan (jika dialokasikan admin). |
| `name` | VARCHAR(255) | Tidak | - | - | - | Nama lengkap pemesan reservasi meja. |
| `phone` | VARCHAR(50) | Tidak | - | - | - | Nomor WhatsApp pemesan untuk konfirmasi. |
| `email` | VARCHAR(255) | Ya | - | NULL | - | Alamat email pemesan. |
| `reservation_date`| DATE | Tidak | - | - | - | Tanggal kedatangan yang diajukan (format YYYY-MM-DD). |
| `reservation_time`| TIME | Tidak | - | - | - | Jam kedatangan yang diajukan (format HH:MM). |
| `guest_count` | INT | Tidak | - | `2` | - | Jumlah tamu yang akan hadir (minimal 1). |
| `special_requests`| TEXT | Ya | - | NULL | - | Permintaan khusus (misal: "Meja pojok dekat tanaman"). |
| `status` | VARCHAR(50) | Tidak | IDX | `'pending'` | - | Status reservasi: `pending`, `confirmed`, `cancelled`, `completed`. |
| `notes` | TEXT | Ya | - | NULL | - | Catatan operasional internal admin kafe. |
| `form_data` | JSON | Ya | - | NULL | - | Kumpulan seluruh nilai isian Form Builder dinamis JSON. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu reservasi diajukan. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu status reservasi diperbarui. |

---

### 2.9 Tabel `reservation_fields` (Store D9)
Tabel perancang formulir dinamis (*Form Builder*) reservasi yang dikontrol penuh oleh admin.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik kolom formulir. |
| `label` | VARCHAR(255) | Tidak | - | - | - | Teks label kolom (misal: "Nomor WhatsApp", "Bukti DP"). |
| `field_key` | VARCHAR(100) | Tidak | UQ | - | - | Kunci teknis kolom dalam request form (misal: `phone`, `photo_dp`). |
| `type` | VARCHAR(50) | Tidak | - | `'text'` | - | Tipe kolom: `text`, `tel`, `date`, `time`, `number`, `textarea`, `photo`, `checkbox`. |
| `placeholder`| VARCHAR(255) | Ya | - | NULL | - | Teks panduan pengisian (*placeholder*) pada elemen input. |
| `options` | JSON | Ya | - | NULL | - | Pilihan opsi untuk tipe `checkbox` (format array JSON). |
| `is_required` | BOOLEAN | Tidak | - | `true` | - | Status wajib diisi (true = wajib, false = opsional). |
| `sort_order` | INT | Tidak | - | `0` | - | Urutan tata letak kolom di formulir reservasi. |
| `is_active` | BOOLEAN | Tidak | - | `true` | - | Status aktif (true = ditampilkan di web, false = disembunyikan). |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu kolom dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu kolom diperbarui. |

---

### 2.10 Tabel `settings` (Store D10)
Tabel konfigurasi serbaguna sistem berpasangan kunci-nilai (*key-value store*).

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik baris pengaturan. |
| `key` | VARCHAR(191) | Tidak | UQ | - | - | Kunci konfigurasi unik (misal: `cafe_whatsapp`, `transaction_mode`). |
| `value` | LONGTEXT | Ya | - | NULL | - | Nilai konfigurasi dalam format teks polos atau JSON string. |
| `type` | VARCHAR(50) | Tidak | - | `'text'` | - | Tipe representasi data (`text`, `boolean`, `integer`, `json`, `image`). |
| `group` | VARCHAR(50) | Tidak | IDX | `'general'` | - | Kelompok modul (`general`, `contact`, `transaction`, `zendo`, `about`). |
| `description` | TEXT | Ya | - | NULL | - | Keterangan fungsi konfigurasi untuk pengembang. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu pengaturan dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu pengaturan diubah. |

#### Daftar Kunci Pengaturan Kritis yang Digunakan di Kode:
- `transaction_mode`: Mode transaksi global (`kasir`, `zendo`, `both`, `none`).
- `order_mode_dine_in`: Saklar aktif/nonaktif pemesanan makan di kafe (`1` / `0`).
- `order_mode_zendo_tracking`: Saklar aktif/nonaktif pengantaran pelacakan GPS (`1` / `0`).
- `order_mode_zendo_chat`: Saklar aktif/nonaktif pengantaran chat WhatsApp Zendo (`1` / `0`).
- `order_dine_in_cash`: Saklar pembayaran tunai kasir (`1` / `0`).
- `order_dine_in_qris`: Saklar pembayaran QRIS kasir (`1` / `0`).
- `qris_image`: Path berkas citra QRIS resmi kafe di server.
- `cafe_whatsapp`: Nomor WhatsApp resmi kafe untuk kontak & reservasi.
- `zendo_whatsapp`: Nomor WhatsApp pusat koordinasi kurir Zendo.
- `zendo_courier_pin`: PIN 4 digit untuk mengaktifkan Mode Kurir (default `1234`).
- `zendo_couriers`: JSON array daftar armada kurir Zendo beserta nomor HP aktif.
- `zendo_chat_template`: Template draf pesan WhatsApp pesanan kurir Zendo.
- `hide_dashboard_chart`: Saklar sembunyikan grafik analitik dashboard (`1` / `0`).
- `admin_read_notification_keys`: JSON array berisi daftar ID notifikasi yang sudah dibaca admin.

---

### 2.11 Tabel `operating_hours` (Store D11)
Tabel jadwal jam operasional kafe harian.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik jadwal hari. |
| `day_of_week` | SMALLINT | Tidak | IDX | - | - | Indeks hari sepekan: `0` = Minggu, `1` = Senin, ..., `6` = Sabtu. |
| `open_time` | TIME | Ya | - | NULL | - | Jam buka operasional kafe (misal: `11:00:00`). |
| `close_time` | TIME | Ya | - | NULL | - | Jam tutup operasional kafe (misal: `21:00:00`). |
| `is_closed` | BOOLEAN | Tidak | - | `false` | - | Penanda apakah kafe libur/tutup penuh pada hari tersebut. |
| `notes` | VARCHAR(255) | Ya | - | NULL | - | Catatan jadwal khusus (misal: "Bar kopi tutup 30 mnt sebelum tutup"). |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu data jadwal dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu jadwal diperbarui. |

---

### 2.12 Tabel `facilities` (Store D12)
Tabel penyimpan data fasilitas penunjang kafe (Indoor, Outdoor, Musala, Toilet).

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik fasilitas. |
| `name` | VARCHAR(255) | Tidak | - | - | - | Nama fasilitas (misal: "Area Indoor"). |
| `slug` | VARCHAR(100) | Ya | - | NULL | - | Slug pengenal tab fasilitas (misal: `indoor`, `musala`). |
| `tab_name` | VARCHAR(100) | Ya | - | NULL | - | Label nama tab pendek yang tampil di kartu. |
| `title` | VARCHAR(255) | Ya | - | NULL | - | Judul utama fasilitas. |
| `badge` | VARCHAR(100) | Ya | - | NULL | - | Teks lencana penanda (misal: "AC & Power Outlets"). |
| `subtitle` | VARCHAR(255) | Ya | - | NULL | - | Sub-judul fasilitas penjelas suasana. |
| `icon` | VARCHAR(100) | Ya | - | NULL | - | Nama ikon Material Symbols (misal: `desk`, `yard`, `mosque`, `wc`). |
| `image` | VARCHAR(255) | Ya | - | NULL | - | Berkas foto sampul utama fasilitas. |
| `description` | TEXT | Ya | - | NULL | - | Narasi deskripsi lengkap kenyamanan fasilitas. |
| `features` | JSON | Ya | - | NULL | - | Array JSON berisi daftar keunggulan fasilitas. |
| `photos` | JSON | Ya | - | NULL | - | Array JSON daftar berkas foto slider carousel fasilitas. |
| `is_active` | BOOLEAN | Tidak | - | `true` | - | Status tampil di beranda kafe. |
| `sort_order` | INT | Tidak | - | `0` | - | Urutan tata letak kartu fasilitas di website. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu fasilitas dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu fasilitas diperbarui. |

---

### 2.13 Tabel `gallery_items` (Store D13)
Tabel koleksi visual foto dan kegiatan kafe Nucomu.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik item galeri. |
| `title` | VARCHAR(255) | Tidak | - | - | - | Judul foto galeri (misal: "Seduhan Pour Over V60"). |
| `category` | VARCHAR(100) | Tidak | IDX | `'atmosphere'`| - | Kategori visual: `atmosphere`, `food`, `beverage`, `event`. |
| `image_path` | VARCHAR(255) | Tidak | - | - | - | Path berkas foto di direktori `public/uploads/gallery/`. |
| `caption` | TEXT | Ya | - | NULL | - | Keterangan naratif suasana atau komposisi foto. |
| `sort_order` | INT | Tidak | - | `0` | - | Urutan grid galeri di beranda dan halaman galeri. |
| `is_active` | BOOLEAN | Tidak | - | `true` | - | Status penayangan foto di website. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu foto galeri diunggah. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu data foto diperbarui. |

---

### 2.14 Tabel `testimonials` (Store D14)
Tabel ulasan dan testimoni pelanggan dari website dengan sistem kurasi sematan (*is_pinned*).

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik ulasan. |
| `customer_name`| VARCHAR(255)| Tidak | - | - | - | Nama pengulas ulasan (atau alias nama pelanggan). |
| `customer_title`| VARCHAR(255)| Ya | - | NULL | - | Sebutan pengulas (misal: "Penikmat Kopi Tulungagung"). |
| `rating` | TINYINT UNSIGNED| Tidak | - | `5` | - | Penilaian kepuasan bintang 1 hingga 5. |
| `review` | TEXT | Tidak | - | - | - | Teks isi komentar ulasan dari pelanggan. |
| `avatar` | VARCHAR(255) | Ya | - | NULL | - | URL foto avatar profil pengulas. |
| `review_image` | VARCHAR(255) | Ya | - | NULL | - | Berkas foto ulasan di `public/uploads/reviews/`. |
| `source` | VARCHAR(50) | Tidak | - | `'website'` | - | Asal sumber ulasan (`'website'` atau `'google'`). |
| `is_sample` | BOOLEAN | Tidak | - | `false` | - | Penanda apakah data berasal dari contoh bawaan seeder. |
| `is_pinned` | BOOLEAN | Tidak | IDX | `false` | - | Penanda semat kurasi admin (**Wajib true agar tayang di beranda**). |
| `is_active` | BOOLEAN | Tidak | - | `true` | - | Status aktif (false = disembunyikan admin tanpa hapus data). |
| `sort_order` | INT | Tidak | - | `0` | - | Urutan prioritas penayangan ulasan di slider beranda. |
| `created_at` | TIMESTAMP | Ya | IDX | NULL | - | Waktu ulasan dikirim pelanggan. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu status ulasan diubah admin. |

---

### 2.15 Tabel `events` (Store D15)
Tabel publikasi agenda dan promosi tematik kafe.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik agenda event. |
| `title` | VARCHAR(255) | Tidak | - | - | - | Nama acara (misal: "Live Acoustic Night Weekend"). |
| `slug` | VARCHAR(255) | Tidak | UQ | - | - | Slug unik agenda acara. |
| `description` | TEXT | Ya | - | NULL | - | Rincian kegiatan, pengisi acara, dan ketentuan. |
| `image` | VARCHAR(255) | Ya | - | NULL | - | Berkas poster kegiatan di `public/uploads/events/`. |
| `start_date` | DATETIME | Tidak | - | - | - | Waktu pelaksanaan mulai acara. |
| `end_date` | DATETIME | Ya | - | NULL | - | Waktu perkiraan acara berakhir. |
| `is_active` | BOOLEAN | Tidak | - | `true` | - | Status aktif penayangan event di web. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu data event dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu event diperbarui. |

---

### 2.16 Tabel `zendo_deliveries` (Store D16)
Tabel logistik kurir internal Zendo, perekam koordinat GPS real-time, token pelacakan, dan audit anti-fake GPS.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik rekaman pengantaran. |
| `order_id` | BIGINT UNSIGNED | Tidak | FK | - | `orders.id` | ID pesanan yang diantar. On delete cascade. |
| `order_code` | VARCHAR(100) | Tidak | IDX | - | - | Salinan kode pesanan (misal: `NCM-20261110-001`). |
| `courier_index` | SMALLINT UNSIGNED| Tidak | - | `1` | - | Nomor indeks kurir Zendo yang ditugaskan (1..50). |
| `courier_name` | VARCHAR(255) | Tidak | - | - | - | Nama kurir yang mengantar (misal: "Kurir Zendo 1"). |
| `courier_phone` | VARCHAR(30) | Tidak | IDX | - | - | Nomor WhatsApp kurir yang dinormalisasi (628xxx). |
| `tracking_token`| VARCHAR(64) | Tidak | UQ | - | - | Token unik publik pelanggan untuk `/zendo/lacak/{token}`. |
| `courier_token` | VARCHAR(64) | Tidak | UQ | - | - | Token rahasia khusus kurir untuk `/zendo/kurir/{token}`. |
| `status` | VARCHAR(20) | Tidak | IDX | `'assigned'` | - | Status tahapan: `assigned`, `picked_up`, `on_the_way`, `almost_done`, `delivered`. |
| `customer_name` | VARCHAR(255) | Ya | - | NULL | - | Salinan nama pelanggan penerima pesanan. |
| `delivery_address`| TEXT | Ya | - | NULL | - | Alamat lengkap tujuan pengantaran. |
| `destination_lat`| DECIMAL(10,7) | Ya | - | NULL | - | Titik latitude lokasi pengantaran pelanggan. |
| `destination_lng`| DECIMAL(10,7) | Ya | - | NULL | - | Titik longitude lokasi pengantaran pelanggan. |
| `courier_lat` | DECIMAL(10,7) | Ya | - | NULL | - | Posisi latitude terakhir kurir yang disiarkan GPS. |
| `courier_lng` | DECIMAL(10,7) | Ya | - | NULL | - | Posisi longitude terakhir kurir yang disiarkan GPS. |
| `courier_accuracy`| FLOAT | Ya | - | NULL | - | Akurasi sinyal GPS kurir dalam meter (maksimal 150m). |
| `courier_heading` | FLOAT | Ya | - | NULL | - | Arah kompas pergerakan kurir dalam derajat (0-360). |
| `courier_speed` | FLOAT | Ya | - | NULL | - | Kecepatan kurir dalam m/s (maksimal 45 m/s = 162 km/jam). |
| `location_updated_at`| TIMESTAMP| Ya | - | NULL | - | Waktu terakhir koordinat kurir diterima server. |
| `route_trail` | JSON | Ya | - | NULL | - | Array JSON jejak koordinat riwayat rute perjalanan kurir. |
| `gps_suspicious_count`| INT UNSIGNED| Tidak | - | `0` | - | Penghitung anomali kecepatan / akurasi / lompatan lokasi. |
| `last_gps_warning`| VARCHAR(255)| Ya | - | NULL | - | Pesan peringatan keamanan GPS terakhir yang tercatat. |
| `gps_locked_until`| TIMESTAMP | Ya | - | NULL | - | Kunci waktu jika kurir terkena hukuman GPS Lock (5 mnt). |
| `courier_verified_at`| TIMESTAMP| Ya | - | NULL | - | Stempel waktu saat kurir berhasil verifikasi PIN. |
| `picked_up_at` | TIMESTAMP | Ya | - | NULL | - | Waktu saat kurir mengambil pesanan dari meja dapur kafe. |
| `completed_at` | TIMESTAMP | Ya | - | NULL | - | Waktu saat pesanan berhasil diserahkan ke pelanggan. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu penugasan pengantaran dibuat. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu rekaman pengantaran terakhir diperbarui. |

---

### 2.17 Tabel `banners` (Store D17)
Tabel banner promo bergerak hero slider pada bagian atas halaman beranda kafe.

| Nama Kolom | Tipe Data | Nullable | Kunci / Indeks | Nilai Default | Relasi | Keterangan & Aturan |
| :--- | :--- | :---: | :---: | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Tidak | PK | Auto Increment | - | Identifikator unik banner promo. |
| `title` | VARCHAR(255) | Tidak | - | - | - | Judul promo utama pada banner. |
| `subtitle` | VARCHAR(255) | Ya | - | NULL | - | Subtitle pelengkap penjelas penawaran promo. |
| `description` | TEXT | Ya | - | NULL | - | Rincian promo, syarat dan ketentuan. |
| `media_type` | ENUM('image','video')| Tidak | - | `'image'` | - | Tipe berkas media promo: `'image'` atau `'video'`. |
| `media_path` | VARCHAR(255) | Tidak | - | - | - | Berkas di `public/uploads/banners/` (foto min 1600px / video MP4). |
| `layout_mode` | VARCHAR(30) | Tidak | - | `'poster'` | - | Mode tampilan: `'poster'`, `'overlay'`, `'split'`. |
| `start_time` | DATETIME | Tidak | - | - | - | Tanggal dan jam banner mulai ditampilkan di beranda. |
| `end_time` | DATETIME | Tidak | - | - | - | Tanggal dan jam banner otomatis berhenti tayang (*auto-expiry*). |
| `button_text` | VARCHAR(100) | Ya | - | `'Pesan Sekarang'`| - | Label teks pada tombol ajakan bertindak (CTA). |
| `button_link` | VARCHAR(255) | Ya | - | `'#menu'` | - | Target URL saat tombol CTA diklik (default: `#menu`). |
| `is_active` | BOOLEAN | Tidak | - | `true` | - | Saklar manual admin (true = aktif, false = dihentikan manual). |
| `sort_order` | INT | Tidak | - | `0` | - | Urutan giliran tampil slide banner di hero carousel. |
| `created_at` | TIMESTAMP | Ya | - | NULL | - | Waktu banner didaftarkan. |
| `updated_at` | TIMESTAMP | Ya | - | NULL | - | Waktu data banner diperbarui. |

---

## 3. Kamus Data Tabel Penunjang Framework (S1 s/d S4)

### 3.1 Tabel `password_reset_tokens` (Store S1)
Tabel standar Laravel untuk token pemulihan kata sandi.
- `email` (VARCHAR 255, PK)
- `token` (VARCHAR 255, Not Null) — Hash token reset sandi.
- `created_at` (TIMESTAMP, Nullable) — Waktu pembuatan token.

### 3.2 Tabel `sessions` (Store S2)
Tabel sesi penanganan status autentikasi peramban pengguna.
- `id` (VARCHAR 255, PK) — ID sesi acak peramban.
- `user_id` (BIGINT UNSIGNED, Nullable, Index, FK -> `users.id`) — ID pengguna terautentikasi.
- `ip_address` (VARCHAR 45, Nullable) — Alamat IP klien peramban.
- `user_agent` (TEXT, Nullable) — Identitas perangkat dan browser klien.
- `payload` (LONGTEXT, Not Null) — Objek data sesi terenkripsi.
- `last_activity` (INT, Not Null, Index) — Stempel waktu UNIX aktivitas terakhir.

### 3.3 Tabel `cache` dan `cache_locks` (Store S3)
Tabel penyimpanan sementara objek dan kuota sistem.
- `cache`: `key` (VARCHAR 255, PK), `value` (MEDIUMTEXT, Not Null), `expiration` (BIGINT, Index).
- `cache_locks`: `key` (VARCHAR 255, PK), `owner` (VARCHAR 255, Not Null), `expiration` (BIGINT, Index).
- Digunakan sistem untuk men-cache ulasan Google Maps Places Details selama 6 jam (`nucomu_google_places_reviews`).

### 3.4 Tabel `jobs`, `job_batches`, dan `failed_jobs` (Store S4)
Tabel antrean pemrosesan tugas asinkron Laravel.
- `jobs`: `id` (BIGINT PK), `queue` (VARCHAR Index), `payload` (LONGTEXT), `attempts` (SMALLINT), `reserved_at`, `available_at`, `created_at`.
- `failed_jobs`: `id` (BIGINT PK), `uuid` (VARCHAR UQ), `connection` (TEXT), `queue` (TEXT), `payload` (LONGTEXT), `exception` (LONGTEXT), `failed_at` (TIMESTAMP).
