# RINGKASAN EKSEKUTIF EKSTRAKSI FAKTA SISTEM (SAS RPL)
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Peran:** Analis Perangkat Lunak (Software Analyst)
**Tanggal Penyusunan:** 24 November 2026
**Sifat Dokumen:** Hasil Ekstraksi Fakta Kode Nyata (*Read-Only Empirical Evidence*)

---

## 1. Daftar Berkas Laporan yang Dihasilkan
Seluruh dokumen hasil analisis telah disusun secara terstruktur di dalam direktori `laporan_data/`:

| No | Nama Berkas | Tahap Analisis | Cakupan Utama Dokumen |
|---|---|---|---|
| 1 | `00_RINGKASAN.md` | Dokumen Induk | Ringkasan eksekutif, 10 temuan utama, daftar ketidaksesuaian, dan poin konfirmasi |
| 2 | `00_inventaris.md` | Tahap 0 | Versi resmi composer/npm, 25 controller, 17 model, 82 view, 32 migrasi, dan 158 rute |
| 3 | `01_fungsi_skpl.md` | Tahap 1 | 74 Kebutuhan Fungsional (`SKPL-NCM.K-0001` s/d `0074`) beserta matriks rute-view-tabel |
| 4 | `02_dfd.md` | Tahap 2 | DFD Level 0 (Konteks), DFD Level 1, DFD Level 2 (Order, Kasir, Kurir), dan Use Case Diagram |
| 5 | `03_erd_kamus_data.md` | Tahap 3 | ERD Mermaid lengkap, kamus data teknis 17 tabel operasional, dan skema relasi |
| 6 | `04_arsitektur_dekomposisi.md` | Tahap 4 | Struktur folder MVC Laravel, pohon dekomposisi modul fungsional, spek software/hardware |
| 7 | `05_antarmuka_halaman.md` | Tahap 5 | 35 Halaman antarmuka, aturan gaya (palet warna, font, breakpoint), dan daftar pesan validasi |
| 8 | `06_spesifikasi_fungsi_inti.md` | Tahap 6 | Spesifikasi teknis 8 sub-bagian untuk 10 fungsi bisnis sentral aplikasi |
| 9 | `07_algoritma.md` | Tahap 7 | Pembuktian algoritma NCM, stok, ongkir bertingkat, Anti-Fake GPS, PDF 1.4, cache ulasan |
| 10 | `08_nonfungsional_keamanan.md` | Tahap 8 | Evaluasi keamanan cookie HMAC, CSRF, hashing bcrypt, RBAC, DB transaction, dan kinerja |
| 11 | `09_pengujian_dupl.md` | Tahap 9 | 16 Kasus uji black box (`DUPL-NCM.K-0001` s/d `0016`), laporan test otomatis, matriks RTM |
| 12 | `10_verifikasi_klaim.md` | Tahap 10 | Pengujian empiris terhadap 15 klaim teknis laporan (Sesuai / Tidak Sesuai / Tidak Ditemukan) |
| 13 | `11_fuzzy_logic.md` | Tahap 11 | Analisis ketiadaan logika fuzzy dan 3 rancangan usulan modul terapan cerdas |

---

## 2. Sepuluh (10) Temuan Terpenting dari Basis Kode

1. **Versi Framework Laravel Generasi 13 (Bukan Laravel 11):**
   - Berkas terkunci `composer.lock` mencatat framework terpasang adalah `laravel/framework v13.35.0` (dengan PHP 8.5.9). Klaim pada laporan lama yang menyebutkan 'Laravel 11' tidak sesuai dengan dependensi riil proyek.
2. **Bebas Alpine.js (Arsitektur Vanilla JavaScript):**
   - Tidak ditemukan pustaka Alpine.js di `package.json`. Keranjang belanja (*Cart Drawer*), modal varian, dan manipulasi antarmuka sepenuhnya menggunakan Vanilla JavaScript murni yang beroperasi di atas `window.localStorage`.
3. **Klarifikasi Jumlah Tabel Basis Data (17 Model vs 24 Tabel Fisik):**
   - Klaim laporan bahwa sistem memiliki '17 tabel fisik' bersumber dari **17 tabel entitas aplikasi/model Eloquent**. Namun jika dihitung secara fisik di basis data migrasi, terdapat total **24 tabel** karena menyertakan 7 tabel infrastruktur Laravel (`sessions`, `cache`, `jobs`, dll).
