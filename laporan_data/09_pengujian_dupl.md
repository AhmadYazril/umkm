# TAHAP 9: PENGUJIAN BLACK BOX (DUPL) DAN KETERUNUTAN
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Metode Pengujian:** Pengujian Kotak Hitam (*Black Box Testing*) & Keterunutan SKPL-DUPL

---

## 1. Rencana Pengujian Black Box

| No | Modul / Proses | Deskripsi Pengujian | Jenis Pengujian | Kode DUPL | Kode SKPL Terkait |
|---|---|---|---|---|---|
| 1 | Autentikasi Admin | Validasi login email & password admin valid dan tidak valid | Black Box | `DUPL-NCM.K-0001` | `SKPL-NCM.K-0033` |
| 2 | Autentikasi Kasir | Validasi login staf kasir dengan kata sandi kasir | Black Box | `DUPL-NCM.K-0002` | `SKPL-NCM.K-0019` |
| 3 | Katalog Menu | Pencarian menu berdasarkan kata kunci dan filter kategori | Black Box | `DUPL-NCM.K-0003` | `SKPL-NCM.K-0008` |
| 4 | Keranjang Belanja | Tambah menu, pilih varian suhu, dan hitung subtotal di cart drawer | Black Box | `DUPL-NCM.K-0004` | `SKPL-NCM.K-0010` |
| 5 | Checkout Mode 1 | Checkout makan di tempat (Dine In) dan terbitnya slip QR (NCM-xxx) | Black Box | `DUPL-NCM.K-0005` | `SKPL-NCM.K-0011` |
| 6 | Checkout Mode 2 | Checkout pesan antar via format chat WhatsApp Zendo | Black Box | `DUPL-NCM.K-0006` | `SKPL-NCM.K-0013` |
| 7 | Checkout Mode 3 | Checkout pesan antar dengan koordinat GPS dan live tracking | Black Box | `DUPL-NCM.K-0007` | `SKPL-NCM.K-0014` |
| 8 | Reservasi Meja | Pengajuan reservasi dinamis beserta kolom Form Builder | Black Box | `DUPL-NCM.K-0008` | `SKPL-NCM.K-0007` |
| 9 | Kirim Ulasan | Pengiriman testimoni kepuasan pelanggan dan rating bintang | Black Box | `DUPL-NCM.K-0009` | `SKPL-NCM.K-0005` |
| 10 | POS Kasir & Scan | Pemindaian barcode kamera slip QR dan kalkulator pembayaran kasir | Black Box | `DUPL-NCM.K-0010` | `SKPL-NCM.K-0022` |
| 11 | Cetak Struk | Pencetakan struk termal bukti pembayaran kasir | Black Box | `DUPL-NCM.K-0011` | `SKPL-NCM.K-0025` |
| 12 | Update Stok Cepat | Perubahan jumlah stok menu langsung dari antarmuka kasir | Black Box | `DUPL-NCM.K-0012` | `SKPL-NCM.K-0026` |
| 13 | Kurir & Anti-Fake GPS | Verifikasi PIN kurir, pengikatan perangkat, dan deteksi anomali GPS | Black Box | `DUPL-NCM.K-0013` | `SKPL-NCM.K-0028` |
| 14 | CRUD Menu & Soft Delete | Tambah menu baru, validasi foto 1:1, soft delete, dan restore | Black Box | `DUPL-NCM.K-0014` | `SKPL-NCM.K-0044` |
| 15 | Banner Promo 30 Mnt | Deteksi auto-expiry banner promo dalam rentang 30 menit | Black Box | `DUPL-NCM.K-0015` | `SKPL-NCM.K-0039` |
| 16 | Ekspor Laporan Penjualan | Ekspor laporan transaksi periode tertentu format PDF 1.4 Native & CSV | Black Box | `DUPL-NCM.K-0016` | `SKPL-NCM.K-0060` |

---

## 2. Rincian Kasus Uji Black Box (DUPL)

### DUPL-NCM.K-0001: Pengujian Login Administrator
**Kode SKPL Terkait:** `SKPL-NCM.K-0033`

- **Inisialisasi Lingkungan:** Aplikasi berjalan di web server lokal, peramban membuka URL `/admin/login`. Akun admin terdaftar di basis data (`admin@nucomu.com`).
- **Masukan Uji (*Test Inputs*):**
- **Data Benar:** Email: `admin@nucomu.com`, Password: `adminpassword123`
- **Data Salah:** Email: `admin@nucomu.com`, Password: `passwordsalah`
- **Data Kosong:** Email: ``, Password: ``
- **Hasil yang Diharapkan (*Expected Results*):**
1. Data Benar: Sistem mengarahkan ke `/admin/dashboard` dan cookie `nucomu_admin_auth_v1` tersimpan.
2. Data Salah: Sistem tetap di `/admin/login` dan memunculkan pesan error: 'Kredensial yang Anda masukkan tidak cocok dengan data kami.'
3. Data Kosong: Memunculkan pesan validasi: 'Email dan password admin wajib diisi.'
- **Kriteria Evaluasi:** Hanya kredensial valid yang dapat membuka halaman dasbor; sesi tidak bocor ke publik.
- **Prosedur Uji Bernomor:**
1. Buka peramban dan tuju `/admin/login`.
2. Masukkan email dan password salah, klik 'Masuk'. Amati pesan kesalahan.
3. Masukkan email dan password benar, klik 'Masuk'. Amati pengalihan halaman.
- **Asumsi dan Batasan:** Akun admin aktif dan memiliki role `'admin'` di basis data.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0002: Pengujian Login Cepat Staf Kasir POS
**Kode SKPL Terkait:** `SKPL-NCM.K-0019`

