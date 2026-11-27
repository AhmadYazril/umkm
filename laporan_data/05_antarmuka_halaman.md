# TAHAP 5: ANTARMUKA PENGGUNA (HALAMAN, ATURAN & PESAN VALIDASI)
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Pendekatan UI:** Responsive Mobile-First Web, Tailwind CSS v4, Dark Warm Coffee Aesthetic

---

## 1. Daftar Seluruh Halaman Sistem

### 1.1 Halaman Publik & Pelanggan (*Customer Interface*)
| No | Nama Halaman | Rute URL | Berkas View Blade | Aktor | Fungsi Halaman | Elemen Utama Antarmuka |
|---|---|---|---|---|---|---|
| 1 | Beranda Utama (*Landing Page*) | `GET /` | `resources/views/home/index.blade.php` | Pelanggan | Menampilkan profil lengkap kafe, hero banner, layanan, menu unggulan, fasilitas, galeri, event, ulasan & kontak | Tombol CTA 'Pesan Sekarang', slider banner promo, card grid menu, tombol filter kategori, modal review |
| 2 | Katalog Menu Lengkap | `GET /menu` | `resources/views/menu/index.blade.php` | Pelanggan | Halaman etalase lengkap seluruh produk makanan & minuman kafe | Search input real-time, pill filter kategori, kartu menu (foto, harga, tombol '+ Keranjang') |
| 3 | Detail Modal Menu | Dynamic Modal | `resources/views/components/menu-modal.blade.php` | Pelanggan | Menampilkan varian suhu (Hot/Ice), tingkat gula, topping tambahan, dan catatan pesanan | Radio button varian, checkbox level gula, tombol counter kuantitas [ - 1 + ], tombol 'Tambah ke Keranjang' |
| 4 | Keranjang Belanja (*Cart Drawer*) | Sisi Kanan (Off-canvas) | `resources/views/components/cart-drawer.blade.php` | Pelanggan | Panel geser keranjang mandiri, kalkulasi subtotal, dan pemilih 3 mode checkout | Daftar item pesanan, input nama pemesan, input nomor meja/alamat, tombol radio 3 mode, tombol 'Lanjutkan Pesanan' |
| 5 | Slip QR Pesanan Mandiri | `GET /qr/pesanan/{code}` | `resources/views/admin/orders/qr-detail.blade.php` | Pelanggan / Kasir | Menampilkan kartu slip digital pesanan mandiri beserta kode barcode QR besar | Citra Barcode QR, kode NCM-xxx, rincian pesanan, total tagihan, panduan pembayaran kasir |
| 6 | Pelacakan Live GPS Kurir | `GET /zendo/lacak/{token}` | `resources/views/zendo/tracking.blade.php` | Pelanggan / Kurir | Menampilkan peta interaktif rute kurir, posisi live marker, dan status pengantaran | Peta Leaflet.js layar penuh, kartu status pengantaran, tombol panggilan darurat kurir/toko |
| 7 | Formulir Checkout Warisan (*Legacy*) | `GET /checkout` | `resources/views/order/checkout.blade.php` | Pelanggan | Halaman pemesanan berbasis sesi server lama | Formulir data pemesan, tabel ringkasan pesanan, tombol 'Kirim Pesanan' |
| 8 | Sukses Checkout Warisan | `POST /checkout` | `resources/views/order/success.blade.php` | Pelanggan | Konfirmasi transaksi selesai pada alur checkout lama | Banner centang sukses, nomor order, tombol 'Kembali ke Beranda' |

### 1.2 Halaman Kasir POS (*Cashier Interface*)
| No | Nama Halaman | Rute URL | Berkas View Blade | Aktor | Fungsi Halaman | Elemen Utama Antarmuka |
|---|---|---|---|---|---|---|
| 9 | Login Staf Kasir | `GET /kasir/login` | `resources/views/kasir/login.blade.php` | Kasir | Otentikasi cepat staf kasir dengan kata sandi | Form input password kasir, tombol submit 'Buka Kasir', pesan peringatan error |
| 10 | Antarmuka Kasir POS Utama | `GET /kasir` | `resources/views/kasir/index.blade.php` | Kasir | Layar kerja sentral kasir: katalog menu, keranjang POS, scanner slip QR, dan riwayat pesanan | Grid katalog sajian, panel tagihan kanan, tombol input cepat uang bayar, tombol 'Scan QR', modal stok cepat |
| 11 | Lembar Struk Termal | `GET /kasir/pesanan/{order}/struk` | `resources/views/kasir/receipt.blade.php` | Kasir | Tampilan cetak bukti pembayaran format kertas thermal 58mm / 80mm | Header kafe, detail item monospace, subtotal, nominal bayar, uang kembali, footer pesan ramah |