4. **Keamanan Cookie Ganda Ditandatangani HMAC SHA-256:**
   - Sesi staf tidak hanya mengandalkan session PHP biasa, tetapi diperkuat oleh cookie terenkripsi persisten: `nucomu_admin_auth_v1` (30 hari) dan `nucomu_kasir_auth_v2` (1 tahun) yang diverifikasi menggunakan `hash_equals()` dan secret key aplikasi.
5. **Penanganan Harga Varian Hanya di Sisi Klien:**
   - Server secara tegas hanya mengambil dan mengalikan harga dasar menu pokok dari tabel `menus` (`$unitPrice = (int) $menu->price`). Tambahan biaya varian dihitung di browser dan hanya disimpan sebagai catatan JSON di `order_items.options` tanpa ditambahkan ke harga satuan server.
6. **Parameter Anti-Fake GPS Menggunakan Satuan m/s (Bukan Ambang 100):**
   - Sensor GPS peramban mengirimkan kecepatan dalam satuan meter per detik (m/s). Konfigurasi resmi `config/nucomu.php` menetapkan batas `max_speed_mps = 45` (setara 162 km/jam). Jika laporan mengasumsikan ambang 100 m/s, nilainya setara 360 km/jam.
7. **Generator Laporan PDF 1.4 Native Tanpa Pustaka Eksternal:**
   - Modul `TransactionReportPdfGenerator` merakit aliran biner berkas PDF 1.4 secara native murni via PHP (objek grafis, font Helvetica, tabel cross-reference `xref`, dan trailer) tanpa ketergantungan paket berat seperti Dompdf atau Snappy.
8. **Ketiadaan Fitur Shift Kasir, Modal Awal, dan Log Aktivitas:**
   - Prosedur buka/tutup shift kasir, rekonsiliasi kas laci saat tutup kasir, serta tabel audit log aktivitas **TIDAK DITEMUKAN** di dalam kode program.
9. **Ketiadaan Gateway Pembayaran Otomatis Midtrans:**
   - Tidak ada integrasi SDK Midtrans. Seluruh transaksi non-tunai kafe mengandalkan scan gambar statis QRIS kafe dan verifikasi penerimaan uang secara manual oleh kasir di meja kasir.
10. **Alamat Fisik Struk Kasir Belum Menggunakan Alamat Resmi:**
    - Templat cetak struk termal 80mm di `resources/views/admin/orders/receipt.blade.php` masih mencantumkan alamat hardcoded *'Jl. Panglima Sudirman No. 45, Kebonsari'* dan belum disesuaikan ke alamat resmi kafe/sekolah di Manggisan, Plosokandang, Kedungwaru, Tulungagung.

---

## 3. Daftar Seluruh Ketidaksesuaian (Laporan Awal vs Fakta Kode)