- **Inisialisasi Lingkungan:** Staf kasir membuka URL `/kasir/login`. Pengaturan kata sandi kasir telah tersimpan di sistem.
- **Masukan Uji (*Test Inputs*):**
- **Data Benar:** Password: `kasir123`
- **Data Salah:** Password: `kasirsalah`
- **Data Kosong:** Password: ``
- **Hasil yang Diharapkan (*Expected Results*):**
1. Data Benar: Mengarahkan ke antarmuka POS `/kasir`, cookie `nucomu_kasir_auth_v2` tersimpan 1 tahun.
2. Data Salah: Muncul pesan error 'Kata sandi kasir yang Anda masukkan salah.'
3. Data Kosong: Muncul pesan validasi 'Kata sandi kasir wajib diisi.'
- **Kriteria Evaluasi:** Kasir dapat mengakses POS tanpa mengetikkan email; keamanan dijamin oleh verifikasi hash.
- **Prosedur Uji Bernomor:**
1. Akses `/kasir/login`.
2. Ketik password salah, tekan Enter. Amati alert merah.
3. Ketik password benar, tekan Enter. Amati layar kerja kasir terbuka.
- **Asumsi dan Batasan:** Akun kasir dengan role `'kasir'` telah ter-*seed* di basis data.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0003: Pencarian Menu dan Filter Kategori
**Kode SKPL Terkait:** `SKPL-NCM.K-0008`

- **Inisialisasi Lingkungan:** Halaman `/menu` atau `/` telah memuat data katalog menu aktif.
- **Masukan Uji (*Test Inputs*):**
- **Kata Kunci Valid:** `Americano`
- **Kata Kunci Tidak Ada:** `Pizza Mozarella`
- **Pill Kategori:** Mengklik pill 'Signature Coffee'
- **Hasil yang Diharapkan (*Expected Results*):**
1. Kata Kunci 'Americano': Menampilkan kartu menu Americano.
2. Kata Kunci 'Pizza Mozarella': Grid menu kosong dengan teks pemberitahuan 'Menu tidak ditemukan.'
3. Filter Kategori: Menampilkan hanya menu yang memiliki `category_id` terkait.
- **Kriteria Evaluasi:** Penyaringan berjalan instan di sisi klien peramban tanpa memuat ulang seluruh halaman (*no page reload*).
- **Prosedur Uji Bernomor:**
1. Buka halaman katalog menu `/menu`.
2. Ketikkan 'Americano' pada kolom pencarian menu.
3. Klik tombol filter kategori 'Non-Coffee'.
- **Asumsi dan Batasan:** Tabel `menus` dan `categories` memiliki data uji.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0004: Pengelolaan Keranjang Belanja Mandiri (Cart Drawer)
**Kode SKPL Terkait:** `SKPL-NCM.K-0010`

- **Inisialisasi Lingkungan:** Pengunjung membuka beranda kafe dengan keranjang belanja masih kosong.
- **Masukan Uji (*Test Inputs*):**
- Klik '+ Keranjang' pada menu 'Kopi Susu Nucomu' (Rp 18.000).
- Ubah kuantitas menjadi 3 porsi.
- Pilih varian 'Ice' dan level gula 'Less Sugar'.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Drawer keranjang terbuka otomatis menampilkan item terpilih.
2. Subtotal terhitung otomatis: 3 x Rp 18.000 = Rp 54.000.
3. Data tersimpan di `window.localStorage['nucomu_cart']` dan tidak hilang saat halaman di-refresh.
- **Kriteria Evaluasi:** Perhitungan subtotal akurat dan kuantitas minimal bernilai 1.
- **Prosedur Uji Bernomor:**
1. Buka beranda `/`.
2. Klik kartu menu 'Kopi Susu Nucomu', atur varian pada modal, klik 'Tambah ke Keranjang'.
3. Klik tombol tambah [ + ] pada kuantitas di dalam Cart Drawer.
4. Segarkan halaman peramban dan periksa laci keranjang.
- **Asumsi dan Batasan:** Peramban mendukung Web Storage API (LocalStorage).
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0005: Checkout Mandiri Mode 1 (Dine In & Slip QR NCM-xxx)
**Kode SKPL Terkait:** `SKPL-NCM.K-0011`