### 1.3 Halaman Panel Administrator (*Admin Interface*)
| No | Nama Halaman | Rute URL | Berkas View Blade | Aktor | Fungsi Halaman | Elemen Utama Antarmuka |
|---|---|---|---|---|---|---|
| 12 | Login Administrator | `GET /admin/login` | `resources/views/admin/login.blade.php` | Admin | Masuk ke dashboard kendali sistem | Form input email & password, remember me, tombol submit 'Masuk' |
| 13 | Dasbor Utama (*Dashboard*) | `GET /admin/dashboard` | `resources/views/admin/dashboard.blade.php` | Admin | Ringkasan metrik omzet, grafik tren, menu terlaris, dan status reservasi | 4 kartu metrik KPI, grafik Chart.js tren keuangan, tabel Top 5 menu, saklar toggle chart |
| 14 | Manajemen Banner Promo | `GET /admin/banner` | `resources/views/admin/banners/index.blade.php` | Admin | Daftar banner promosi aktif, terjadwal, dan expired | Tabel banner, tombol 'Tambah Banner', tombol 'Perpanjang', badge status, tombol hapus |
| 15 | Tambah Banner Promo | `GET /admin/banner/create` | `resources/views/admin/banners/create.blade.php` | Admin | Form upload banner dan penjadwalan | Input judul, file upload banner (16:9), datetime start/end time, tombol simpan |
| 16 | Edit Banner Promo | `GET /admin/banner/{id}/edit` | `resources/views/admin/banners/edit.blade.php` | Admin | Perubahan data banner dan durasi | Form pratinjau gambar, input waktu penayangan, tombol submit pembaruan |
| 17 | Kelola Profil Our Story | `GET /admin/about` | `resources/views/admin/about/index.blade.php` | Admin | Mengedit narasi tentang kafe, filosofi, dan foto | Textarea deskripsi sejarah, unggah foto profil, tombol simpan pengaturan |
| 18 | Manajemen Fasilitas | `GET /admin/fasilitas` | `resources/views/admin/facilities/index.blade.php` | Admin | CRUD kartu fasilitas kafe | Tabel fasilitas, modal form input (nama, badge, ikon, checklist fitur JSON, upload foto) |
| 19 | Manajemen Galeri Foto | `GET /admin/galeri` | `resources/views/admin/gallery/index.blade.php` | Admin | Unggah dan kurasi foto portofolio kafe | Grid thumbnail foto, form upload multi-gambar, filter kategori galeri, tombol hapus |
| 20 | Manajemen Agenda & Event | `GET /admin/event` | `resources/views/admin/events/index.blade.php` | Admin | Penjadwalan workshop kopi dan live music | Tabel agenda acara, modal form tambah event (judul, tanggal, jam, poster), tombol aksi |
| 21 | Master Kategori Menu | `GET /admin/kategori` | `resources/views/admin/categories/index.blade.php` | Admin | CRUD kategori induk menu | Tabel kategori, modal input nama & ikon, tombol proteksi hapus jika ada menu |
| 22 | Master Katalog Menu | `GET /admin/menu` | `resources/views/admin/menus/index.blade.php` | Admin | Tabel daftar seluruh menu kafe | Tabel menu, tombol 'Tambah Menu', switch ketersediaan, stok cepat, tombol edit, tombol soft delete |
| 23 | Form Tambah / Edit Menu | `GET /admin/menu/tambah` | `resources/views/admin/menus/form.blade.php` | Admin | Formulir lengkap entri produk makanan/minuman | Dropdown kategori, input nama, harga, stok, badge, upload foto 1:1, repeater opsi varian |
| 24 | Kurasi Testimoni Pelanggan | `GET /admin/testimoni` | `resources/views/admin/testimonials/index.blade.php` | Admin | Moderasi ulasan ulasan pelanggan | Tabel ulasan, tombol toggle 'Pin ke Beranda', tombol sembunyikan, tombol hapus spam |
| 25 | Pemantauan Reservasi Masuk | `GET /admin/reservasi` | `resources/views/admin/reservations/index.blade.php` | Admin | Daftar pengajuan reservasi dan Form Builder | Tabel daftar tamu reservasi, dropdown ubah status (pending/confirmed), tab Form Builder dinamis |
| 26 | Manajemen Pesanan & Transaksi| `GET /admin/pesanan` | `resources/views/admin/orders/index.blade.php` | Admin | Tabel seluruh riwayat pesanan pelanggan | Filter tanggal, tab status, tombol ekspor rekap PDF/CSV, modal konfigurasi 3 mode transaksi |
| 27 | Detail Transaksi Pesanan | `GET /admin/pesanan/{id}` | `resources/views/admin/orders/show.blade.php` | Admin | Pemeriksaan rincian transaksi | Rincian item menu, data pengantaran kurir, log catatan, tombol ubah status pesanan |
| 28 | Scanner Slip QR Admin | `GET /admin/pesanan/scan` | `resources/views/admin/orders/scan.blade.php` | Admin | Pemindaian barcode kamera di sisi admin | Viewfinder kamera video scanner QR, input teks pencarian kode pesanan darurat |
| 29 | Manajemen Kurir & Ongkir Zendo | `GET /admin/zendo` | `resources/views/admin/zendo/index.blade.php` | Admin | Konfigurasi armada pengantaran kurir | Form daftar nomor WhatsApp kurir, form tarif ongkir jarak bertingkat, tombol reset perangkat |
| 30 | Master Nomor Meja | `GET /admin/pengaturan/meja` | `resources/views/admin/settings/tables.blade.php` | Admin | CRUD nomor meja fisik kafe | Tabel meja kafe, modal tambah nomor meja, kapasitas kursi, status meja |
| 31 | Jam Kerja Operasional | `GET /admin/pengaturan/jam-operasional` | `resources/views/admin/settings/operating-hours.blade.php` | Admin | Pengaturan jam buka-tutup kafe 7 hari | Tabel 7 baris hari (Senin-Minggu), input jam buka, jam tutup, checkbox libur |
| 32 | Pengaturan Kontak & Sosmed | `GET /admin/contact` | `resources/views/admin/contact/index.blade.php` | Admin | Pengaturan alamat, nomor kontak, dan sosmed | Form input nomor WhatsApp kafe, Instagram, TikTok, koordinat GPS kafe, tombol simpan |
| 33 | Profil Pribadi Akun Admin | `GET /admin/profile` | `resources/views/admin/profile/index.blade.php` | Admin | Pengelolaan data akun dan keamanan | Form ubah nama & email, upload foto avatar profil, form ubah kata sandi dengan konfirmasi |
| 34 | Pusat Notifikasi Real-time | `GET /admin/notifications`| `resources/views/admin/notifications/index.blade.php` | Admin | Log aktivitas pesanan dan reservasi baru | Daftar kartu notifikasi masuk, indikator belum dibaca, tombol 'Tandai Semua Dibaca' |
| 35 | Panduan Bantuan Sistem | `GET /admin/help` | `resources/views/admin/help/index.blade.php` | Admin | Dokumentasi operasional staf kafe | Seksi FAQ, panduan cetak struk, panduan konfigurasi mode transaksi |