| No | Poin yang Diklaim pada Laporan Awal | Fakta Riil di Dalam Kode Program | Status Evaluasi | Rekomendasi Tindak Lanjut untuk Laporan |
|---|---|---|---|---|
| 1 | Aplikasi dibangun menggunakan Laravel 11. | `composer.lock` mengunci `laravel/framework v13.35.0` (kebutuhan `^13.17`). | **TIDAK SESUAI** | Ubah teks laporan menjadi **Laravel Framework v13.35** (PHP 8.5). |
| 2 | Kasir memiliki fitur Buka/Tutup Shift dan Rekonsiliasi Modal Awal. | Tidak ada tabel `shifts`, tidak ada input modal awal di `KasirController`, dan tidak ada rekonsiliasi kas. | **TIDAK DITEMUKAN** | Hapus klaim fitur shift dari bab pembahasan dan pindahkan ke saran pengembangan masa depan. |
| 3 | Terdapat fitur Audit Log / Log Aktivitas Admin. | Tidak ada tabel `activity_logs` atau observer pencatat riwayat mutasi admin. | **TIDAK DITEMUKAN** | Hapus pembahasan log aktivitas dari daftar fitur sistem. |
| 4 | Server menghitung penambahan harga varian menu saat checkout. | Server hanya mengambil `$menu->price` pokok; data varian hanya dicatat sebagai teks JSON di `order_items.options`. | **TIDAK SESUAI** | Jelaskan bahwa kalkulasi selisih varian ditangani oleh skrip Cart Drawer di peramban klien. |
| 5 | Ambang kecepatan Anti-Fake GPS adalah 100 km/jam. | Satuan Geolocation API adalah m/s dan konfigurasi `config/nucomu.php` menetapkan `max_speed_mps = 45` (162 km/jam). | **TIDAK SESUAI** | Perbaiki keterangan parameter menjadi **45 m/s (162 km/jam)**. |
| 6 | Sistem terintegrasi dengan Payment Gateway Midtrans. | Tidak ada kode, SDK, atau konfigurasi Midtrans di dalam repositori. | **TIDAK DITEMUKAN** | Ganti narasi pembayaran menjadi pembayaran tunai di kasir dan scan manual QRIS statis. |
| 7 | Struk kasir mencetak alamat resmi kafe di Plosokandang, Kedungwaru. | Pada berkas `receipt.blade.php` baris 51 masih tertulis *'Jl. Panglima Sudirman No. 45, Kebonsari'*. | **TIDAK SESUAI** | Catat sebagai temuan revisi kode agar alamat struk diselaraskan ke alamat resmi. |
| 8 | Sistem mendukung pemilihan ukuran struk 58 mm dan 80 mm. | Ukuran kertas struk dikunci (*hardcoded*) pada CSS `@page { size: 80mm auto; }` dan lebar `76mm`. | **TIDAK SESUAI** | Ubah keterangan menjadi **Dukungan cetak struk termal format 80 mm**. |
| 9 | Hanya ulasan bintang lima Google yang diambil ke website. | Fungsi `getCombinedReviews()` memuat seluruh ulasan Google tanpa filter nilai rating. | **TIDAK SESUAI** | Jelaskan bahwa sistem menampilkan seluruh ulasan publik yang dikembalikan oleh Google API. |
| 10 | Terdapat tabel akun untuk 4 peran pengguna di database. | Tabel `users.role` hanya memuat `'admin'`, `'kasir'`, dan `'user'`. Kurir tidak memiliki akun pengguna. | **TIDAK SESUAI SKEMA** | Jelaskan bahwa akses kurir bersifat *token-based* melalui tautan tugas di `zendo_deliveries`. |

---

## 4. Daftar Hal yang Masih PERLU KONFIRMASI
Poin-poin berikut tidak dapat diputuskan hanya dari pembacaan kode sumber statis dan memerlukan konfirmasi dari pemilik proyek (pemilik Nucomu Cafe / guru pembimbing RPL):

1. **Status Akun Produksi Google Places API:**
   - *Status Kode:* Berkas konfigurasi lokal membiarkan `GOOGLE_PLACES_API_KEY` dan `GOOGLE_PLACE_ID` bernilai `null` sehingga sistem memakai data ulasan contoh lokal (*fallback*).
   - *Perlu Konfirmasi:* Apakah kafe sudah memiliki Google Cloud Project aktif dengan *Billing Account* terverifikasi untuk penayangan live reviews di server produksi?
2. **Penggunaan Token WhatsApp Gateway Fonnte:**
   - *Status Kode:* Metode pengiriman pesan otomatis ke endpoint `https://api.fonnte.com/send` telah disiapkan di `NucomuController`, namun token `zendo_wa_api_token` saat ini kosong sehingga sistem beralih ke pembukaan tautan WhatsApp Web (`wa.me`).
   - *Perlu Konfirmasi:* Apakah pihak kafe berencana berlangganan API gateway Fonnte berbayar atau tetap mempertahankan alur pengalihan URL WhatsApp Web?
3. **Pengalokasian Meja Fisik pada Tamu Reservasi:**
   - *Status Kode:* Tabel `reservations` memiliki foreign key `table_id` ke tabel `tables`, tetapi antarmuka admin tidak menyediakan dropdown pemilih nomor meja saat admin mengubah status reservasi menjadi `confirmed`.
   - *Perlu Konfirmasi:* Apakah penentuan nomor meja fisik tamu reservasi memang sengaja dilakukan secara manual di lokasi (*on-the-spot seating*) oleh staf kafe?
4. **Klaim Pengukuran Waktu Muat (*Load Time < 2s*):**
   - *Status Kode:* Repositori tidak memiliki skrip pengujian performa benchmark atau pengukuran waktu muat jaringan.
   - *Perlu Konfirmasi:* Jika angka performa ingin tetap dimasukkan ke laporan akhir, pengujian pengukuran nyata (menggunakan Google Lighthouse atau DevTools Network tab pada server live) wajib dilakukan terlebih dahulu.