- **Inisialisasi Lingkungan:** Keranjang belanja memuat minimal 1 item menu.
- **Masukan Uji (*Test Inputs*):**
- **Data Benar:** Mode: 'Makan di Tempat', Nama: 'Budi Santoso', Nomor Meja: '04', Pembayaran: 'Tunai di Kasir'.
- **Data Salah (Tanpa Meja):** Nomor Meja: ``, Nama: 'Budi Santoso'.
- **Data Salah (Keranjang Kosong):** Mengirim POST `/pesan` dengan array `items: []`.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Data Benar: Transaksi tersimpan ke `orders` dengan status `pending`, kode `NCM-Ymd-xxx`, stok menu berkurang, diarahkan ke slip digital `/qr/pesanan/{code}`.
2. Tanpa Meja: Respon error 422 'Nomor meja wajib dipilih untuk pesanan makan di tempat (Dine In).'
3. Keranjang Kosong: Respon error 422 'Keranjang pesanan masih kosong.'
- **Kriteria Evaluasi:** Transaksi tersimpan atomik via DB::transaction dan kode QR barcode berhasil digenerate.
- **Prosedur Uji Bernomor:**
1. Buka keranjang belanja, pilih mode 'Makan di Tempat'.
2. Masukkan nama dan pilih meja '04'.
3. Klik 'Kirim Pesanan'. Amati halaman slip QR terbuka.
- **Asumsi dan Batasan:** Nomor meja '04' terdaftar aktif di tabel `tables`.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0006: Checkout Mandiri Mode 2 (Chat WhatsApp Zendo)
**Kode SKPL Terkait:** `SKPL-NCM.K-0013`

- **Inisialisasi Lingkungan:** Keranjang belanja memuat item menu. Nomor WhatsApp Zendo kafe telah diatur di `settings`.
- **Masukan Uji (*Test Inputs*):**
- Mode: 'Pesan Antar (Chat Zendo)', Nama: 'Siti Aminah', Alamat: 'Jl. Ahmad Yani No. 15, Tulungagung'.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Pesanan tersimpan di basis data dengan `order_type = take_away` dan status `processing`.
2. Peramban membuka tab baru tautan `https://wa.me/{nomor}?text={pesan_terformat}`.
3. Isi pesan WhatsApp memuat rincian item, subtotal, alamat, dan perkiraan ongkir.
- **Kriteria Evaluasi:** Format teks draf WhatsApp rapi sesuai template kafe.
- **Prosedur Uji Bernomor:**
1. Masukkan menu ke keranjang.
2. Pilih mode 'Antar via Chat Zendo'.
3. Isi nama dan alamat pengantaran, klik 'Lanjutkan ke WhatsApp'.
- **Asumsi dan Batasan:** Perangkat terhubung dengan aplikasi WhatsApp Web atau WhatsApp Mobile.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0007: Checkout Mandiri Mode 3 (Antar GPS & Pelacakan Live)
**Kode SKPL Terkait:** `SKPL-NCM.K-0014`

- **Inisialisasi Lingkungan:** Keranjang belanja terisi, mode Zendo Tracking aktif di `settings`.
- **Masukan Uji (*Test Inputs*):**
- Mode: 'Antar Kurir Zendo', Nama: 'Ahmad Faiz', Pin Peta: Lat -8.065, Lng 111.905 (Jarak 2.5 km), Alamat: 'Perum Grahadi Regency B-12'.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Server menghitung jarak Haversine (2.5 km) dan menetapkan tarif Tier 1 (Rp 10.000).
2. Total akhir = Subtotal + Rp 10.000.
3. Record pengantaran kurir terbit di `zendo_deliveries`.
4. Pelanggan dialihkan ke URL live tracking `/zendo/lacak/{token}`.
- **Kriteria Evaluasi:** Perhitungan ongkir bertingkat server presisi dan token pelacakan terbit.
- **Prosedur Uji Bernomor:**
1. Buka keranjang, pilih mode 'Antar Kurir Zendo'.
2. Geser pin peta Leaflet ke lokasi rumah pelanggan.
3. Amati kalkulasi ongkir berubah otomatis.
4. Klik 'Pesan Sekarang'.
- **Asumsi dan Batasan:** Titik koordinat kafe tersimpan di konfigurasi `settings`.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0008: Pengajuan Reservasi Meja Dinamis
**Kode SKPL Terkait:** `SKPL-NCM.K-0007`