---

## 2. Aturan Antarmuka & Pedoman Desain (UI Style Guide)

### 2.1 Kerangka Layout Utama (*Master Layouts*)
1. **`layouts/app.blade.php` (Publik / Pelanggan):**
   - Menggunakan tema *Dark Warm Aesthetic* (Nucomu Coffee Roast).
   - Memuat komponen navigasi terpadu (`components/navbar.blade.php`), laci keranjang melayang (`components/cart-drawer.blade.php`), modal interaktif varian (`components/menu-modal.blade.php`), dan footer kafe (`components/footer.blade.php`).
   - Memuat skrip sisi klien murni (Vanilla JavaScript) untuk sinkronisasi LocalStorage keranjang.
2. **`layouts/admin.blade.php` (Panel Administrator):**
   - Menggunakan tata letak dua kolom: Sidebar navigasi di sisi kiri (gelap pekat) dan area konten kerja di sisi kanan.
   - Dilengkapi topbar status admin, tombol cepat pintasan kasir, lencana notifikasi belum dibaca, dan dropdown profil.
3. **`kasir/index.blade.php` (Antarmuka Kasir POS Mandiri):**
   - Tata letak layar penuh (*fullscreen app*) teroptimasi untuk perangkat tablet atau layar sentuh kasir tanpa gangguan navigasi web biasa.