- **Inisialisasi Lingkungan:** Pengunjung membuka seksi reservasi di beranda.
- **Masukan Uji (*Test Inputs*):**
- **Data Benar:** Nama: 'Hendra Wijaya', No WA: '081234567890', Tanggal: Besok, Jam: '19:00', Jumlah: 4 Orang.
- **Data Salah (Tanggal Lampau):** Tanggal: Kemarin.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Data Benar: Record tersimpan di tabel `reservations` status `pending`, muncul toast sukses, membuka konfirmasi WA admin.
2. Tanggal Lampau: Muncul pesan error 'Tanggal reservasi tidak boleh berupa tanggal yang sudah lewat.'
- **Kriteria Evaluasi:** Validasi tanggal masa depan ketat dan field kustom Form Builder terekam.
- **Prosedur Uji Bernomor:**
1. Scroll ke bagian formulir reservasi beranda.
2. Coba pilih tanggal kemarin dan klik 'Kirim Reservasi'. Amati pesan error.
3. Pilih tanggal besok lusa dan isi data lengkap, klik 'Kirim Reservasi'.
- **Asumsi dan Batasan:** Nomor WhatsApp admin reservasi telah disetel di `settings`.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0009: Pengiriman Ulasan Kepuasan Pelanggan
**Kode SKPL Terkait:** `SKPL-NCM.K-0005`

- **Inisialisasi Lingkungan:** Pengunjung membuka modal 'Tulis Ulasan' pada seksi ulasan beranda.
- **Masukan Uji (*Test Inputs*):**
- **Data Benar:** Nama: 'Rina Kartika', Rating: 5 Bintang, Komentar: 'Kopi Butterscloud terenak di Tulungagung!', Foto: berkas `test.jpg` (500 KB).
- **Data Salah (Komentar Kosong):** Komentar: ``.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Data Benar: Record tersimpan di tabel `testimonials` dengan status `is_pinned = 0` (unpinned), foto tersimpan di `public/uploads/reviews/`.
2. Komentar Kosong: Muncul pesan validasi 'Ulasan pengalaman wajib diisi.'
- **Kriteria Evaluasi:** Ulasan baru tidak langsung tampil di beranda sebelum dikurasi (di-pin) oleh admin.
- **Prosedur Uji Bernomor:**
1. Klik tombol 'Tulis Ulasan' di beranda.
2. Beri bintang 5, ketik komentar, unggah foto bukti.
3. Klik 'Kirim Ulasan'.
- **Asumsi dan Batasan:** Direktori `public/uploads/reviews/` memiliki izin tulis.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0010: Pemindaian Slip QR & Pelunasan Transaksi Kasir
**Kode SKPL Terkait:** `SKPL-NCM.K-0022`

- **Inisialisasi Lingkungan:** Kasir login aktif pada antarmuka `/kasir`. Terdapat pesanan belum lunas `NCM-20261102-001` sebesar Rp 45.000.
- **Masukan Uji (*Test Inputs*):**
- Input pencarian: `NCM-20261102-001`.
- Nominal uang tunai diserahkan: Rp 50.000.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Rincian pesanan tampil di layar kasir dengan sisa tagihan Rp 45.000.
2. Kalkulator kembalian otomatis menampilkan: Rp 5.000.
3. Setelah klik 'Bayar Lunas', status pesanan berubah menjadi `paid` dan `completed` di basis data.
4. Dialog cetak struk termal otomatis terbuka.
- **Kriteria Evaluasi:** Status pembayaran berhasil dimutasi dan kembalian dihitung akurat.
- **Prosedur Uji Bernomor:**
1. Kasir mengetikkan kode NCM di kotak pencarian atau memindai QR ponsel pelanggan.
2. Periksa daftar item menu dan total tagihan.
3. Klik tombol pecahan cepat '50.000'.
4. Klik tombol 'Bayar Tunai & Cetak Struk'.
- **Asumsi dan Batasan:** Koneksi printer kasir termal siap.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0011: Pencetakan Struk Termal Kasir
**Kode SKPL Terkait:** `SKPL-NCM.K-0025`

- **Inisialisasi Lingkungan:** Transaksi berstatus lunas dibuka pada URL `/kasir/pesanan/{id}/struk`.
- **Masukan Uji (*Test Inputs*):**
- Menekan pintasan cetak atau memicu `window.print()`.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Halaman menampilkan struk monokrom monospace 58mm / 80mm.
2. CSS `@media print` menyembunyikan tombol navigasi dan hanya menyisakan area kertas struk.
3. Informasi alamat kafe, daftar item, subtotal, bayar, dan kembali tercetak jelas.
- **Kriteria Evaluasi:** Tata letak font monospace rapi dan rata kanan pada kolom nominal uang.
- **Prosedur Uji Bernomor:**
1. Buka URL struk pesanan lunas.
2. Periksa dialog preview printer peramban.
3. Lakukan cetak uji ke printer termal.
- **Asumsi dan Batasan:** Driver printer thermal 58mm/80mm terpasang di sistem operasi kasir.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0012: Pembaruan Stok Cepat Menu Kasir
**Kode SKPL Terkait:** `SKPL-NCM.K-0026`

- **Inisialisasi Lingkungan:** Kasir login aktif pada `/kasir`. Menu 'Cappuccino' memiliki sisa stok 10 porsi.
- **Masukan Uji (*Test Inputs*):**
- Ubah nilai stok menjadi `0` porsi.
- Matikan switch ketersediaan (*is_available = false*).
- **Hasil yang Diharapkan (*Expected Results*):**
1. Tabel `menus` terupdate `stock = 0` dan `is_available = 0`.
2. Peta cadangan stok di tabel `settings` tersinkronisasi.
3. Kartu menu pada katalog publik dan katalog kasir otomatis berubah status menjadi 'Habis / Sold Out'.
- **Kriteria Evaluasi:** Pencegahan pemesanan menu yang stok fisiknya telah habis.
- **Prosedur Uji Bernomor:**
1. Pada layar kasir, klik tombol ikon stok cepat pada menu Cappuccino.
2. Ubah angka stok menjadi 0 dan klik 'Simpan Stok'.
3. Buka tab baru `/menu` publik dan periksa status kartu Cappuccino.
- **Asumsi dan Batasan:** Koneksi basis data aktif.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0013: Kurir Zendo: Verifikasi PIN & Deteksi Anti-Fake GPS
**Kode SKPL Terkait:** `SKPL-NCM.K-0028`

- **Inisialisasi Lingkungan:** Tugas pengantaran aktif terbuka pada URL `/zendo/lacak/{token}`.
- **Masukan Uji (*Test Inputs*):**
- **Uji PIN Benar:** Masukkan PIN resmi kurir (misal: `1234`).
- **Uji Sinyal Lemah:** Kirim pembacaan GPS dengan `accuracy = 180 m`.
- **Uji Fake GPS (Teleportasi):** Kirim koordinat melompat 5 km dalam 2 detik.
- **Hasil yang Diharapkan (*Expected Results*):**
1. PIN Benar: Perangkat berhasil terikat (`is_device_bound = 1`) dan mode kurir aktif.
2. Sinyal Lemah: Server mengembalikan respon `verdict: weak` ('Sinyal GPS lemah, akurasi 180m').
3. Fake GPS: Server menolak pembacaan dengan respon `verdict: suspicious` ('Lokasi melompat dalam 2 detik — indikasi teleport / Fake GPS') dan menaikkan `gps_suspicious_count`.
- **Kriteria Evaluasi:** Perangkat kurir terproteksi dan anomali koordinat terdeteksi otomatis.
- **Prosedur Uji Bernomor:**
1. Buka tautan pelacakan pengantaran di smartphone.
2. Masukkan PIN kurir untuk mengikat browser.
3. Uji pengiriman koordinat wajar dan koordinat anomali.
- **Asumsi dan Batasan:** Konfigurasi `zendo_tracking` aktif pada `config/nucomu.php`.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0014: CRUD Menu: Soft Delete dan Proteksi Force Delete
**Kode SKPL Terkait:** `SKPL-NCM.K-0044`

- **Inisialisasi Lingkungan:** Admin login pada panel `/admin/menu`. Terdapat menu A (pernah dipesan di `order_items`) dan menu B (menu baru belum pernah dipesan).
- **Masukan Uji (*Test Inputs*):**
- Klik tombol 'Hapus' pada Menu A.
- Klik tombol 'Hapus Permanen' (*Force Delete*) pada Menu A.
- Klik tombol 'Pulihkan' (*Restore*) pada Menu A.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Klik Hapus Menu A: Terjadi Soft Delete (`deleted_at = now()`), menu hilang dari web publik tetapi data transaksi kasir tidak rusak.
2. Force Delete Menu A: Sistem MENOLAK dengan pesan error 'Menu ini tidak dapat dihapus permanen karena memiliki riwayat transaksi di kasir.'
3. Restore Menu A: Kolom `deleted_at` kembali menjadi `NULL` dan menu aktif kembali di publik.
- **Kriteria Evaluasi:** Integritas relasi data historis penjualan terlindungi seutuhnya.
- **Prosedur Uji Bernomor:**
1. Masuk menu admin `/admin/menu`.
2. Hapus menu yang memiliki riwayat penjualan. Buka tab tong sampah.
3. Coba lakukan force delete dan amati penolakan sistem.
4. Klik tombol Restore.
- **Asumsi dan Batasan:** Tabel `menus` menggunakan trait `SoftDeletes`.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0015: Deteksi Auto-Expiry Banner Promo Menjelang 30 Menit
**Kode SKPL Terkait:** `SKPL-NCM.K-0039`

- **Inisialisasi Lingkungan:** Admin login pada dasbor admin. Terdapat banner promo dengan `end_time` 20 menit dari waktu sekarang.
- **Masukan Uji (*Test Inputs*):**
- Permintaan polling otomatis AJAX ke `/admin/banner/expiring-check`.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Server mengidentifikasi banner tersebut masuk kategori `expiringSoon` (dalam batas <= 30 menit).
2. Muncul modal peringatan melayang di pojok layar dasbor admin.
3. Admin mengklik tombol 'Perpanjang 30 Menit' dan `end_time` bertambah 30 menit.
- **Kriteria Evaluasi:** Ambang batas waktu tepat 30 menit dan tombol perpanjang cepat berfungsi.
- **Prosedur Uji Bernomor:**
1. Buat banner dengan masa kedaluwarsa 20 menit ke depan.
2. Buka dasbor admin dan amati popup peringatan yang muncul.
3. Klik tombol perpanjang.
- **Asumsi dan Batasan:** Fitur polling JavaScript aktif di halaman admin.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