4. **`zendo/tracking.blade.php` (Antarmuka Pelacakan Peta Mandiri):**
   - Tampilan interaktif responsif yang mendahulukan kontainer peta Leaflet.js dengan kartu *floating overlay* status pengantaran di bagian bawah.

### 2.2 Palet Warna Resmi (*Color Palette*)
| Kategori Warna | Kode Hex | Nama Nuansa | Penggunaan pada Elemen Antarmuka |
|---|---|---|---|
| **Background Dark** | `#0C0C0C` / `#101010` | Obsidian Black | Latar belakang dasar halaman publik dan panel admin |
| **Coffee Roast Accent**| `#140E0A` / `#2A1A10` | Espresso Roast | Warna kartu konten, header tabel, dan sidebar admin |
| **Warm Crimson Accent**| `#7A0210` / `#D10A24` | Coffee Cherry Red | Tombol aksi utama (CTA), tombol submit pesanan, badge promo |
| **Text Primary** | `#FAF8F5` | Latte Cream | Warna teks utama dan judul pada tema gelap |
| **Text Muted** | `#8F8F8F` / `#B5B5B5` | Muted Ash | Teks sekunder, deskripsi menu, dan petunjuk formulir |
| **Border & Divider** | `#242424` / `#431D0A` | Roast Contour | Garis pemisah antar seksi, batas sel tabel, garis modal |
| **Status Success** | `#16A34A` | Emerald Green | Badge status lunas (`paid`), pesanan selesai (`completed`) |
| **Status Warning** | `#EAB308` | Amber Gold | Badge status menunggu verifikasi (`pending`), pesanan diproses |
| **Status Danger** | `#DC2626` | Crimson Alert | Tombol hapus, badge status dibatalkan (`cancelled`) |

### 2.3 Tipografi & Font
- **Font Display & Judul Editorial:** `Cormorant Garamond` (Google Fonts, bobot 400, 600, 700). Digunakan pada judul utama hero beranda, nama seksi kafe, dan headline promo untuk memberikan kesan kafe artisan yang elegan.
- **Font Panel Admin:** `Fraunces` (Google Fonts, bobot 600) untuk tipografi header admin.
- **Font Teks Utama & Formulir:** `Plus Jakarta Sans` / System Sans-serif (`ui-sans-serif, system-ui, sans-serif`). Digunakan untuk keterbacaan tinggi pada teks isi, harga menu, dan label form.
- **Font Struk Termal:** `Courier New, Courier, monospace`. Digunakan khusus pada struk kasir untuk menjamin kelurusan kolom harga kasir saat dicetak.
- **Simbol Ikonografi:** `Material Symbols Outlined` (Google Icons) untuk ikon navigasi antarmuka.

### 2.4 Titik Henti Responsif (*Responsive Breakpoints*)
Antarmuka mengacu pada standar Tailwind CSS v4:
- `Mobile Default (< 640px)`: Tata letak kolom tunggal vertikal, laci navigasi hamburger, kartu menu 1 kolom, drawer keranjang layar penuh.
- `Tablet sm (>= 640px)`: Grid menu 2 kolom, tabel ringkasan transaksi kasir memuat tombol aksi.
- `Laptop md (>= 768px)`: Grid menu 3 kolom, modal dialog dengan ukuran proporsional tengah layar.
- `Desktop lg (>= 1024px)`: Sidebar admin menetap di kiri, grid menu 4 kolom, drawer keranjang off-canvas di sisi kanan dengan lebar 420px.

---

## 3. Daftar Pesan Validasi dan Kesalahan Asli (*Error Messages*)
Kutipan teks asli pesan validasi yang didefinisikan secara eksplisit di dalam kode kontroler (*Controllers*):

### 3.1 Pesan Kesalahan Autentikasi Staf (Admin & Kasir)
1. `"Email dan password admin wajib diisi."` (Admin login)
2. `"Kredensial yang Anda masukkan tidak cocok dengan data kami."` (Admin login salah)
3. `"Akun Anda tidak memiliki hak akses administrator."` (Admin otorisasi ditolak)
4. `"Kata sandi kasir wajib diisi."` (Kasir login)
5. `"Kata sandi kasir yang Anda masukkan salah."` (Kasir login salah)
6. `"Password saat ini wajib diisi untuk keamanan."` (Ubah password admin)
7. `"Password saat ini yang Anda masukkan salah."` (Ubah password admin)
8. `"Password baru minimal harus terdiri dari 8 karakter."` (Ubah password admin)
9. `"Konfirmasi password baru tidak cocok. Harap periksa kembali."` (Ubah password admin)