### DUPL-NCM.K-0016: Ekspor Laporan Rekapitulasi Penjualan (PDF & CSV)
**Kode SKPL Terkait:** `SKPL-NCM.K-0060`

- **Inisialisasi Lingkungan:** Terdapat data transaksi berstatus `paid` pada rentang bulan ini di tabel `orders`.
- **Masukan Uji (*Test Inputs*):**
- Klik tombol 'Unduh PDF 1.4 Native'.
- Klik tombol 'Unduh CSV Excel'.
- **Hasil yang Diharapkan (*Expected Results*):**
1. Ekspor PDF: Mengunduh berkas biner `.pdf` yang valid, terformat multi-halaman dengan banner gelap, kartu omzet, tabel transaksi, dan penutup PDF 1.4.
2. Ekspor CSV: Mengunduh berkas `.csv` dengan header UTF-8 BOM yang langsung terbuka rapi dalam kolom-kolom Microsoft Excel.
- **Kriteria Evaluasi:** Angka total omzet pada laporan cocok 100% dengan akumulasi tagihan basis data.
- **Prosedur Uji Bernomor:**
1. Masuk ke halaman admin `/admin/pesanan`.
2. Tentukan filter tanggal dari awal bulan hingga hari ini.
3. Klik 'Unduh Laporan PDF' dan buka berkas hasil unduhan.
- **Asumsi dan Batasan:** Tersedia data transaksi lunas di basis data.
- **Hasil Aktual:** `BELUM DIUJI (Mode Analisis Ekstraksi Dokumen)`
- **Status:** `BELUM DIUJI`

---

## 3. Laporan Hasil Pengujian Otomatis (*Automated Test Execution*)

### 3.1 Perintah Eksekusi
```bash
php artisan test
# atau: vendor/bin/phpunit
```

### 3.2 Laporan Hasil Nyata
- **Status Eksekusi di Lingkungan Sandbox Windows:** **TERKENDALA ISOLASI KONTROL AKSES (ACL)**.
- **Pesan Kesalahan Aktual:**
  ```
  PHPUnit 12.5.38 by Sebastian Bergmann and contributors.
  Cannot open bootstrap script "D:\laragon\www\umkm\vendor/autoload.php"
  ```
- **Penyebab Teknis Empiris:**
  Uji diagnostik internal menunjukkan fungsi bawaan PHP `is_readable('vendor/autoload.php')` pada Windows di dalam token subproses sandbox mengembalikan nilai `bool(false)` meskipun berkas fisik ada (`file_exists = true`) dan dapat dibuka (`fopen = resource`). Pustaka `PHPUnit 12` pada kelas `BootstrapLoader.php` baris 48 menerapkan pemeriksaan ketat `if (!is_readable($filename))` sebelum memuat berkas autoload, sehingga pengujian otomatis terhenti sebelum mengeksekusi suite tes.
- **Data Suite Pengujian di Repositori:**
  Proyek ini memiliki **17 berkas pengujian fitur (*Feature Tests*)** lengkap di direktori `tests/Feature/` yang mencakup seluruh modul penting:
  1. `AboutAdminTest.php`
  2. `BannerAdminTest.php`
  3. `CategoryPhotoTest.php`
  4. `ContactAdminTest.php`
  5. `DashboardChartAndOrderRecapTest.php`
  6. `FacilityAdminTest.php`
  7. `GalleryAdminTest.php`
  8. `MenuAdminTest.php`
  9. `MenuVariantTest.php`
  10. `Modul10AdminTest.php`
  11. `NucomuFeatureTest.php` (Pengujian komprehensif alur order, stok, dan kasir)
  12. `OrderTahap8aTest.php`
  13. `PublicPagesTest.php` (Pengujian keterjangkauan seluruh halaman publik)
  14. `ReservationAdminTest.php`
  15. `TestimonialAdminTest.php`
  16. `UploadFormsAccessTest.php`
  17. `ExampleTest.php`

---

## 4. Matriks Keterunutan Kebutuhan (*Traceability Matrix: SKPL -> DUPL*)
Tabel berikut memetakan keterunutan antara 74 Kebutuhan Fungsional (`SKPL-NCM.K-xxxx`) dari Tahap 1 dengan 16 Kasus Uji Inti (`DUPL-NCM.K-xxxx`):

| No | Kode SKPL | Nama Kebutuhan Fungsional | Status Kasus Uji | Kode Kasus Uji Terkait |
|---|---|---|---|---|
| 1 | `SKPL-NCM.K-0001` | Profil Kafe & Nilai Brand | Tercakup | `DUPL-NCM.K-0003` |
| 2 | `SKPL-NCM.K-0002` | Katalog Fasilitas Kafe | Tercakup | `DUPL-NCM.K-0003` |
| 3 | `SKPL-NCM.K-0003` | Galeri Visual & Suasana Kafe | Tercakup | `DUPL-NCM.K-0003` |
| 4 | `SKPL-NCM.K-0004` | Tampilan Ulasan Pelanggan | Tercakup | `DUPL-NCM.K-0009` |
| 5 | `SKPL-NCM.K-0005` | Kirim Ulasan Mandiri | Tercakup | `DUPL-NCM.K-0009` |
| 6 | `SKPL-NCM.K-0006` | Agenda Acara Kafe | Tercakup | `DUPL-NCM.K-0003` |
| 7 | `SKPL-NCM.K-0007` | Pengajuan Reservasi Meja | Tercakup | `DUPL-NCM.K-0008` |
| 8 | `SKPL-NCM.K-0008` | Pencarian & Filter Menu | Tercakup | `DUPL-NCM.K-0003` |
| 9 | `SKPL-NCM.K-0009` | Detail Menu & Pilihan Varian | Tercakup | `DUPL-NCM.K-0004` |
| 10 | `SKPL-NCM.K-0010` | Keranjang Belanja Mandiri | Tercakup | `DUPL-NCM.K-0004` |
| 11 | `SKPL-NCM.K-0011` | Checkout Mode 1 (Dine In) | Tercakup | `DUPL-NCM.K-0005` |
| 12 | `SKPL-NCM.K-0012` | Tampilan Slip QR (NCM-xxx) | Tercakup | `DUPL-NCM.K-0005` |
| 13 | `SKPL-NCM.K-0013` | Checkout Mode 2 (Chat Zendo) | Tercakup | `DUPL-NCM.K-0006` |
| 14 | `SKPL-NCM.K-0014` | Checkout Mode 3 (Antar GPS) | Tercakup | `DUPL-NCM.K-0007` |
| 15 | `SKPL-NCM.K-0015` | Tautan GrabFood | Belum Ada Kasir Otomatis | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 16 | `SKPL-NCM.K-0016` | Pembayaran Tunai & QRIS | Tercakup | `DUPL-NCM.K-0005 / DUPL-NCM.K-0010` |
| 17 | `SKPL-NCM.K-0017` | Pelacakan Status Live GPS | Tercakup | `DUPL-NCM.K-0007 / DUPL-NCM.K-0013` |
| 18 | `SKPL-NCM.K-0018` | Checkout Warisan (Legacy) | Fitur Sekunder | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 19 | `SKPL-NCM.K-0019` | Login Staf Kasir | Tercakup | `DUPL-NCM.K-0002` |
| 20 | `SKPL-NCM.K-0020` | Logout Staf Kasir | Tercakup | `DUPL-NCM.K-0002` |
| 21 | `SKPL-NCM.K-0021` | Katalog Penjualan POS | Tercakup | `DUPL-NCM.K-0010` |
| 22 | `SKPL-NCM.K-0022` | Pencarian & Scan Slip QR | Tercakup | `DUPL-NCM.K-0010` |
| 23 | `SKPL-NCM.K-0023` | Order Langsung POS Kasir | Tercakup | `DUPL-NCM.K-0010` |
| 24 | `SKPL-NCM.K-0024` | Pelunasan Transaksi Kasir | Tercakup | `DUPL-NCM.K-0010` |
| 25 | `SKPL-NCM.K-0025` | Cetak Struk Termal Kasir | Tercakup | `DUPL-NCM.K-0011` |
| 26 | `SKPL-NCM.K-0026` | Update Stok Cepat Kasir | Tercakup | `DUPL-NCM.K-0012` |
| 27 | `SKPL-NCM.K-0027` | Sinkronisasi Data Kasir | Fitur Utilitas | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 28 | `SKPL-NCM.K-0028` | Verifikasi PIN Kurir & Bind | Tercakup | `DUPL-NCM.K-0013` |
| 29 | `SKPL-NCM.K-0029` | Lepas Ikatan Perangkat Kurir | Tercakup | `DUPL-NCM.K-0013` |
| 30 | `SKPL-NCM.K-0030` | Streaming Koordinat GPS Kurir | Tercakup | `DUPL-NCM.K-0013` |
| 31 | `SKPL-NCM.K-0031` | Anti-Fake GPS Kurir | Tercakup | `DUPL-NCM.K-0013` |
| 32 | `SKPL-NCM.K-0032` | Pembaruan Status Antar Kurir | Tercakup | `DUPL-NCM.K-0013` |
| 33 | `SKPL-NCM.K-0033` | Login Staf Admin | Tercakup | `DUPL-NCM.K-0001` |
| 34 | `SKPL-NCM.K-0034` | Logout Staf Admin | Tercakup | `DUPL-NCM.K-0001` |
| 35 | `SKPL-NCM.K-0035` | Dasbor Analitik Penjualan | Tercakup | `DUPL-NCM.K-0016` |
| 36 | `SKPL-NCM.K-0036` | Saklar Sembunyi Grafik | Fitur UI | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 37 | `SKPL-NCM.K-0037` | Kelola Banner Promo | Tercakup | `DUPL-NCM.K-0015` |
| 38 | `SKPL-NCM.K-0038` | Perpanjang Tayang Banner | Tercakup | `DUPL-NCM.K-0015` |
| 39 | `SKPL-NCM.K-0039` | Deteksi Banner Segera Habis | Tercakup | `DUPL-NCM.K-0015` |
| 40 | `SKPL-NCM.K-0040` | Kelola Profil Our Story | Fitur Konten | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 41 | `SKPL-NCM.K-0041` | CRUD Fasilitas Kafe | Fitur Konten | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 42 | `SKPL-NCM.K-0042` | CRUD Koleksi Foto Galeri | Fitur Konten | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 43 | `SKPL-NCM.K-0043` | CRUD Agenda Acara Kafe | Fitur Konten | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 44 | `SKPL-NCM.K-0044` | CRUD Kategori Menu | Tercakup | `DUPL-NCM.K-0014` |
| 45 | `SKPL-NCM.K-0045` | CRUD Menu Makanan/Minuman | Tercakup | `DUPL-NCM.K-0014` |
| 46 | `SKPL-NCM.K-0046` | Soft Delete Menu | Tercakup | `DUPL-NCM.K-0014` |
| 47 | `SKPL-NCM.K-0047` | Pulihkan Menu Terhapus | Tercakup | `DUPL-NCM.K-0014` |
| 48 | `SKPL-NCM.K-0048` | Hapus Permanen Menu | Tercakup | `DUPL-NCM.K-0014` |
| 49 | `SKPL-NCM.K-0049` | Kontrol Ketersediaan Menu | Tercakup | `DUPL-NCM.K-0012` |
| 50 | `SKPL-NCM.K-0050` | Toggle Best Seller & Featured | Fitur UI | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 51 | `SKPL-NCM.K-0051` | Kurasi Ulasan Pelanggan | Tercakup | `DUPL-NCM.K-0009` |
| 52 | `SKPL-NCM.K-0052` | Sematkan Ulasan (Pin/Unpin) | Tercakup | `DUPL-NCM.K-0009` |
| 53 | `SKPL-NCM.K-0053` | Hapus Ulasan Spam | Tercakup | `DUPL-NCM.K-0009` |
| 54 | `SKPL-NCM.K-0054` | Pantau Reservasi & Konfirmasi | Tercakup | `DUPL-NCM.K-0008` |
| 55 | `SKPL-NCM.K-0055` | Konfigurasi WhatsApp Reservasi | Tercakup | `DUPL-NCM.K-0008` |
| 56 | `SKPL-NCM.K-0056` | Form Builder Reservasi | Tercakup | `DUPL-NCM.K-0008` |
| 57 | `SKPL-NCM.K-0057` | Konfigurasi 3 Mode & QRIS | Tercakup | `DUPL-NCM.K-0005 / DUPL-NCM.K-0006` |
| 58 | `SKPL-NCM.K-0058` | Reset Template Chat WA | Fitur Pengaturan | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 59 | `SKPL-NCM.K-0059` | Detail Transaksi Pesanan | Tercakup | `DUPL-NCM.K-0010 / DUPL-NCM.K-0016` |
| 60 | `SKPL-NCM.K-0060` | Ekspor Laporan Rekap PDF | Tercakup | `DUPL-NCM.K-0016` |
| 61 | `SKPL-NCM.K-0061` | Ekspor Laporan Rekap CSV | Tercakup | `DUPL-NCM.K-0016` |
| 62 | `SKPL-NCM.K-0062` | Pengaturan Daftar Kurir | Tercakup | `DUPL-NCM.K-0013` |
| 63 | `SKPL-NCM.K-0063` | Pengaturan Tarif Ongkir | Tercakup | `DUPL-NCM.K-0007` |
| 64 | `SKPL-NCM.K-0064` | Reset Ikatan Perangkat Kurir | Tercakup | `DUPL-NCM.K-0013` |
| 65 | `SKPL-NCM.K-0065` | Master Nomor Meja Kafe | Tercakup | `DUPL-NCM.K-0005` |
| 66 | `SKPL-NCM.K-0066` | Pengaturan Jam Operasional | Fitur Konten | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 67 | `SKPL-NCM.K-0067` | Pengaturan Kontak & Sosmed | Fitur Konten | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 68 | `SKPL-NCM.K-0068` | Ubah Profil Pribadi Admin | Fitur Akun | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 69 | `SKPL-NCM.K-0069` | Ubah Password Pribadi Admin | Fitur Akun | `DUPL-NCM.K-0001 (Variasi)` |
| 70 | `SKPL-NCM.K-0070` | Pusat Notifikasi Real-time | Fitur Notifikasi | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
| 71 | `SKPL-NCM.K-0071` | Panduan Dokumentasi Bantuan | Fitur Statis | `[BELUM MEMILIKI UJI DUPL KHUSUS]` |