### 3.2 Pesan Kesalahan Keranjang & Checkout Transaksi
1. `"Keranjang pesanan masih kosong."` (`NucomuController@storeOrder`)
2. `"Jumlah pesanan (qty) untuk setiap menu harus berupa bilangan bulat minimal 1."` (`NucomuController@storeOrder`)
3. `"Menu ':name' sedang tidak tersedia saat ini."` (`NucomuController@storeOrder`)
4. `"Stok menu ':name' tidak mencukupi (tersisa :stock porsi)."` (`NucomuController@storeOrder`)
5. `"Nama lengkap pemesan wajib diisi."` (`NucomuController@storeOrder`)
6. `"Alamat pengiriman wajib dipilih pada peta atau diisi untuk pengiriman Kurir Zendo."` (`NucomuController@storeOrder`)
7. `"Nomor meja wajib dipilih untuk pesanan makan di tempat (Dine In)."` (`NucomuController@storeOrder`)
8. `"Mode pemesanan makan di tempat sedang dinonaktifkan oleh kafe."` (`NucomuController@storeOrder`)
9. `"Mode pemesanan pesan antar Zendo sedang dinonaktifkan oleh kafe."` (`NucomuController@storeOrder`)

### 3.3 Pesan Kesalahan Reservasi Meja & Form Builder
1. `"Nama lengkap pemesan reservasi wajib diisi."` (`NucomuController@storeReservation`)
2. `"Nomor WhatsApp aktif wajib diisi untuk konfirmasi reservasi."` (`NucomuController@storeReservation`)
3. `"Tanggal reservasi wajib diisi."` (`NucomuController@storeReservation`)
4. `"Tanggal reservasi tidak boleh berupa tanggal yang sudah lewat."` (`NucomuController@storeReservation`)
5. `"Jam reservasi wajib diisi."` (`NucomuController@storeReservation`)
6. `"Jumlah tamu reservasi minimal 1 orang."` (`NucomuController@storeReservation`)
7. `"Kolom :label wajib diisi."` (`NucomuController@storeReservation` dinamis Form Builder)
8. `"Berkas unggahan untuk :label maksimal berukuran 2 MB."` (Form Builder file upload)

### 3.4 Pesan Kesalahan Manajemen Menu, Kategori & Banner
1. `"Kategori ini tidak dapat dihapus karena masih memiliki :count menu terkait."` (`Admin\CategoryController@destroy`)
2. `"Nama menu sajian wajib diisi."` (`Admin\MenuController@store`)
3. `"Harga menu wajib berupa angka positif."` (`Admin\MenuController@store`)
4. `"Foto menu harus berupa berkas gambar (JPG, JPEG, PNG, WebP) dengan rasio 1:1 maksimal 2 MB."` (`Admin\MenuController@store`)
5. `"Menu ini tidak dapat dihapus permanen karena memiliki riwayat transaksi di kasir. Silakan gunakan soft delete."` (`Admin\MenuController@forceDelete`)
6. `"Judul promosi banner wajib diisi."` (`Admin\BannerController@store`)
7. `"Waktu selesai tayang banner harus lebih besar dari waktu mulai tayang."` (`Admin\BannerController@store`)

### 3.5 Pesan Kesalahan Logistik & Kurir Zendo
1. `"PIN kurir wajib terdiri dari 4 hingga 6 digit angka."` (`ZendoTrackingController@verifyPinAndBindDevice`)
2. `"PIN kurir yang Anda masukkan salah atau tidak terdaftar."` (`ZendoTrackingController@verifyPinAndBindDevice`)
3. `"Sesi pengantaran ini telah diikat oleh perangkat kurir lain. Silakan minta admin untuk me-reset ikatan perangkat."` (`ZendoTrackingController@verifyPinAndBindDevice`)
4. `"Koordinat GPS tidak valid atau sinyal satelit terlalu lemah (akurasi > 100m)."` (`ZendoTrackingController@updateLocation`)
5. `"Kecepatan terdeteksi tidak wajar (Anti-Fake GPS Terpicu)."` (`ZendoTrackingController@updateLocation`)
