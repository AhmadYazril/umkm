/**
 * DIAGRAMS DATA STORE - NUCOMU CAFE VISUAL DOCUMENTATION
 * Total 30 Diagram Lengkap:
 * - Tab A: 7 Diagram Berjenjang (Dekomposisi Fungsional)
 * - Tab B: 8 DFD (Data Flow Diagram Gane-Sarson)
 * - Tab C: 15 Algoritma / Flowchart (dengan Langkah Pengguna & Matriks DB)
 */

const DIAGRAMS = [
  // ==========================================
  // TAB A: DIAGRAM BERJENJANG (7 DIAGRAM)
  // ==========================================
  {
    id: "A1-hirarki-sistem",
    tab: "A",
    number: "A1",
    title: "Diagram Induk Dekomposisi Fungsional Sistem Nucomu Cafe",
    subtitle: "Pohon Dekomposisi Utama: 4 Aktor (Admin, Kasir, Customer, Kurir Zendo)",
    description: "Diagram induk memetakan struktur fungsional sistem dari tingkat 0 (Sistem Informasi Nucomu Cafe) ke empat aktor operasional utama (Administrator, Kasir POS, Customer Web, dan Kurir Zendo) serta sub-modul fungsional di bawah masing-masing aktor.",
    code: `flowchart TB
    classDef root fill:#140E0A,stroke:#C2A378,stroke-width:3px,color:#FFFFFF;
    classDef actor fill:#2A1D17,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef mod fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    SYS["0.0 SISTEM INFORMASI WEBSITE NUCOMU CAFE"]:::root

    ACT_ADM["1.0 Modul Administrator<br>(Manajemen Pusat)"]:::actor
    ACT_KSR["2.0 Modul Kasir<br>(Point of Sale / POS)"]:::actor
    ACT_CUST["3.0 Modul Customer<br>(Web Pelanggan)"]:::actor
    ACT_KRR["4.0 Modul Kurir Zendo<br>(Logistik & GPS)"]:::actor

    SYS --> ACT_ADM
    SYS --> ACT_KSR
    SYS --> ACT_CUST
    SYS --> ACT_KRR

    %% Admin Submodules
    M_ADM_1["1.1 Dashboard & Grafik"]:::mod
    M_ADM_2["1.2 Hero & Banner Promo"]:::mod
    M_ADM_3["1.3 Konten Profil & Media"]:::mod
    M_ADM_4["1.4 Menu & Kategori"]:::mod
    M_ADM_5["1.5 Kurasi Ulasan/Pin"]:::mod
    M_ADM_6["1.6 Reservasi & Form Builder"]:::mod
    M_ADM_7["1.7 Transaksi & Scanner Slip"]:::mod
    M_ADM_8["1.8 Armada Kurir Zendo"]:::mod
    M_ADM_9["1.9 Profil, Notif & Setting"]:::mod

    ACT_ADM --> M_ADM_1
    ACT_ADM --> M_ADM_2
    ACT_ADM --> M_ADM_3
    ACT_ADM --> M_ADM_4
    ACT_ADM --> M_ADM_5
    ACT_ADM --> M_ADM_6
    ACT_ADM --> M_ADM_7
    ACT_ADM --> M_ADM_8
    ACT_ADM --> M_ADM_9

    %% Kasir Submodules
    M_KSR_1["2.1 Login & Sesi Kasir"]:::mod
    M_KSR_2["2.2 Katalog POS & Order Langsung"]:::mod
    M_KSR_3["2.3 Lookup & Validasi Slip QR"]:::mod
    M_KSR_4["2.4 Pelunasan, Struk & Stok"]:::mod

    ACT_KSR --> M_KSR_1
    ACT_KSR --> M_KSR_2
    ACT_KSR --> M_KSR_3
    ACT_KSR --> M_KSR_4

    %% Customer Submodules
    M_CST_1["3.1 Beranda, Profil & Fasilitas"]:::mod
    M_CST_2["3.2 Katalog Menu & Pilihan Varian"]:::mod
    M_CST_3["3.3 Form Ulasan & Unggah Foto"]:::mod
    M_CST_4["3.4 Reservasi Meja Dinamis"]:::mod
    M_CST_5["3.5 Keranjang & Checkout 3 Mode"]:::mod
    M_CST_6["3.6 Slip Pesanan & Lacak Live"]:::mod

    ACT_CUST --> M_CST_1
    ACT_CUST --> M_CST_2
    ACT_CUST --> M_CST_3
    ACT_CUST --> M_CST_4
    ACT_CUST --> M_CST_5
    ACT_CUST --> M_CST_6

    %% Kurir Submodules
    M_KRR_1["4.1 Verifikasi PIN & Device Binding"]:::mod
    M_KRR_2["4.2 Streaming Koordinat GPS"]:::mod
    M_KRR_3["4.3 Deteksi Anti-Fake GPS"]:::mod
    M_KRR_4["4.4 Perubahan Status Pengantaran"]:::mod

    ACT_KRR --> M_KRR_1
    ACT_KRR --> M_KRR_2
    ACT_KRR --> M_KRR_3
    ACT_KRR --> M_KRR_4`
  },
  {
    id: "A2-admin-bagian1",
    tab: "A",
    number: "A2",
    title: "Dekomposisi Admin - Bagian 1 (Dashboard, Banner, Konten Profil)",
    subtitle: "Rincian Fungsional: 1.1 Dashboard Analitik, 1.2 Banner Promo & Hero, 1.3 Konten Profil Kafe",
    description: "Menjabarkan modul analitik penjualan harian/mingguan/bulanan, pengelolaan media banner hero beresolusi minimal 1600px atau video MP4 20MB dengan jadwal hitung mundur, serta konten Our Story, fasilitas kafe multi-foto, galeri, dan event.",
    code: `flowchart TB
    classDef actor fill:#2A1D17,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef mod fill:#C2A378,stroke:#2A1D17,stroke-width:1px,color:#FFFFFF;
    classDef submod fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef leaf fill:#FAF8F5,stroke:#8C7E74,stroke-width:1px,color:#140E0A;

    ADM["1.0 Modul Administrator"]:::actor

    M11["1.1 Dashboard & Analitik"]:::mod
    M12["1.2 Hero & Banner Promo"]:::mod
    M13["1.3 Konten Profil Kafe"]:::mod

    ADM --> M11
    ADM --> M12
    ADM --> M13

    %% 1.1 Dashboard
    F111["1.1.1 Pantau 4 Metrik Utama<br>(Omzet, Pesanan, Pending, Menu)"]:::submod
    F112["1.1.2 Grafik Penjualan Interaktif<br>(Harian 7 Hari, 4 Minggu, 12 Bulan)"]:::submod
    F113["1.1.3 Menu Terlaris<br>(Top 5 Berdasarkan Kuantitas & Omzet)"]:::submod
    F114["1.1.4 Saklar Visibilitas Grafik<br>(Toggle Show/Hide Chart)"]:::submod

    M11 --> F111
    M11 --> F112
    M11 --> F113
    M11 --> F114

    %% 1.2 Hero & Banner
    F121["1.2.1 Tambah Banner Baru"]:::submod
    F122["1.2.2 Jadwal & Hitung Mundur<br>(Start Time, End Time & Auto Expired)"]:::submod
    F123["1.2.3 Perpanjang Masa Tayang<br>(Extend Duration Pilihan Jam/Hari)"]:::submod
    F124["1.2.4 Operasional Banner<br>(Edit, Hapus Berkas, Toggle Aktif)"]:::submod
    F125["1.2.5 Deteksi Notifikasi 30 Menit<br>(Check Expiring Banner API)"]:::submod

    M12 --> F121
    M12 --> F122
    M12 --> F123
    M12 --> F124
    M12 --> F125

    L121["1.2.1.1 Validasi Media<br>(Foto Landscape Min 1600px / Video MP4)"]:::leaf
    L122["1.2.1.2 Mode Layout<br>(Poster, Overlay, Split Layout)"]:::leaf
    F121 --> L121
    F121 --> L122

    %% 1.3 Konten Profil
    F131["1.3.1 Our Story / Tentang Kafe<br>(Headline, Narasi, Kutipan, Video/Foto)"]:::submod
    F132["1.3.2 Fasilitas Kafe<br>(Multi-Foto, Fitur, Ikon, Toggle, Reorder)"]:::submod
    F133["1.3.3 Galeri Foto<br>(Upload Foto, Kategori, Caption, Toggle)"]:::submod
    F134["1.3.4 Event & Agenda<br>(Judul, Tanggal, Poster, Deskripsi)"]:::submod

    M13 --> F131
    M13 --> F132
    M13 --> F133
    M13 --> F134`
  },
  {
    id: "A3-admin-bagian2",
    tab: "A",
    number: "A3",
    title: "Dekomposisi Admin - Bagian 2 (Menu, Kategori, Ulasan, Reservasi)",
    subtitle: "Rincian Fungsional: 1.4 Menu & Kategori, 1.5 Kurasi Ulasan/Pinning, 1.6 Reservasi & Form Builder",
    description: "Menjabarkan logika pengelolaan kategori & menu berproteksi (mencegah hapus kategori berisi menu atau force delete menu pernah dipesan), sistem kurasi ulasan (unpinned default, pinning, hide, delete), serta form builder dinamis reservasi meja.",
    code: `flowchart TB
    classDef actor fill:#2A1D17,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef mod fill:#C2A378,stroke:#2A1D17,stroke-width:1px,color:#FFFFFF;
    classDef submod fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef leaf fill:#FAF8F5,stroke:#8C7E74,stroke-width:1px,color:#140E0A;

    ADM["1.0 Modul Administrator"]:::actor

    M14["1.4 Menu & Kategori"]:::mod
    M15["1.5 Kurasi Ulasan"]:::mod
    M16["1.6 Reservasi & Form Builder"]:::mod

    ADM --> M14
    ADM --> M15
    ADM --> M16

    %% 1.4 Menu & Kategori
    F141["1.4.1 Kelola Kategori Menu"]:::submod
    F142["1.4.2 Tambah & Edit Menu<br>(Foto Kotak 1:1, Harga, Stok Awal 25)"]:::submod
    F143["1.4.3 Kendali Stok & Tampilan<br>(Toggle Ketersediaan, Featured, Best Seller)"]:::submod
    F144["1.4.4 Hapus Menu Berproteksi"]:::submod

    M14 --> F141
    M14 --> F142
    M14 --> F143
    M14 --> F144

    L141["1.4.1.1 Tolak Hapus Kategori<br>Jika Masih Berisi Menu"]:::leaf
    L142["1.4.4.1 Tolak Hapus Permanen<br>Jika Menu Pernah Dipesan (Soft Delete)"]:::leaf
    L143["1.4.4.2 Pulihkan Menu (Restore)"]:::leaf
    F141 --> L141
    F144 --> L142
    F144 --> L143

    %% 1.5 Kurasi Ulasan
    F151["1.5.1 Filter & Pencarian Ulasan<br>(Semua, Pinned, Unpinned, Rating 1-5)"]:::submod
    F152["1.5.2 Sematkan Ulasan (Pin)<br>(Otomatis Tampil di Beranda)"]:::submod
    F153["1.5.3 Lepas Sematan (Unpin)"]:::submod
    F154["1.5.4 Sembunyikan Ulasan (Toggle Active)"]:::submod
    F155["1.5.5 Hapus Ulasan Spam & Foto"]:::submod
    F156["1.5.6 Hapus Massal Data Contoh"]:::submod

    M15 --> F151
    M15 --> F152
    M15 --> F153
    M15 --> F154
    M15 --> F155
    M15 --> F156

    %% 1.6 Reservasi & Form Builder
    F161["1.6.1 Pantau Daftar Reservasi<br>(Filter Pending, Confirmed, Completed, Cancel)"]:::submod
    F162["1.6.2 Ubah Status & Hapus Reservasi"]:::submod
    F163["1.6.3 Atur Nomor WhatsApp Tujuan"]:::submod
    F164["1.6.4 Form Builder Kustom"]:::submod

    M16 --> F161
    M16 --> F162
    M16 --> F163
    M16 --> F164

    L161["1.6.4.1 Tambah Kolom Baru<br>(Teks, Tel, Tanggal, Jam, Angka, Area, Foto, Checkbox)"]:::leaf
    L162["1.6.4.2 Atur Wajib/Opsional, Urutan, Toggle Aktif"]:::leaf
    F164 --> L161
    F164 --> L162`
  },
  {
    id: "A4-admin-bagian3",
    tab: "A",
    number: "A4",
    title: "Dekomposisi Admin - Bagian 3 (Transaksi, Kurir, Pengaturan)",
    subtitle: "Rincian Fungsional: 1.7 Transaksi 3 Mode & Scanner, 1.8 Armada Kurir Zendo, 1.9 Profil & Pengaturan",
    description: "Menjabarkan konfigurasi 3 mode transaksi dengan aturan interlocking server, pemindai QR slip kasir, ekspor laporan rekap PDF 1.4 native, manajemen armada kurir dan tarif tier, profil akun, serta pusat notifikasi.",
    code: `flowchart TB
    classDef actor fill:#2A1D17,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef mod fill:#C2A378,stroke:#2A1D17,stroke-width:1px,color:#FFFFFF;
    classDef submod fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef leaf fill:#FAF8F5,stroke:#8C7E74,stroke-width:1px,color:#140E0A;

    ADM["1.0 Modul Administrator"]:::actor

    M17["1.7 Transaksi & Scanner"]:::mod
    M18["1.8 Logistik & Kurir Zendo"]:::mod
    M19["1.9 Profil & Pengaturan"]:::mod

    ADM --> M17
    ADM --> M18
    ADM --> M19

    %% 1.7 Transaksi
    F171["1.7.1 Konfigurasi 3 Mode Transaksi"]:::submod
    F172["1.7.2 Sub-Pengaturan Pembayaran<br>(Opsi Tunai & QRIS, Upload Gambar QRIS)"]:::submod
    F173["1.7.3 Template Chat WA Zendo Dinamis<br>(Edit Format Pesan & Tombol Reset)"]:::submod
    F174["1.7.4 Operasional Pesanan<br>(Filter, Cari Kode NCM, Ubah Status, Cetak Struk)"]:::submod
    F175["1.7.5 Ekspor Laporan Rekap Transaksi<br>(PDF Native 1.4, Print View, CSV/Excel)"]:::submod
    F176["1.7.6 Pemindai Kamera Slip QR Kasir"]:::submod

    M17 --> F171
    M17 --> F172
    M17 --> F173
    M17 --> F174
    M17 --> F175
    M17 --> F176

    L171["1.7.1.1 Aturan Saling Mengunci<br>(Tracking ON -> Chat Otomatis OFF)"]:::leaf
    L172["1.7.1.2 Mode Semua OFF<br>(Keranjang Dinonaktifkan di Web)"]:::leaf
    F171 --> L171
    F171 --> L172

    %% 1.8 Kurir Zendo
    F181["1.8.1 Kelola Daftar & Nomor Kurir<br>(Tambah, Hapus, Edit Nomor WA Kurir)"]:::submod
    F182["1.8.2 Konfigurasi Tarif Ongkir<br>(Tier 0-3km, 3-4km, ..., Tambahan/km)"]:::submod
    F183["1.8.3 Pemantauan Perangkat Terhubung<br>(Pantau Device Token & Status GPS)"]:::submod
    F184["1.8.4 Cabut Akses / Reset Device Kurir"]:::submod

    M18 --> F181
    M18 --> F182
    M18 --> F183
    M18 --> F184

    %% 1.9 Profil & Pengaturan
    F191["1.9.1 Profil Administrator<br>(Nama, Email, Upload/Hapus Avatar, Ganti Password)"]:::submod
    F192["1.9.2 Pusat Notifikasi & Pesan<br>(Agregasi Banner, Order, Reservasi, Review, Tandai Baca)"]:::submod
    F193["1.9.3 Pusat Bantuan & Manual Penggunaan<br>(Panduan 9 Modul & FAQ Sekolah)"]:::submod
    F194["1.9.4 Kontak, Sosmed & Jam Operasional<br>(WA Resmi, Maps Embed, GrabFood, Jadwal Buka)"]:::submod

    M19 --> F191
    M19 --> F192
    M19 --> F193
    M19 --> F194`
  },
  {
    id: "A5-kasir-pos",
    tab: "A",
    number: "A5",
    title: "Dekomposisi Kasir POS (Point of Sale Workspace)",
    subtitle: "Rincian Fungsional: 2.1 Login & Sesi Kasir, 2.2 Katalog POS, 2.3 Lookup Slip QR, 2.4 Pelunasan & Struk",
    description: "Menjabarkan antarmuka operasional kasir di rute /kasir: autentikasi dengan cookie 1 tahun, pembuatan pesanan meja langsung, pemindaian kode QR slip pelanggan, konfirmasi pelunasan, cetak struk thermal, dan update stok cepat.",
    code: `flowchart TB
    classDef actor fill:#2A1D17,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef mod fill:#C2A378,stroke:#2A1D17,stroke-width:1px,color:#FFFFFF;
    classDef submod fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef leaf fill:#FAF8F5,stroke:#8C7E74,stroke-width:1px,color:#140E0A;

    KSR["2.0 Modul Kasir (POS)"]:::actor

    M21["2.1 Autentikasi Kasir"]:::mod
    M22["2.2 Katalog & Entri Pesanan POS"]:::mod
    M23["2.3 Lookup Slip QR Pesanan"]:::mod
    M24["2.4 Pelunasan, Struk & Stok"]:::mod

    KSR --> M21
    KSR --> M22
    KSR --> M23
    KSR --> M24

    %% 2.1 Autentikasi
    F211["2.1.1 Login Kasir Password<br>(Akun kasir@nucomu.com / Admin)"]:::submod
    F212["2.1.2 Sesi Cookie 1 Tahun<br>(nucomu_kasir_auth_v2 HMAC Token)"]:::submod
    F213["2.1.3 Logout Kasir & Hapus Sesi"]:::submod
    M21 --> F211
    M21 --> F212
    M21 --> F213

    %% 2.2 Katalog & Entri POS
    F221["2.2.1 Tampilan Grid Menu & Kategori"]:::submod
    F222["2.2.2 Entri Pesanan Meja / Takeaway"]:::submod
    F223["2.2.3 Pilihan Pembayaran Tunai / QRIS"]:::submod
    F224["2.2.4 Simpan Transaksi & Generate Kode NCM"]:::submod
    M22 --> F221
    M22 --> F222
    M22 --> F223
    M22 --> F224

    %% 2.3 Lookup Slip QR
    F231["2.3.1 Scan Barcode / Input Kode Slip"]:::submod
    F232["2.3.2 Ambil Rincian Item & Total Pelanggan"]:::submod
    F233["2.3.3 Verifikasi Meja & Identitas Pemesan"]:::submod
    M23 --> F231
    M23 --> F232
    M23 --> F233

    %% 2.4 Pelunasan, Struk, Stok
    F241["2.4.1 Konfirmasi Pelunasan (Lunas)"]:::submod
    F242["2.4.2 Ubah Status Pesanan<br>(Processing, Ready, Completed, Cancel)"]:::submod
    F243["2.4.3 Cetak Struk Kasir Thermal (58/80mm)"]:::submod
    F244["2.4.4 Pembaruan Stok Cepat Menu (+/- Stok)"]:::submod
    F245["2.4.5 Sinkronisasi Data Real-Time (Polling JSON)"]:::submod
    M24 --> F241
    M24 --> F242
    M24 --> F243
    M24 --> F244
    M24 --> F245`
  },
  {
    id: "A6-customer-web",
    tab: "A",
    number: "A6",
    title: "Dekomposisi Customer (Antarmuka Web Publik)",
    subtitle: "Rincian Fungsional: 3.1 Beranda Profil, 3.2 Katalog Menu, 3.3 Ulasan, 3.4 Reservasi Dinamis, 3.5 Checkout 3 Mode, 3.6 Pelacakan",
    description: "Menjabarkan seluruh pengalaman pelanggan website: menjelajahi hero banner dan fasilitas, memilih menu & varian penyajian, mengisi formulir reservasi meja, mengirim ulasan & foto, checkout multi-mode, hingga pelacakan langsung kurir.",
    code: `flowchart TB
    classDef actor fill:#2A1D17,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef mod fill:#C2A378,stroke:#2A1D17,stroke-width:1px,color:#FFFFFF;
    classDef submod fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef leaf fill:#FAF8F5,stroke:#8C7E74,stroke-width:1px,color:#140E0A;

    CUST["3.0 Modul Customer"]:::actor

    M31["3.1 Eksplorasi Profil Kafe"]:::mod
    M32["3.2 Katalog Menu & Pilihan"]:::mod
    M33["3.3 Ulasan & Pengalaman"]:::mod
    M34["3.4 Reservasi Meja Dinamis"]:::mod
    M35["3.5 Keranjang & Checkout"]:::mod
    M36["3.6 Pelacakan Pesanan"]:::mod

    CUST --> M31
    CUST --> M32
    CUST --> M33
    CUST --> M34
    CUST --> M35
    CUST --> M36

    %% 3.1 Profil
    F311["3.1.1 Lihat Banner Promo & Video Hero"]:::submod
    F312["3.1.2 Baca Our Story & Filosofi Kafe"]:::submod
    F313["3.1.3 Jelajahi Fasilitas (AC, Outdoor, Musala, Toilet)"]:::submod
    F314["3.1.4 Buka Rute Peta Google Maps & Jam Buka"]:::submod
    F315["3.1.5 Tautan Sosmed (Instagram, TikTok, GrabFood)"]:::submod
    M31 --> F311
    M31 --> F312
    M31 --> F313
    M31 --> F314
    M31 --> F315

    %% 3.2 Menu
    F321["3.2.1 Telusuri Menu per Kategori Tab"]:::submod
    F322["3.2.2 Buka Detail Menu & Pilihan Opsi<br>(Penyajian Panas/Dingin, Sugar Level, Extra)"]:::submod
    M32 --> F321
    M32 --> F322

    %% 3.3 Ulasan
    F331["3.3.1 Baca Ulasan Pilihan (Google & Web)"]:::submod
    F332["3.3.2 Kirim Ulasan Baru (Rating, Teks, Upload Foto)"]:::submod
    M33 --> F331
    M33 --> F332

    %% 3.4 Reservasi
    F341["3.4.1 Buka Modal / Halaman Reservasi"]:::submod
    F342["3.4.2 Isi Formulir Sesuai Form Builder"]:::submod
    F343["3.4.3 Buka WhatsApp dengan Draf Terformat"]:::submod
    M34 --> F341
    M34 --> F342
    M34 --> F343

    %% 3.5 Keranjang & Checkout
    F351["3.5.1 Tambah ke Keranjang Belanja"]:::submod
    F352["3.5.2 Mode 1: Makan di Kafe (Dine In)"]:::submod
    F353["3.5.3 Mode 2: Antar via Chat WA Zendo"]:::submod
    F354["3.5.4 Mode 3: Antar Pelacakan Langsung"]:::submod
    M35 --> F351
    M35 --> F352
    M35 --> F353
    M35 --> F354

    L351["3.5.2.1 Pilih Tunai/QRIS -> Dapatkan Slip QR Kasir"]:::leaf
    L352["3.5.3.1 Hitung Ongkir Flat -> Buka Chat WA Kurir"]:::leaf
    L353["3.5.4.1 Pilih Pin Peta -> Hitung Jarak & Ongkir GPS"]:::leaf
    F352 --> L351
    F353 --> L352
    F354 --> L353

    %% 3.6 Pelacakan
    F361["3.6.1 Lacak Status Kode Pesanan NCM"]:::submod
    F362["3.6.2 Buka Peta Live Tracking Kurir Zendo"]:::submod
    M36 --> F361
    M36 --> F362`
  },
  {
    id: "A7-kurir-zendo",
    tab: "A",
    number: "A7",
    title: "Dekomposisi Kurir Zendo (Logistik & GPS Workspace)",
    subtitle: "Rincian Fungsional: 4.1 Autentikasi PIN & Binding, 4.2 Rute & Peta, 4.3 Streaming GPS & Anti-Fake, 4.4 Status Antar",
    description: "Menjabarkan alur kerja khusus kurir Zendo di rute /zendo/kurir/{token}: verifikasi PIN perangkat (device binding), peta Leaflet rute pengantaran, streaming posisi GPS dengan filter anti-fake GPS (kecepatan, akurasi, radius), dan transisi tahapan pengantaran.",
    code: `flowchart TB
    classDef actor fill:#2A1D17,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef mod fill:#C2A378,stroke:#2A1D17,stroke-width:1px,color:#FFFFFF;
    classDef submod fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef leaf fill:#FAF8F5,stroke:#8C7E74,stroke-width:1px,color:#140E0A;

    KRR["4.0 Modul Kurir Zendo"]:::actor

    M41["4.1 Autentikasi & Akses Kurir"]:::mod
    M42["4.2 Rute & Peta Pengantaran"]:::mod
    M43["4.3 Pengiriman GPS & Anti-Fake"]:::mod
    M44["4.4 Pembaruan Tahapan Antar"]:::mod

    KRR --> M41
    KRR --> M42
    KRR --> M43
    KRR --> M44

    %% 4.1 Autentikasi
    F411["4.1.1 Buka Tautan Token Kurir<br>(/zendo/kurir/{token} atau ?k=...)"]:::submod
    F412["4.1.2 Verifikasi PIN Kurir (4 Digit) / OTP"]:::submod
    F413["4.1.3 Pengikatan Perangkat (Cookie zendo_courier_device)"]:::submod
    F414["4.1.4 Keluar / Putus Sambungan (Unbind Device)"]:::submod
    M41 --> F411
    M41 --> F412
    M41 --> F413
    M41 --> F414

    %% 4.2 Peta
    F421["4.2.1 Tampilkan Titik Lokasi Kafe"]:::submod
    F422["4.2.2 Tampilkan Titik Koordinat Pelanggan"]:::submod
    F423["4.2.3 Panduan Navigasi & Telepon Pelanggan"]:::submod
    M42 --> F421
    M42 --> F422
    M42 --> F423

    %% 4.3 Streaming GPS & Anti-Fake
    F431["4.3.1 Streaming Koordinat GPS HP Berkala"]:::submod
    F432["4.3.2 Validasi Batas Kecepatan (Max 45 m/s)"]:::submod
    F433["4.3.3 Validasi Akurasi Sinyal (Max 150m)"]:::submod
    F434["4.3.4 Validasi Radius Layanan (Max 40km)"]:::submod
    F435["4.3.5 Sistem Penguncian GPS Lock Jika Anomali"]:::submod
    M43 --> F431
    M43 --> F432
    M43 --> F433
    M43 --> F434
    M43 --> F435

    %% 4.4 Status
    F441["4.4.1 Status: Ditugaskan (Assigned)"]:::submod
    F442["4.4.2 Status: Mengambil Pesanan (Picked Up)"]:::submod
    F443["4.4.3 Status: Dalam Perjalanan (On The Way)"]:::submod
    F444["4.4.4 Status: Mendekati Lokasi (Almost Done)"]:::submod
    F445["4.4.5 Status: Selesai Diantar (Delivered)"]:::submod
    M44 --> F441
    M44 --> F442
    M44 --> F443
    M44 --> F444
    M44 --> F445`
  },

  // ==========================================
  // TAB B: DFD GANE-SARSON (8 DIAGRAM)
  // ==========================================
  {
    id: "B1-diagram-konteks",
    tab: "B",
    number: "B1",
    title: "Diagram Konteks Sistem Informasi Nucomu Cafe (DFD Tingkat 0)",
    subtitle: "Proses 0, 4 Aktor Manusia (Admin, Kasir, Customer, Kurir), 4 Entitas Luar (WhatsApp, Google Places, QRIS, Scanner)",
    description: "Diagram konteks memposisikan sistem secara utuh sebagai satu proses tunggal bernomor 0 dan memperlihatkan seluruh aliran data masuk dan keluar dari entitas luar di sekelilingnya tanpa memperlihatkan data store.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef sys fill:#E7DED1,stroke:#2A1D17,stroke-width:3px,color:#140E0A;

    SYS["0<br>SISTEM INFORMASI WEBSITE NUCOMU CAFE"]:::sys

    E_ADM["Administrator (Admin)"]:::ent
    E_KSR["Kasir (Cashier)"]:::ent
    E_CST["Customer / Pengunjung"]:::ent
    E_KRR["Kurir Zendo"]:::ent
    E_WA["WhatsApp Web / Gateway API"]:::ent
    E_GMP["Google Maps & Places API"]:::ent
    E_QRIS["Layanan QRIS Kafe"]:::ent
    E_SCN["Perangkat Scanner / Kamera HP"]:::ent

    %% Admin flows
    E_ADM -->|"Kredensial login, kelola konten, menu, harga, banner, kurasi ulasan, form builder, konfigurasi transaksi"| SYS
    SYS -->|"Dashboard analitik, grafik omzet, status notifikasi, data pesanan, rekapitulasi laporan PDF"| E_ADM

    %% Kasir flows
    E_KSR -->|"Kredensial kasir, entri pesanan langsung, update stok, kode slip pesanan, konfirmasi pembayaran lunas"| SYS
    SYS -->|"Tampilan katalog POS, rincian pesanan hasil scan QR, struk thermal kasir, data sinkronisasi"| E_KSR

    %% Customer flows
    E_CST -->|"Permintaan halaman, input ulasan & foto, isian formulir reservasi, data keranjang & checkout 3 mode"| SYS
    SYS -->|"Tampilan katalog & varian, ulasan terkurasi, slip QR pesanan kasir, tautan peta live tracking"| E_CST

    %% Kurir flows
    E_KRR -->|"Token akses kurir, PIN verifikasi, streaming koordinat GPS kurir, pembaruan tahapan pengantaran"| SYS
    SYS -->|"Rute alamat antar pelanggan, panduan koordinat, status verifikasi perangkat, peringatan GPS Lock"| E_KRR

    %% WhatsApp flows
    SYS -->|"Draf pesan pesanan Zendo terformat, draf reservasi meja terstruktur, payload kirim Fonnte API"| E_WA

    %% Google Places flows
    E_GMP -->|"Payload JSON ulasan bintang 5 & komentar pengunjung Google Maps"| SYS
    SYS -->|"Permintaan HTTP Place Details dengan Place ID & API Key"| E_GMP

    %% QRIS flows
    SYS -->|"Citra QRIS resmi kafe dari tabel settings"| E_QRIS

    %% Scanner flows
    E_SCN -->|"Hasil pembacaan scan QR kode pesanan NCM-xxx"| SYS`
  },
  {
    id: "B2-dfd-level0",
    tab: "B",
    number: "B2",
    title: "DFD Level 0 Sistem Informasi Nucomu Cafe",
    subtitle: "Dekomposisi Proses 0 Menjadi 9 Proses Utama (1.0 s/d 9.0) & 17 Data Stores Fisik (D1 s/d D17)",
    description: "Memecah proses sistem menjadi 9 proses utama: 1.0 Autentikasi, 2.0 Konten Web, 3.0 Menu & Stok, 4.0 Reservasi, 5.0 Ulasan, 6.0 Transaksi & Kasir, 7.0 Kurir Zendo, 8.0 Notifikasi, dan 9.0 Laporan & Analitik. Seluruh 17 tabel MySQL sebenarnya dipetakan.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef store fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 4 4,color:#140E0A;

    %% Entitas
    E_ADM["Administrator"]:::ent
    E_KSR["Kasir"]:::ent
    E_CST["Customer"]:::ent
    E_KRR["Kurir Zendo"]:::ent
    E_WA["WhatsApp API/Web"]:::ent
    E_GMP["Google Places API"]:::ent

    %% Proses Utama
    P1["1.0<br>Autentikasi & Sesi Pengguna"]:::proc
    P2["2.0<br>Kelola Konten & Tampilan Web"]:::proc
    P3["3.0<br>Kelola Menu, Kategori & Stok"]:::proc
    P4["4.0<br>Kelola Reservasi & Form Builder"]:::proc
    P5["5.0<br>Kelola Ulasan & Testimoni"]:::proc
    P6["6.0<br>Transaksi Pemesanan & Kasir"]:::proc
    P7["7.0<br>Logistik & Pelacakan Kurir"]:::proc
    P8["8.0<br>Pusat Notifikasi & Pesan"]:::proc
    P9["9.0<br>Laporan & Analitik Penjualan"]:::proc

    %% Data Stores
    D1[("D1: users")]:::store
    D2[("D2: categories")]:::store
    D3[("D3: menus")]:::store
    D4[("D4: menu_options")]:::store
    D5[("D5: tables")]:::store
    D6[("D6: orders")]:::store
    D7[("D7: order_items")]:::store
    D8[("D8: reservations")]:::store
    D9[("D9: reservation_fields")]:::store
    D10[("D10: settings")]:::store
    D11[("D11: operating_hours")]:::store
    D12[("D12: facilities")]:::store
    D13[("D13: gallery_items")]:::store
    D14[("D14: testimonials")]:::store
    D15[("D15: events")]:::store
    D16[("D16: zendo_deliveries")]:::store
    D17[("D17: banners")]:::store

    %% 1.0 Autentikasi
    E_ADM -->|"Email & password"| P1
    E_KSR -->|"Kata sandi kasir"| P1
    E_KRR -->|"PIN kurir & device token"| P1
    P1 <-->|"Cek akun & simpan token cookie"| D1
    P1 <-->|"Verifikasi PIN kurir & token perangkat"| D10
    P1 -->|"Sesi admin aktif"| E_ADM
    P1 -->|"Sesi kasir aktif (1 thn)"| E_KSR
    P1 -->|"Mode kurir aktif"| E_KRR

    %% 2.0 Konten Web
    E_ADM -->|"Input banner, about, fasilitas, galeri, event, jam"| P2
    P2 -->|"Tulis banner promo"| D17
    P2 -->|"Tulis narasi about & kontak"| D10
    P2 -->|"Tulis jadwal buka"| D11
    P2 -->|"Tulis fasilitas & foto slider"| D12
    P2 -->|"Tulis galeri & caption"| D13
    P2 -->|"Tulis event kafe"| D15
    D17 & D10 & D11 & D12 & D13 & D15 -->|"Baca konten aktif"| P2
    P2 -->|"Tampilan profil beranda"| E_CST

    %% 3.0 Menu & Kategori
    E_ADM -->|"Data kategori & menu baru"| P3
    E_KSR -->|"Update stok fisik cepat"| P3
    P3 <-->|"Kelola kategori & cek relasi menu"| D2
    P3 <-->|"Simpan menu, foto, harga, stok, soft delete"| D3
    P3 <-->|"Simpan varian & level gula"| D4
    D7 -->|"Cek apakah menu pernah dipesan"| P3
    P3 -->|"Katalog menu publik"| E_CST
    P3 -->|"Daftar menu POS kasir"| E_KSR

    %% 4.0 Reservasi
    E_ADM -->|"Kelola kolom form builder & no WA"| P4
    P4 <-->|"Simpan/baca struktur form"| D9
    E_CST -->|"Isian formulir reservasi"| P4
    P4 -->|"Simpan reservasi status pending"| D8
    P4 -->|"Format draf pesan WA"| E_WA
    D8 -->|"Daftar reservasi masuk"| P4
    P4 -->|"Konfirmasi status reservasi"| E_ADM

    %% 5.0 Ulasan
    E_CST -->|"Form ulasan baru & foto"| P5
    E_GMP -->|"Ulasan Google Maps"| P5
    P5 -->|"Simpan ulasan website (unpinned)"| D14
    D14 -->|"Daftar kurasi ulasan"| P5
    E_ADM -->|"Aksi pin, unpin, sembunyikan, hapus"| P5
    P5 -->|"Update status is_pinned & is_active"| D14
    P5 -->|"Tampilkan ulasan pinned di beranda"| E_CST

    %% 6.0 Transaksi & Kasir
    E_CST -->|"Checkout keranjang 3 mode"| P6
    E_KSR -->|"Entri pesanan langsung & konfirmasi lunas"| P6
    D3 -->|"Ambil harga resmi & potong stok"| P6
    D5 -->|"Pilih meja kafe"| P6
    D10 -->|"Ambil mode transaksi, QRIS & tarif"| P6
    P6 -->|"Simpan data pesanan"| D6
    P6 -->|"Simpan item pesanan"| D7
    P6 -->|"Kirim data pengiriman"| P7
    P6 -->|"Slip QR kasir"| E_CST
    P6 -->|"Draf WA pesanan Zendo"| E_WA
    P6 -->|"Cetak struk kasir thermal"| E_KSR

    %% 7.0 Kurir Zendo
    P6 -->|"Buat tugas antar baru"| P7
    E_KRR -->|"Kirim koordinat GPS & ubah status antar"| P7
    P7 <-->|"Simpan token, koordinat, jejak rute, status"| D16
    P7 -->|"Data posisi live map"| E_CST
    P7 -->|"Panduan rute & tujuan"| E_KRR

    %% 8.0 Notifikasi
    D17 & D8 & D6 & D14 -->|"Agregasi kejadian kafe"| P8
    P8 <-->|"Simpan kunci notifikasi terbaca"| D10
    P8 -->|"Daftar notifikasi & polling badge"| E_ADM

    %% 9.0 Laporan
    D6 & D7 -->|"Rekap data transaksi harian/mingguan/bulanan"| P9
    D10 -->|"Pengaturan visibilitas grafik"| P9
    P9 -->|"Grafik analitik dashboard & unduh PDF 1.4"| E_ADM`
  },
  {
    id: "B3-dfd-level1-transaksi",
    tab: "B",
    number: "B3",
    title: "DFD Level 1 Sub-Proses 6.0: Transaksi, Pemesanan, Kasir POS & Scanner Slip",
    subtitle: "Rincian Proses 6.1 s/d 6.6: Konfigurasi Mode, Dine In, Chat Zendo, Tracking Zendo, Kasir POS, Pelunasan",
    description: "Menjabarkan alur data terinci untuk 3 mode pemesanan pelanggan: perhitungan harga asli server di tabel menus, pemotongan stok otomatis, slip QR kasir, auto-dispatch kurir Zendo, live GPS map, serta pelunasan kasir dan struk thermal.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef store fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 4 4,color:#140E0A;

    E_CST["Customer"]:::ent
    E_KSR["Kasir"]:::ent
    E_ADM["Admin"]:::ent
    E_WA["WhatsApp Zendo"]:::ent

    P6_1["6.1<br>Konfigurasi Mode Transaksi<br>& Template WA"]:::proc
    P6_2["6.2<br>Pemesanan Mode 1:<br>Makan di Kafe (Dine-In)"]:::proc
    P6_3["6.3<br>Pemesanan Mode 2:<br>Antar via Chat Zendo"]:::proc
    P6_4["6.4<br>Pemesanan Mode 3:<br>Antar Pelacakan Zendo"]:::proc
    P6_5["6.5<br>Operasional POS Kasir<br>& Validasi Slip QR"]:::proc
    P6_6["6.6<br>Pelunasan, Status<br>& Cetak Struk Kasir"]:::proc

    D3[("D3: menus")]:::store
    D5[("D5: tables")]:::store
    D6[("D6: orders")]:::store
    D7[("D7: order_items")]:::store
    D10[("D10: settings")]:::store
    D16[("D16: zendo_deliveries")]:::store

    %% 6.1 Konfigurasi Mode
    E_ADM -->|"Atur status 3 mode, QRIS & template pesan"| P6_1
    P6_1 <-->|"Simpan & validasi aturan saling mengunci"| D10

    %% 6.2 Mode Dine In
    E_CST -->|"Item keranjang, meja/takeaway, tunai/QRIS"| P6_2
    D10 -->|"Baca status mode dine-in & gambar QRIS"| P6_2
    D3 -->|"Validasi ketersediaan & hitung harga server"| P6_2
    D5 -->|"Validasi nomor meja"| P6_2
    P6_2 -->|"Simpan pesanan baru status pending"| D6
    P6_2 -->|"Simpan rincian item pesanan"| D7
    P6_2 -->|"Potong stok menu"| D3
    P6_2 -->|"Halaman slip pesanan & QR barcode kasir"| E_CST

    %% 6.3 Mode Chat Zendo
    E_CST -->|"Item pesanan, nama, alamat pengantaran"| P6_3
    D10 -->|"Baca nomor WA Zendo, template & tarif dasar"| P6_3
    D3 -->|"Hitung subtotal resmi server"| P6_3
    P6_3 -->|"Simpan pesanan status processing"| D6
    P6_3 -->|"Simpan item pesanan"| D7
    P6_3 -->|"Draf pesan WhatsApp Zendo terisi otomatis"| E_WA

    %% 6.4 Mode Pelacakan Zendo
    E_CST -->|"Item pesanan, pin peta GPS, alamat lengkap"| P6_4
    D10 -->|"Ambil koordinat kafe & tabel tarif jarak"| P6_4
    P6_4 -->|"Hitung jarak Haversine & tarif bertingkat"| P6_4
    P6_4 -->|"Simpan pesanan delivery status processing"| D6
    P6_4 -->|"Simpan item pesanan"| D7
    P6_4 -->|"Buat tugas antar, token lacak & token kurir"| D16
    P6_4 -->|"Tautan pelacakan live map untuk customer"| E_CST

    %% 6.5 Kasir POS & QR Scan
    E_CST -->|"Tunjukkan slip QR pesanan"| E_KSR
    E_KSR -->|"Scan QR slip (/qr/pesanan/{code}) / order langsung"| P6_5
    D6 & D7 -->|"Ambil detail pesanan belum lunas"| P6_5
    P6_5 -->|"Tampilkan rincian pesanan di kasir"| E_KSR

    %% 6.6 Pelunasan & Struk
    E_KSR -->|"Konfirmasi terima uang / lunas & ubah status"| P6_6
    P6_6 -->|"Update payment_status=paid, status=completed"| D6
    P6_6 -->|"Cetak struk kasir thermal"| E_KSR
    E_ADM -->|"Ubah status transaksi & batalkan pesanan"| P6_6`
  },
  {
    id: "B4-dfd-level1-reservasi",
    tab: "B",
    number: "B4",
    title: "DFD Level 1 Sub-Proses 4.0: Reservasi Meja & Form Builder Dinamis",
    subtitle: "Rincian Proses 4.1 s/d 4.4: Konfigurasi Builder, Pengisian & Validasi, Format WhatsApp, Riwayat Reservasi",
    description: "Menjabarkan alur data pembangun formulir kustom oleh admin di tabel reservation_fields, validasi isian dinamis (termasuk foto bukti transfer maks 2MB), penyimpanan reservasi di tabel reservations, dan perakitan tautan wa.me terformat.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef store fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 4 4,color:#140E0A;

    E_ADM["Administrator"]:::ent
    E_CST["Customer"]:::ent
    E_WA["WhatsApp Reservasi"]:::ent

    P4_1["4.1<br>Konfigurasi Form Builder<br>& Pengaturan No WA"]:::proc
    P4_2["4.2<br>Pengisian Formulir<br>& Validasi Isian Dinamis"]:::proc
    P4_3["4.3<br>Penyusunan Pesan<br>Format WhatsApp"]:::proc
    P4_4["4.4<br>Manajemen Status<br>& Riwayat Reservasi"]:::proc

    D8[("D8: reservations")]:::store
    D9[("D9: reservation_fields")]:::store
    D10[("D10: settings")]:::store

    %% 4.1 Form builder
    E_ADM -->|"Tambah kolom (teks/angka/foto/checkbox), toggle aktif, set WA"| P4_1
    P4_1 <-->|"Simpan konfigurasi kolom reservasi"| D9
    P4_1 <-->|"Simpan nomor tujuan WhatsApp reservasi"| D10

    %% 4.2 Pengisian & validasi
    D9 -->|"Ambil daftar kolom aktif & aturan validasi"| P4_2
    E_CST -->|"Submit isian data, tanggal, jam, tamu & berkas foto"| P4_2
    P4_2 -->|"Validasi tipe (maks 2MB foto, min 8 digit HP, format tgl)"| P4_2
    P4_2 -->|"Simpan reservasi status pending & form_data JSON"| D8

    %% 4.3 Format WA
    P4_2 -->|"Data reservasi terverifikasi"| P4_3
    D10 -->|"Ambil nomor WA reservasi kafe"| P4_3
    P4_3 -->|"Kirim pesan terformat rapi dengan tautan foto"| E_WA
    P4_3 -->|"URL redirect wa.me"| E_CST

    %% 4.4 Manajemen Admin
    D8 -->|"Ambil daftar reservasi masuk"| P4_4
    P4_4 -->|"Tampilkan daftar & filter status"| E_ADM
    E_ADM -->|"Konfirmasi (Confirmed/Completed/Cancelled) atau Hapus"| P4_4
    P4_4 -->|"Update status / hapus rekaman"| D8`
  },
  {
    id: "B5-dfd-level1-ulasan",
    tab: "B",
    number: "B5",
    title: "DFD Level 1 Sub-Proses 5.0: Ulasan & Testimoni Pelanggan",
    subtitle: "Rincian Proses 5.1 s/d 5.4: Penerimaan Web (Unpinned), Sinkronisasi Google Maps, Kurasi Admin, Penayangan Beranda",
    description: "Menjabarkan alur ulasan dari form web publik (tersimpan dengan is_pinned=false), sinkronisasi ulasan bintang 5 Google Maps Places API, kurasi admin (pin/unpin/sembunyi/hapus), hingga penayangan ulasan terpilih ke beranda.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef store fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 4 4,color:#140E0A;

    E_CST["Customer"]:::ent
    E_GMP["Google Places API"]:::ent
    E_ADM["Administrator"]:::ent

    P5_1["5.1<br>Penerimaan Ulasan Website<br>(is_pinned Awal: False)"]:::proc
    P5_2["5.2<br>Sinkronisasi & Cache<br>Ulasan Google Maps"]:::proc
    P5_3["5.3<br>Kurasi Ulasan Admin<br>(Pin, Unpin, Sembunyi, Hapus)"]:::proc
    P5_4["5.4<br>Penggabungan & Penayangan<br>Ulasan di Beranda"]:::proc

    D14[("D14: testimonials")]:::store

    %% 5.1 Kirim Ulasan
    E_CST -->|"Nama, rating (1-5), komentar, tag menu, upload foto ulasan"| P5_1
    P5_1 -->|"Validasi gambar maks 5MB & simpan status is_pinned=false"| D14
    P5_1 -->|"Konfirmasi ulasan terkirim & tunggu kurasi"| E_CST

    %% 5.2 Google Maps Sync
    E_GMP -->|"Data ulasan Google Maps via Places Details"| P5_2
    P5_2 -->|"Cache 6 jam ulasan Google Maps"| P5_4

    %% 5.3 Kurasi Admin
    D14 -->|"Daftar seluruh ulasan web masuk"| P5_3
    P5_3 -->|"Tampilkan daftar ulasan & metrik rating"| E_ADM
    E_ADM -->|"Perintah: Pin ulasan, Unpin, Toggle Aktif, Hapus ulasan/foto"| P5_3
    P5_3 -->|"Update status is_pinned & is_active atau hapus berkas fisik"| D14

    %% 5.4 Penayangan Publik
    D14 -->|"Ambil ulasan web yang is_active=true & is_pinned=true"| P5_4
    P5_4 -->|"Daftar ulasan terpilih (Google + Web Pinned)"| E_CST`
  },
  {
    id: "B6-dfd-level1-autentikasi",
    tab: "B",
    number: "B6",
    title: "DFD Level 1 Sub-Proses 1.0: Autentikasi, Sesi Pengguna & Otorisasi",
    subtitle: "Rincian Proses 1.1 s/d 1.4: Login Admin, Login Kasir Cookie 1 Tahun, Verifikasi PIN Kurir, Logout",
    description: "Menjabarkan mekanisme keamanan sistem untuk 3 peran: verifikasi password admin (cookie HMAC nucomu_admin_auth_v1 30 hari), verifikasi kata sandi kasir (cookie nucomu_kasir_auth_v2 1 tahun), PIN kurir Zendo (cookie binding perangkat), serta invalidasi sesi saat logout.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef store fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 4 4,color:#140E0A;

    E_ADM["Administrator"]:::ent
    E_KSR["Kasir"]:::ent
    E_KRR["Kurir Zendo"]:::ent

    P1_1["1.1<br>Login & Verifikasi Admin<br>(Role: admin)"]:::proc
    P1_2["1.2<br>Login & Sesi Kasir POS<br>(Role: kasir / admin)"]:::proc
    P1_3["1.3<br>Verifikasi PIN Kurir<br>& Device Binding"]:::proc
    P1_4["1.4<br>Logout & Invalidation Sesi"]:::proc

    D1[("D1: users")]:::store
    D10[("D10: settings")]:::store

    %% 1.1 Admin Login
    E_ADM -->|"Email & password (/admin/login)"| P1_1
    D1 -->|"Cek hash password & verifikasi role='admin'"| P1_1
    P1_1 -->|"Set cookie nucomu_admin_auth_v1 (30 hari) & redirect"| E_ADM

    %% 1.2 Kasir Login
    E_KSR -->|"Kata sandi kasir (/kasir/login)"| P1_2
    D1 -->|"Verifikasi akun kasir@nucomu.com"| P1_2
    P1_2 -->|"Set cookie nucomu_kasir_auth_v2 (1 tahun) & sesi kasir"| E_KSR

    %% 1.3 Kurir PIN
    E_KRR -->|"Input PIN kurir 4 digit & token pengantaran"| P1_3
    D10 -->|"Cek kecocokan PIN & status device revoked"| P1_3
    P1_3 -->|"Set cookie zendo_courier_device & buka mode kurir"| E_KRR

    %% 1.4 Logout
    E_ADM & E_KSR -->|"Permintaan logout"| P1_4
    P1_4 -->|"Hapus sesi, regenerate CSRF & lupakan cookie"| D1`
  },
  {
    id: "B7-dfd-level1-menu",
    tab: "B",
    number: "B7",
    title: "DFD Level 1 Sub-Proses 3.0: Katalog Menu, Kategori & Kontrol Stok",
    subtitle: "Rincian Proses 3.1 s/d 3.4: Kelola Kategori & Proteksi Relasi, Tambah/Edit Menu, Kontrol Stok, Hapus Menu Berproteksi",
    description: "Menjabarkan alur data manajemen kategori dan menu: penolakan hapus kategori bila masih berisi menu, penolakan force delete menu yang tercatat pada order_items (soft delete aman), pemulihan menu (restore), dan kontrol ketersediaan/stok.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef store fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 4 4,color:#140E0A;

    E_ADM["Administrator"]:::ent
    E_KSR["Kasir"]:::ent
    E_CST["Customer"]:::ent

    P3_1["3.1<br>Kelola Kategori Menu<br>& Cek Proteksi Relasi"]:::proc
    P3_2["3.2<br>Tambah/Edit Menu & Foto<br>(Wajib 1:1, Harga, Opsi Varian)"]:::proc
    P3_3["3.3<br>Kontrol Stok & Ketersediaan<br>(Admin & Kasir Real-Time)"]:::proc
    P3_4["3.4<br>Hapus Menu Berproteksi<br>& Pemulihan (Restore)"]:::proc

    D2[("D2: categories")]:::store
    D3[("D3: menus")]:::store
    D4[("D4: menu_options")]:::store
    D7[("D7: order_items")]:::store

    %% 3.1 Kategori
    E_ADM -->|"Nama kategori, slug, urutan, perintah hapus"| P3_1
    D3 -->|"Hitung jumlah menu di dalam kategori"| P3_1
    P3_1 -->|"Tolak hapus jika menu > 0 / Simpan kategori"| D2
    P3_1 -->|"Pesan sukses/peringatan kategori"| E_ADM

    %% 3.2 Tambah/Edit Menu
    E_ADM -->|"Data menu, kategori_id, foto 1:1, varian"| P3_2
    D2 -->|"Validasi keberadaan kategori"| P3_2
    P3_2 -->|"Simpan menu baru dengan stok awal default 25"| D3
    P3_2 -->|"Simpan varian penyajian & gula"| D4

    %% 3.3 Stok & Ketersediaan
    E_ADM & E_KSR -->|"Toggle tersedia/habis & ubah stok fisik"| P3_3
    P3_3 <-->|"Perbarui kolom stock & is_available"| D3
    D3 & D4 -->|"Data katalog menu aktif"| E_CST

    %% 3.4 Hapus Menu Berproteksi
    E_ADM -->|"Permintaan hapus menu (soft / force)"| P3_4
    D7 -->|"Cek apakah menu pernah tercatat di order_items"| P3_4
    P3_4 -->|"Jika pernah dipesan: tolak force delete, jalankan soft delete"| D3
    P3_4 -->|"Jika belum pernah dipesan: hapus berkas foto & force delete"| D3
    P3_4 -->|"Pulihkan menu terhapus (restore)"| D3
    P3_4 -->|"Konfirmasi proteksi / penghapusan menu"| E_ADM`
  },
  {
    id: "B8-dfd-level1-kurir",
    tab: "B",
    number: "B8",
    title: "DFD Level 1 Sub-Proses 7.0: Logistik & Pelacakan Kurir Zendo",
    subtitle: "Rincian Proses 7.1 s/d 7.4: Auto-Dispatch Kurir, Peta & Rute Leaflet, Streaming GPS & Anti-Fake, Status Antar",
    description: "Menjabarkan siklus logistik kurir Zendo: algoritma pemilihan kurir idle / almost done, peta Leaflet interaktif, filter anti-fake GPS (kecepatan maksimal 45m/s, akurasi sinyal 150m, radius 40km), penguncian GPS Lock jika anomali berulang, dan penyelesaian pesanan.",
    code: `flowchart TB
    classDef ent fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef store fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 4 4,color:#140E0A;

    E_KRR["Kurir Zendo"]:::ent
    E_CST["Customer"]:::ent
    E_ADM["Admin"]:::ent

    P7_1["7.1<br>Penugasan Pesanan Otomatis<br>(Auto-Dispatch Kurir)"]:::proc
    P7_2["7.2<br>Penyajian Peta & Rute Antar<br>(Leaflet.js & Titik Tujuan)"]:::proc
    P7_3["7.3<br>Streaming Koordinat GPS<br>& Validasi Anti-Fake GPS"]:::proc
    P7_4["7.4<br>Pembaruan Tahapan Antar<br>& Penyelesaian Pesanan"]:::proc

    D6[("D6: orders")]:::store
    D10[("D10: settings")]:::store
    D16[("D16: zendo_deliveries")]:::store

    %% 7.1 Penugasan
    D6 -->|"Pesanan delivery Zendo baru"| P7_1
    D10 -->|"Daftar armada kurir & waktu pesanan terakhir"| P7_1
    P7_1 -->|"Pilih kurir santai (idle) -> hampir selesai"| P7_1
    P7_1 -->|"Simpan penugasan kurir & buat pasangan token"| D16

    %% 7.2 Peta
    D16 -->|"Ambil koordinat kafe & pelanggan via token"| P7_2
    P7_2 -->|"Tampilkan peta rute pengantaran ke kurir"| E_KRR
    P7_2 -->|"Tampilkan peta live tracking ke customer"| E_CST

    %% 7.3 Streaming GPS
    E_KRR -->|"Kirim lat, lng, akurasi, kecepatan, heading"| P7_3
    D10 -->|"Ambil ambang batas kecepatan & radius layanan"| P7_3
    P7_3 -->|"Cek kecepatan (>45m/s), akurasi (>150m), radius (>40km)"| P7_3
    P7_3 -->|"Simpan koordinat valid, waktu & jejak rute JSON"| D16
    P7_3 -->|"Kunci GPS kurir (GPS Lock) jika anomali berulang"| E_KRR

    %% 7.4 Status Antar
    E_KRR -->|"Ubah status: picked_up -> on_the_way -> delivered"| P7_4
    P7_4 -->|"Update kolom status & stempel waktu di delivery"| D16
    P7_4 -->|"Update status pesanan menjadi completed"| D6
    P7_4 -->|"Notifikasi pesanan telah sampai di tangan customer"| E_CST
    D16 -->|"Pantau armada & reset perangkat kurir"| E_ADM`
  },

  // ==========================================
  // TAB C: ALGORITMA & FLOWCHART (15 DIAGRAM)
  // ==========================================
  {
    id: "C1-algoritma-keseluruhan",
    tab: "C",
    number: "C1",
    title: "Flowchart Algoritma Keseluruhan Sistem Nucomu Cafe (End-to-End)",
    subtitle: "Gambaran Umum Siklus Hidup Sistem: Pengunjung Publik, Checkout 3 Jalur, Kasir POS, Kurir Zendo & Administrasi",
    description: "Bagan alir tingkat tinggi yang merangkum keseluruhan sistem informasi Nucomu Cafe dari saat pengunjung mengakses website, memilih menu makanan/minuman, memesan melalui salah satu dari 3 mode transaksi, reservasi meja dinamis, hingga operasional internal kasir POS, kurir Zendo, dan manajemen admin.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    START([Mulai]):::startEnd
    BROWSE[/"Pengunjung Mengakses Website Nucomu Cafe"/]:::io
    READ_HOME["Sistem Menampilkan Hero Banner, Profil About, Fasilitas, Menu, dan Ulasan Pilihan"]:::process
    DEC_ACTION{"Pilihan Aksi Pengunjung?"}:::decision

    %% Aksi 1: Menu & Pesan
    ACT_ORDER["Pilih Menu, Varian, dan Masukkan ke Keranjang"]:::process
    DEC_MODE{"Pilih Metode Transaksi?"}:::decision
    ORDER_DINE["Checkout Makan di Kafe (Dine In) -> Dapatkan Slip QR Kasir"]:::process
    ORDER_CHAT["Checkout Antar Chat -> Kirim Draf Pesanan ke WA Kurir Zendo"]:::process
    ORDER_TRACK["Checkout Antar GPS -> Buat Pengiriman Zendo & Buka Peta Live Tracking"]:::process

    %% Aksi 2: Reservasi
    ACT_RES["Isi Formulir Reservasi Meja Dinamis -> Simpan DB & Buka WA Kafe"]:::process

    %% Aksi 3: Ulasan
    ACT_REV["Kirim Ulasan & Upload Foto Pengalaman -> Masuk Status Unpinned"]:::process

    %% Operasional Internal
    KASIR_OPS["Kasir POS: Scan Slip QR Customer / Entri Pesanan Langsung -> Konfirmasi Lunas & Cetak Struk"]:::process
    KURIR_OPS["Kurir Zendo: Buka Tautan Kurir -> Verifikasi PIN -> Streaming GPS & Update Status Antar"]:::process
    ADMIN_OPS["Admin: Kurasi Ulasan (Pin), Kelola Banner/Menu, Monitor Pesanan, & Ekspor Laporan PDF"]:::process

    END([Selesai]):::startEnd

    START --> BROWSE --> READ_HOME --> DEC_ACTION
    DEC_ACTION -- "Pesan Makanan/Minuman" --> ACT_ORDER --> DEC_MODE
    DEC_MODE -- "Makan di Kafe" --> ORDER_DINE --> KASIR_OPS
    DEC_MODE -- "Antar Chat WA" --> ORDER_CHAT
    DEC_MODE -- "Antar Lacak GPS" --> ORDER_TRACK --> KURIR_OPS
    DEC_ACTION -- "Reservasi Meja" --> ACT_RES --> ADMIN_OPS
    DEC_ACTION -- "Kirim Ulasan" --> ACT_REV --> ADMIN_OPS
    KASIR_OPS --> ADMIN_OPS --> END
    ORDER_CHAT --> END
    KURIR_OPS --> END`,
    steps: [
      "Pengunjung membuka website Nucomu Cafe dan menjelajahi informasi beranda (hero banner, filosofi Our Story, fasilitas kafe, katalog menu, dan ulasan pilihan).",
      "Pengunjung memilih salah satu interaksi utama: (a) Memesan menu di keranjang belanja, (b) Mengisi formulir reservasi meja, atau (c) Mengirimkan ulasan & foto pengalaman.",
      "Jika memesan menu, pengunjung memilih 1 dari 3 mode transaksi: Mode 1 Makan di Kafe (menghasilkan Slip QR), Mode 2 Antar Chat WA (draf pesan WA ke kurir), atau Mode 3 Antar Pelacakan GPS (live tracking peta Leaflet).",
      "Staf kasir memvalidasi slip QR pelanggan di POS, menerima pembayaran (Tunai/QRIS), memperbarui stok, dan mencetak struk thermal.",
      "Kurir Zendo menerima tugas antar otomatis, membuka tautan kurir ber-PIN, menyiarkan koordinat GPS, dan memperbarui tahapan antar hingga pesanan selesai.",
      "Administrator memantau metrik penjualan di dashboard, mengurasi ulasan masuk, mengelola banner, dan mengekspor laporan rekapitulasi PDF 1.4."
    ],
    dbMatrix: [
      {
        step: "Inisialisasi Beranda",
        tables: "banners, facilities, menus, testimonials, settings",
        readCols: "is_active, start_time, end_time, is_pinned, is_available",
        writeColsYes: "Tidak ada (operasi baca)",
        writeColsNo: "Tidak ada",
        errorMessage: "Fallback ke nilai default pengaturan jika tabel settings kosong"
      },
      {
        step: "Pemesanan Pelanggan",
        tables: "orders, order_items, menus, settings, zendo_deliveries",
        readCols: "menus.price, menus.stock, settings.value",
        writeColsYes: "orders (code, total, status), order_items, menus.stock berkurang",
        writeColsNo: "Tidak ada penulisan data jika validasi gagal",
        errorMessage: "'Keranjang kosong', 'Menu tidak ditemukan', 'Stok habis' (HTTP 422)"
      },
      {
        step: "Operasional Kasir",
        tables: "orders, order_items, menus",
        readCols: "orders.code, orders.payment_status",
        writeColsYes: "orders.payment_status='paid', orders.status='completed'",
        writeColsNo: "Tidak ada",
        errorMessage: "'Pesanan tidak ditemukan' jika kode NCM salah"
      }
    ]
  },
  {
    id: "C2-alur-autentikasi",
    tab: "C",
    number: "C2",
    title: "Flowchart Autentikasi Pengguna & Pengecekan Sesi Cookie HMAC",
    subtitle: "Login Admin (nucomu_admin_auth_v1 30 Hari), Login Kasir (nucomu_kasir_auth_v2 1 Tahun), Logout & Invalidation",
    description: "Menggambarkan alur pengecekan kredensial login admin dan kasir, pembentukan token cookie HMAC terenkripsi, pengalihan otomatis berdasarkan peran user (admin vs kasir), serta invalidasi sesi saat logout.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S1([Mulai: Pengguna Akses Halaman Login]):::startEnd
    IN1[/"Input Email & Password"/]:::io
    C1{"Periksa Kredensial via Auth::attempt?"}:::decision
    C2{"Periksa Nilai Kolom user.role?"}:::decision
    P_ERR1[/"Tolak: Email atau password salah"/]:::io
    P_ERR2[/"Tolak: Akun Anda tidak memiliki akses admin"/]:::io
    P_KASIR["Set Sesi Kasir & Pasang Cookie nucomu_kasir_auth_v2 (1 Tahun)"]:::process
    P_ADM["Set Sesi Admin & Pasang Cookie nucomu_admin_auth_v1 (30 Hari)"]:::process
    R_KASIR[/"Redirect ke /kasir (Workspace Kasir POS)"/]:::io
    R_ADM[/"Redirect ke /admin/dashboard"/]:::io
    E1([Selesai]):::startEnd

    S1 --> IN1 --> C1
    C1 -- Tidak --> P_ERR1 --> E1
    C1 -- Ya --> C2
    C2 -- "role == 'kasir'" --> P_KASIR --> R_KASIR --> E1
    C2 -- "role == 'user' (bukan admin)" --> P_ERR2 --> E1
    C2 -- "role == 'admin'" --> P_ADM --> R_ADM --> E1`,
    steps: [
      "Admin membuka /admin/login, memasukkan email dan password, lalu mengklik 'Masuk ke Dashboard'.",
      "Sistem memverifikasi kecocokan email dan hash password di tabel users via Auth::attempt().",
      "Jika user.role == 'admin', sistem meregenerasi sesi, memasang cookie nucomu_admin_auth_v1 (30 hari), dan mengarahkan ke /admin/dashboard.",
      "Jika user.role == 'kasir', sistem otomatis mengalihkan ke antarmuka kasir /kasir dan memasang cookie nucomu_kasir_auth_v2 (1 tahun).",
      "Jika peran bukan admin/kasir, akses ditolak: 'Akun Anda tidak memiliki akses admin.'",
      "Saat logout diklik, sesi dimusnahkan, token CSRF diregenerasi, dan cookie autentikasi dihapus dari peramban."
    ],
    dbMatrix: [
      {
        step: "Login Admin",
        tables: "users",
        readCols: "id, email, password, role",
        writeColsYes: "Session state & cookie nucomu_admin_auth_v1 (30 hari)",
        writeColsNo: "Tidak ada penulisan data DB",
        errorMessage: "'Email atau password salah.' atau 'Akun Anda tidak memiliki akses admin.'"
      },
      {
        step: "Login Kasir",
        tables: "users",
        readCols: "id, email, password, role",
        writeColsYes: "Session kasir_authenticated & cookie nucomu_kasir_auth_v2 (1 tahun)",
        writeColsNo: "Tidak ada penulisan data DB",
        errorMessage: "'Kata sandi kasir yang Anda masukkan salah.'"
      },
      {
        step: "Logout",
        tables: "sessions (bila driver database)",
        readCols: "id, payload",
        writeColsYes: "Sesi dihapus, cookie dihapus (forget)",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C3-alur-banner-promo",
    tab: "C",
    number: "C3",
    title: "Flowchart Penambahan & Penayangan Banner Promo",
    subtitle: "Validasi Landscape Min 1600px/MP4 20MB, Jadwal, Hitung Mundur, Notif 30 Menit, Auto-Expire",
    description: "Menggambarkan alur validasi unggahan media banner (dimensi foto minimal lebar 1600px landscape atau video MP4 maks 20MB), penentuan jadwal aktif (start_time dan end_time), penyimpanan berkas fisik, dan filter logika penayangan banner aktif di beranda.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S2([Mulai]):::startEnd
    IN2[/"Admin Unggah Berkas & Isi Jadwal Banner"/]:::io
    C_MEDIA{"Validasi Tipe & Dimensi Media?"}:::decision
    C_TIME{"Validasi end_time >= start_time?"}:::decision
    ERR_MED[/"Tolak: Ukuran minimal lebar 1600px landscape atau video max 20MB"/]:::io
    ERR_TIM[/"Tolak: Tanggal selesai harus sama atau setelah tanggal mulai"/]:::io
    SAVE_FILE["Simpan Berkas ke public/uploads/banners/"]:::process
    SAVE_DB["Insert Baris Baru ke Tabel banners"]:::process
    WEB_CHECK{"Pelanggan Buka Beranda: is_active && start <= now <= end?"}:::decision
    SHOW_SLIDE[/"Tampilkan Banner pada Slider Beranda"/]:::io
    HIDE_SLIDE["Sembunyikan Banner dari Beranda (Expired/Nonaktif)"]:::process
    E2([Selesai]):::startEnd

    S2 --> IN2 --> C_MEDIA
    C_MEDIA -- Gagal --> ERR_MED --> E2
    C_MEDIA -- Lolos --> C_TIME
    C_TIME -- Gagal --> ERR_TIM --> E2
    C_TIME -- Lolos --> SAVE_FILE --> SAVE_DB --> WEB_CHECK
    WEB_CHECK -- Ya --> SHOW_SLIDE --> E2
    WEB_CHECK -- Tidak --> HIDE_SLIDE --> E2`,
    steps: [
      "Admin membuka /admin/banner/tambah, mengisi judul, memilih tipe media (gambar/video), tanggal mulai, dan tanggal selesai.",
      "Sistem memvalidasi berkas: gambar wajib berorientasi landscape lebar minimal 1600px (maks 5MB); video wajib format MP4/WebM/MOV (maks 20MB).",
      "Sistem memvalidasi rentang waktu: tanggal selesai harus sama atau setelah tanggal mulai.",
      "Berkas disimpan ke direktori public/uploads/banners/ dan baris banner baru di-insert ke tabel banners.",
      "Saat pelanggan mengakses beranda, sistem hanya merender banner yang is_active=true dan start_time <= NOW <= end_time.",
      "Jika sisa masa tayang <= 30 menit, sistem mendeteksi via endpoint /admin/banner/expiring-check dan menampilkan peringatan di dashboard."
    ],
    dbMatrix: [
      {
        step: "Tambah Banner",
        tables: "banners",
        readCols: "-",
        writeColsYes: "title, subtitle, description, media_type, media_path, layout_mode, start_time, end_time, button_text, button_link, is_active, sort_order",
        writeColsNo: "Tidak ada penulisan data DB, berkas fisik dibatalkan",
        errorMessage: "'Ukuran foto minimal lebar 1600 piksel' atau 'Tanggal selesai harus setelah tanggal mulai.'"
      },
      {
        step: "Perpanjang Masa Tayang",
        tables: "banners",
        readCols: "id, end_time",
        writeColsYes: "end_time (ditambah durasi baru), is_active=true",
        writeColsNo: "Tidak ada penulisan",
        errorMessage: "'Durasi perpanjangan tidak valid.'"
      },
      {
        step: "Hapus Banner",
        tables: "banners",
        readCols: "id, media_path",
        writeColsYes: "Baris dihapus (DELETE), berkas di uploads/banners/ di-unlink",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C4-alur-konten-profil",
    tab: "C",
    number: "C4",
    title: "Flowchart Konten Profil & Fasilitas Multi-Foto",
    subtitle: "Our Story ke settings, Fasilitas Multi-Foto JSON Array, Slider Interaktif & Galeri Foto",
    description: "Menggambarkan alur penambahan dan pengeditan fasilitas kafe yang mendukung unggahan banyak foto carousel (disimpan dalam format JSON di kolom photos dan features), serta pembaruan narasi Our Story ke tabel settings.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S3([Mulai]):::startEnd
    IN3[/"Admin Input Nama Fasilitas, Fitur & Unggah Foto-Foto"/]:::io
    C_FAS{"Validasi Format Foto (JPG/PNG/WebP maks 2MB)?"}:::decision
    ERR_FAS[/"Tolak: Format foto tidak valid atau melebihi 2MB"/]:::io
    PROC_FAS["Simpan Array Foto ke public/uploads/facilities/ & Encode JSON"]:::process
    SAVE_FAS["Insert / Update ke Tabel facilities (photos JSON, features JSON)"]:::process
    PUB_FAS[/"Pengunjung Melihat Slider Fasilitas Interaktif di Beranda"/]:::io
    E3([Selesai]):::startEnd

    S3 --> IN3 --> C_FAS
    C_FAS -- Gagal --> ERR_FAS --> E3
    C_FAS -- Lolos --> PROC_FAS --> SAVE_FAS --> PUB_FAS --> E3`,
    steps: [
      "Admin membuka /admin/fasilitas, mengisi nama fasilitas, badge, ikon, deskripsi, dan fitur checklist.",
      "Admin mengunggah beberapa slide foto sekaligus untuk fasilitas tersebut.",
      "Sistem memvalidasi setiap berkas foto (format JPG/PNG/WebP, ukuran maksimal 2MB per foto).",
      "Berkas dipindahkan ke public/uploads/facilities/, path berkas dikemas menjadi array JSON dan disimpan ke kolom photos di tabel facilities.",
      "Pengunjung website melihat kartu fasilitas dengan slider foto interaktif di beranda."
    ],
    dbMatrix: [
      {
        step: "Update About / Our Story",
        tables: "settings",
        readCols: "key, value",
        writeColsYes: "key, value untuk 9 kunci narasi dan path media",
        writeColsNo: "Tidak ada penulisan",
        errorMessage: "'Label kategori wajib diisi.' atau 'Judul cerita wajib diisi.'"
      },
      {
        step: "Simpan Fasilitas Multi-Foto",
        tables: "facilities",
        readCols: "-",
        writeColsYes: "name, slug, tab_name, title, badge, icon, description, features (JSON), photos (JSON), is_active, sort_order",
        writeColsNo: "Tidak ada penulisan",
        errorMessage: "'Nama fasilitas wajib diisi' atau 'Format foto harus JPG/PNG maks 2MB.'"
      },
      {
        step: "Simpan Galeri",
        tables: "gallery_items",
        readCols: "-",
        writeColsYes: "title, category, image_path, caption, sort_order, is_active",
        writeColsNo: "Tidak ada penulisan",
        errorMessage: "'Ukuran foto maksimal 2 MB.'"
      }
    ]
  },
  {
    id: "C5-alur-menu-kategori",
    tab: "C",
    number: "C5",
    title: "Flowchart Logika Proteksi Hapus Kategori & Menu",
    subtitle: "Tolak Hapus Kategori Jika Ada Menu, Tolak Force Delete Menu Pernah Dipesan (Soft Delete), Restore Menu",
    description: "Menggambarkan aturan ketat perlindungan integritas basis data: kategori tidak boleh dihapus jika masih menaungi menu, menu yang pernah memiliki riwayat pemesanan di order_items ditolak keras untuk dihapus permanen (hanya boleh soft delete), dan menu nonaktif dapat dipulihkan.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S4([Mulai]):::startEnd
    DEC_TYPE{"Objek yang Dihapus?"}:::decision

    %% Kategori
    C_CAT{"Kategori Masih Memiliki Menu (menus.count > 0)?"}:::decision
    ERR_CAT[/"Tolak: Kategori masih berisi menu, pindahkan menunya dulu"/]:::io
    DEL_CAT["Hapus Baris dari Tabel categories"]:::process

    %% Menu
    C_MENU{"Menu Pernah Dipesan (order_items.count > 0)?"}:::decision
    C_FORCE{"Apakah Diminta Force Delete (Hapus Permanen)?"}:::decision
    ERR_FORCE[/"Tolak: Menu sudah pernah dipesan, tidak dapat dihapus permanen"/]:::io
    SOFT_DEL["Jalankan Soft Delete (Isi Kolom menus.deleted_at)"]:::process
    HARD_DEL["Hapus Berkas Foto Fisik & Jalankan forceDelete()"]:::process

    E4([Selesai]):::startEnd

    S4 --> DEC_TYPE
    DEC_TYPE -- "Kategori" --> C_CAT
    C_CAT -- Ya --> ERR_CAT --> E4
    C_CAT -- Tidak --> DEL_CAT --> E4

    DEC_TYPE -- "Menu" --> C_MENU
    C_MENU -- Ya --> C_FORCE
    C_FORCE -- Ya --> ERR_FORCE --> E4
    C_FORCE -- Tidak --> SOFT_DEL --> E4
    C_MENU -- Tidak --> HARD_DEL --> E4`,
    steps: [
      "Admin memilih menu atau kategori yang ingin dihapus pada antarmuka manajemen.",
      "Jika menghapus kategori: sistem menghitung jumlah menu aktif pada kategori tersebut. Jika > 0, penghapusan ditolak dengan peringatan untuk memindahkan menunya terlebih dahulu.",
      "Jika menghapus menu: sistem memeriksa apakah menu.id pernah tercatat pada tabel order_items.",
      "Jika menu pernah dipesan dan admin memilih 'Hapus Permanen' (force delete), sistem menolak keras untuk menjaga integritas data audit pembukuan.",
      "Jika menu pernah dipesan dan admin memilih 'Hapus Biasa', sistem menjalankan Soft Delete (mengisi deleted_at), sehingga menu tersembunyi dari website tetapi riwayat transaksi tetap utuh.",
      "Jika menu belum pernah dipesan, berkas foto fisik dihapus dari disk dan baris data dihapus permanen."
    ],
    dbMatrix: [
      {
        step: "Hapus Kategori",
        tables: "categories, menus",
        readCols: "categories.id, menus.category_id",
        writeColsYes: "Baris categories dihapus (DELETE)",
        writeColsNo: "Tidak ada baris dihapus jika menus.count > 0",
        errorMessage: "'Kategori masih berisi X menu. Pindahkan atau hapus menunya dulu.'"
      },
      {
        step: "Hapus Menu Pernah Dipesan",
        tables: "menus, order_items",
        readCols: "menus.id, order_items.menu_id",
        writeColsYes: "menus.deleted_at = NOW() (Soft delete aman)",
        writeColsNo: "Tolak jika force=true, tidak ada perubahan",
        errorMessage: "'Menu sudah pernah dipesan pelanggan sehingga tidak dapat dihapus permanen...'"
      },
      {
        step: "Hapus Menu Tanpa Riwayat",
        tables: "menus, order_items",
        readCols: "menus.id, menus.image",
        writeColsYes: "forceDelete() pada menus & unlink foto fisik",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C6-alur-kurasi-ulasan",
    tab: "C",
    number: "C6",
    title: "Flowchart Pengiriman & Kurasi Ulasan Pelanggan",
    subtitle: "Kirim Unpinned (Default False), Kurasi Admin (Pin/Unpin/Toggle/Hapus), Tampilan Gabungan Web + Google Maps",
    description: "Menggambarkan alur pengiriman ulasan dari pengunjung publik yang otomatis berstatus unpinned (is_pinned=false) untuk mencegah spam, proses kurasi admin di /admin/testimoni (pinning, unpin, hide, delete), dan penggabungan dengan data ulasan Google Maps di beranda.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S5([Mulai]):::startEnd
    IN5[/"Customer Isi Form Ulasan & Upload Foto"/]:::io
    C_VAL5{"Validasi Formulir & Ukuran Foto (<= 5MB)?"}:::decision
    ERR5[/"Tolak: Nama, rating bintang, dan ulasan wajib diisi"/]:::io
    SAVE_REV["Simpan ke testimonials (is_pinned = false, is_active = true)"]:::process
    ADM_SEE[/"Admin Memeriksa Daftar Ulasan di /admin/testimoni"/]:::io
    DEC_PIN{"Keputusan Kurasi Admin?"}:::decision
    DO_PIN["Admin Klik 'Pin Ulasan' (is_pinned = true, is_active = true)"]:::process
    DO_HIDE["Admin Klik 'Sembunyikan' (is_active = false, is_pinned = false)"]:::process
    DO_DEL["Admin Klik 'Hapus' (Hapus Baris DB & Unlink File Foto)"]:::process
    PUB_REV[/"Ulasan Ditayangkan pada Slider Beranda Website"/]:::io
    E5([Selesai]):::startEnd

    S5 --> IN5 --> C_VAL5
    C_VAL5 -- Gagal --> ERR5 --> E5
    C_VAL5 -- Lolos --> SAVE_REV --> ADM_SEE --> DEC_PIN
    DEC_PIN -- "Sematkan (Pin)" --> DO_PIN --> PUB_REV --> E5
    DEC_PIN -- "Sembunyikan" --> DO_HIDE --> E5
    DEC_PIN -- "Hapus Permanen" --> DO_DEL --> E5`,
    steps: [
      "Pelanggan membuka form ulasan di website, mengisi nama, rating 1-5 bintang, ulasan teks, dan mengunggah foto suasana/menu.",
      "Sistem memvalidasi formulir dan membatasi ukuran foto maksimal 5MB.",
      "Ulasan disimpan ke tabel testimonials dengan is_pinned = false dan is_active = true (belum langsung tayang di beranda publik).",
      "Admin membuka panel /admin/testimoni untuk meninjau ulasan baru yang masuk.",
      "Admin mengklik 'Pin Ulasan' pada ulasan berkualitas; status is_pinned berubah menjadi true dan ulasan langsung tampil di beranda.",
      "Admin dapat menyembunyikan ulasan tidak pantas atau menghapus permanen berkas foto dan rekaman ulasan spam."
    ],
    dbMatrix: [
      {
        step: "Kirim Ulasan Customer",
        tables: "testimonials",
        readCols: "-",
        writeColsYes: "name, handle, order_tag, content, rating, review_image, source='website', is_active=true, is_pinned=false",
        writeColsNo: "Tidak ada penulisan data",
        errorMessage: "'Nama lengkap wajib diisi' atau 'Format foto harus JPG/PNG maks 5MB.'"
      },
      {
        step: "Pin Ulasan",
        tables: "testimonials",
        readCols: "id, is_pinned",
        writeColsYes: "is_pinned = true, is_active = true",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      },
      {
        step: "Hapus Ulasan",
        tables: "testimonials",
        readCols: "id, review_image",
        writeColsYes: "Baris dihapus (DELETE), berkas di uploads/reviews/ di-unlink",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C7-alur-reservasi-formbuilder",
    tab: "C",
    number: "C7",
    title: "Flowchart Reservasi Meja Dinamis & Form Builder",
    subtitle: "Kolom Kustom Dinamis, Validasi Multi-Tipe (Foto <=2MB, Tel, Angka), Draf WhatsApp Terformat",
    description: "Menggambarkan alur perancangan formulir reservasi meja menggunakan Form Builder oleh admin, perenderan formulir dinamis di antarmuka publik, validasi multi-tipe field, penyimpanan struktur form_data JSON di database, dan penerusan pesan terstruktur ke WhatsApp resmi kafe.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S6([Mulai]):::startEnd
    READ_FIELDS["Sistem Membaca Kolom Aktif dari reservation_fields"]:::process
    IN6[/"Customer Mengisi Formulir & Unggah Foto (Jika Diminta)"/]:::io
    C_VAL6{"Validasi Isian & Batasan Berkas (Foto <= 2MB)?"}:::decision
    ERR6[/"Tolak: Tampilkan Pesan Kesalahan Validasi Field"/]:::io
    SAVE_RES["Insert ke Tabel reservations (status: 'pending', form_data JSON)"]:::process
    BUILD_WA["Sistem Memformat Teks Pesan WhatsApp Berdasarkan form_data"]:::process
    READ_WA["Ambil Nomor WA Reservasi dari settings"]:::process
    REDIR_WA[/"Alihkan Browser Customer ke https://wa.me/{nomor}?text=..."/]:::io
    E6([Selesai]):::startEnd

    S6 --> READ_FIELDS --> IN6 --> C_VAL6
    C_VAL6 -- Gagal --> ERR6 --> E6
    C_VAL6 -- Lolos --> SAVE_RES --> BUILD_WA --> READ_WA --> REDIR_WA --> E6`,
    steps: [
      "Admin merancang struktur form di /admin/reservasi (menambah field teks, nomor telepon, tanggal, jam, angka, foto, atau checkbox).",
      "Customer membuka halaman / modal reservasi di website; form dirender secara dinamis berdasarkan kolom aktif di reservation_fields.",
      "Customer mengisi isian data dan melampirkan berkas foto bukti jika kolom tipe foto diwajibkan.",
      "Server memvalidasi isian secara dinamis (ukuran foto maks 2MB, format tanggal/jam, panjang nomor telepon minimal 8 digit).",
      "Data reservasi disimpan ke tabel reservations dengan status 'pending' dan seluruh isian kustom dikemas sebagai JSON di form_data.",
      "Sistem menyusun draf pesan WhatsApp berformat rapi dan mengalihkan peramban pelanggan ke URL wa.me resmi kafe."
    ],
    dbMatrix: [
      {
        step: "Simpan Kolom Builder",
        tables: "reservation_fields",
        readCols: "field_key",
        writeColsYes: "label, field_key, type, placeholder, options, is_required, sort_order, is_active",
        writeColsNo: "Tidak ada",
        errorMessage: "'Label dan kunci kolom wajib diisi.'"
      },
      {
        step: "Kirim Reservasi Pelanggan",
        tables: "reservations, reservation_fields, settings",
        readCols: "reservation_fields.*, settings.value (nomor WA)",
        writeColsYes: "name, phone, date, time, guests, notes, form_data (JSON), status='pending'",
        writeColsNo: "Tidak ada penulisan data DB jika validasi field gagal",
        errorMessage: "'{Field} wajib diisi' atau 'Ukuran foto maksimal 2 MB' (HTTP 422)"
      },
      {
        step: "Ubah Status Reservasi",
        tables: "reservations",
        readCols: "id, status",
        writeColsYes: "status diubah menjadi 'confirmed', 'completed', atau 'cancelled'",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C8-alur-transaksi-mode",
    tab: "C",
    number: "C8",
    title: "Flowchart Pengaturan 3 Mode Transaksi & Aturan Saling Mengunci",
    subtitle: "Interlocking Server Rules: Tracking ON -> Chat OFF, Normalisasi Konflik, Mode Semua OFF Sembunyikan Keranjang",
    description: "Menggambarkan logika saling mengunci (mutual exclusion / interlocking) antar-mode transaksi: Mode 3 (Pelacakan Zendo) dan Mode 2 (Chat Zendo) tidak dapat aktif bersamaan. Jika keduanya diaktifkan, server menormalisasi dengan memprioritaskan Pelacakan. Jika semua mode OFF, tombol keranjang disembunyikan.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S_MODE([Mulai: Admin Ubah Mode Transaksi]):::startEnd
    IN_REQ[/"Request: mode_dine_in, mode_zendo_chat, mode_zendo_tracking"/]:::io
    C_TRACK{"Apakah mode_zendo_tracking diaktifkan?"}:::decision
    SET_CHAT_OFF["Interlocking: Set mode_zendo_chat = false (Nonaktifkan Chat)"]:::process
    C_CHAT{"Apakah mode_zendo_chat diaktifkan?"}:::decision
    SET_TRACK_OFF["Interlocking: Set mode_zendo_tracking = false (Nonaktifkan Tracking)"]:::process
    C_ALL_OFF{"Apakah Semua Mode Bernilai False?"}:::decision
    SET_ALL_OFF["Set is_any_mode_active = false: Sembunyikan Tombol Keranjang di Web"]:::process
    SET_SOME_ON["Set is_any_mode_active = true: Tampilkan Keranjang & Metode Aktif"]:::process
    SAVE_SET["Simpan Pengaturan ke Tabel settings (key: order_mode_settings)"]:::process
    OUT_SUCC[/"Tampilkan Pesan Sukses ke Admin"/]:::io
    E_MODE([Selesai]):::startEnd

    S_MODE --> IN_REQ --> C_TRACK
    C_TRACK -- Ya --> SET_CHAT_OFF --> C_ALL_OFF
    C_TRACK -- Tidak --> C_CHAT
    C_CHAT -- Ya --> SET_TRACK_OFF --> C_ALL_OFF
    C_CHAT -- Tidak --> C_ALL_OFF
    C_ALL_OFF -- Ya --> SET_ALL_OFF --> SAVE_SET
    C_ALL_OFF -- Tidak --> SET_SOME_ON --> SAVE_SET
    SAVE_SET --> OUT_SUCC --> E_MODE`,
    steps: [
      "Admin membuka menu Pengaturan Transaksi di /admin/pesanan.",
      "Admin mengatur saklar (toggle) untuk: Mode 1 Dine In, Mode 2 Chat WA, dan Mode 3 Pelacakan GPS.",
      "Server memeriksa aturan interlocking: jika Pelacakan GPS diaktifkan, maka Chat WA otomatis dimatikan.",
      "Jika Chat WA diaktifkan, maka Pelacakan GPS otomatis dimatikan.",
      "Jika seluruh mode dinonaktifkan (semua OFF), server menyetel is_any_mode_active = false: tombol keranjang belanja di navbar dan floating cart otomatis disembunyikan di web publik.",
      "Katalog menu beralih fungsi menjadi katalog informasi saja; permintaan POST checkout yang memaksa masuk ditolak HTTP 422."
    ],
    dbMatrix: [
      {
        step: "Update Pengaturan Mode",
        tables: "settings",
        readCols: "key, value",
        writeColsYes: "key='order_mode_settings', value=JSON(mode_dine_in, mode_zendo_chat, mode_zendo_tracking, is_any_mode_active)",
        writeColsNo: "Tidak ada",
        errorMessage: "'Format pengaturan mode transaksi tidak valid.'"
      },
      {
        step: "Normalisasi Saling Mengunci",
        tables: "settings",
        readCols: "order_mode_settings",
        writeColsYes: "Koreksi otomatis nilai boolean agar tidak terjadi bentrok antar-mode pengantaran",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C9-alur-checkout-dinein",
    tab: "C",
    number: "C9",
    title: "Flowchart Checkout Makan di Kafe (Dine In) & Kasir POS",
    subtitle: "Hitung Harga Server, Potong Stok Menu, Terbitkan Slip QR NCM, Scan Kasir POS, Pelunasan & Struk Thermal",
    description: "Menggambarkan alur checkout pelanggan untuk makan di kafe: harga resmi dihitung dari basis data (anti manipulasi client), stok fisik menu langsung dipotong otomatis, penerbitan slip digital ber-QR code NCM, pemindaian oleh kasir, penerimaan bayar (Tunai/QRIS), dan cetak struk thermal.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S_DI([Mulai: Pelanggan Pilih Dine In]):::startEnd
    IN_DI[/"Input: Item Keranjang, Nama, Pilihan Meja / Takeaway, Metode Bayar (Tunai/QRIS)"/]:::io
    VAL_DI{"Validasi: Meja/Takeaway dipilih & Metode Bayar Valid?"}:::decision
    ERR_DI[/"Tolak 422: Validasi Meja / Pembayaran Gagal"/]:::io
    CALC_DI["Ambil Harga Menu dari Database & Hitung Subtotal Resmi"]:::process
    STOCK_DI["Potong Stok Fisik Menu (KasirController::deductStockForOrderItems)"]:::process
    GEN_CODE["Generate Kode Pesanan Format NCM-Ymd-xxx"]:::process
    SAVE_DI["Insert orders (status: 'pending', payment_status: 'unpaid') & order_items"]:::process
    OUT_SLIP[/"Tampilkan Halaman Slip Digital (/pesan/slip/{code}) Berisi Kode QR"/]:::io
    KSR_SCAN[/"Kasir Scan QR Slip atau Buka /qr/pesanan/{code}"/]:::io
    KSR_CHECK{"Verifikasi Fisik & Terima Uang Tunai / QRIS?"}:::decision
    KSR_PAID["Update orders: payment_status = 'paid', status = 'completed', completed_at = NOW()"]:::process
    KSR_PRINT[/"Kasir Cetak Struk Thermal 58mm/80mm"/]:::io
    E_DI([Selesai]):::startEnd

    S_DI --> IN_DI --> VAL_DI
    VAL_DI -- Tidak --> ERR_DI --> E_DI
    VAL_DI -- Ya --> CALC_DI --> STOCK_DI --> GEN_CODE --> SAVE_DI --> OUT_SLIP --> KSR_SCAN --> KSR_CHECK
    KSR_CHECK -- Lolos --> KSR_PAID --> KSR_PRINT --> E_DI
    KSR_CHECK -- Batal --> E_DI`,
    steps: [
      "Pelanggan memilih item menu di keranjang belanja, memilih opsi 'Makan di Kafe', memasukkan nama, nomor meja / takeaway, dan opsi bayar (Tunai/QRIS).",
      "Server memvalidasi keranjang dan mengambil harga resmi dari basis data menus (mencegah manipulasi harga dari client-side).",
      "Server langsung memotong stok fisik menu (deductStockForOrderItems) dan membuat baris pesanan berstatus pending dan unpaid.",
      "Server menghasilkan kode pesanan unik NCM-Ymd-xxx dan menampilkan slip digital ber-QR code.",
      "Pelanggan menunjukkan slip QR ke kasir; kasir memindai kode QR (/qr/pesanan/{code}) menggunakan kamera HP atau scanner barcode.",
      "Kasir menerima uang tunai / memverifikasi transfer QRIS, menekan tombol 'Konfirmasi Lunas' (status berubah menjadi paid & completed), dan mencetak struk thermal."
    ],
    dbMatrix: [
      {
        step: "Checkout Dine In",
        tables: "orders, order_items, menus, tables",
        readCols: "menus.price, menus.stock, menus.is_available, tables.id",
        writeColsYes: "orders (code, order_type='dine_in', status='pending', payment_status='unpaid'), order_items (quantity, price, subtotal), menus.stock berkurang",
        writeColsNo: "Tidak ada penulisan data DB jika keranjang kosong / stok habis",
        errorMessage: "'Menu sedang tidak tersedia saat ini' atau 'Nomor meja tidak valid' (HTTP 422)"
      },
      {
        step: "Pelunasan Kasir",
        tables: "orders",
        readCols: "orders.id, orders.payment_status",
        writeColsYes: "orders.payment_status = 'paid', orders.status = 'completed', completed_at = NOW()",
        writeColsNo: "Tidak ada",
        errorMessage: "'Pesanan tidak ditemukan' jika QR code tidak valid"
      }
    ]
  },
  {
    id: "C10-alur-checkout-zendochat",
    tab: "C",
    number: "C10",
    title: "Flowchart Checkout Antar via Chat WhatsApp Zendo",
    subtitle: "Validasi Alamat Pengantaran, Ongkir Flat Tier Dasar (Rp 10.000), Template Pesan Dinamis & Redirect wa.me",
    description: "Menggambarkan alur checkout pengantaran melalui pesan WhatsApp kurir Zendo tanpa GPS: validasi alamat tujuan, penetapan tarif ongkir flat dasar (Rp 10.000), penyimpanan rekaman pesanan berstatus processing, perakitan template pesan WhatsApp otomatis, dan pengalihan ke wa.me.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S_ZC([Mulai: Pelanggan Pilih Antar via Chat]):::startEnd
    IN_ZC[/"Input: Item Keranjang, Nama, No. HP, Alamat Pengantaran, Catatan"/]:::io
    VAL_ZC{"Validasi: Nama, No. HP & Alamat Terisi Lengkap?"}:::decision
    ERR_ZC[/"Tolak 422: Alamat pengantaran wajib diisi"/]:::io
    CALC_ZC["Hitung Subtotal Menu & Tetapkan Ongkir Flat Dasar (Rp 10.000)"]:::process
    SAVE_ZC["Insert ke orders (order_type: 'zendo_chat', status: 'processing') & order_items"]:::process
    READ_TPL["Baca Template Chat WA Zendo & Nomor WA Kurir dari settings"]:::process
    BUILD_ZC["Sistem Merakit Format Pesan WA: Rincian Menu, Total, Alamat & Catatan"]:::process
    REDIR_ZC[/"Redirect Browser Pelanggan ke wa.me/{nomor_kurir}?text={pesan_terformat}"/]:::io
    E_ZC([Selesai: Pesanan Masuk Chat Kurir]):::startEnd

    S_ZC --> IN_ZC --> VAL_ZC
    VAL_ZC -- Tidak --> ERR_ZC --> E_ZC
    VAL_ZC -- Ya --> CALC_ZC --> SAVE_ZC --> READ_TPL --> BUILD_ZC --> REDIR_ZC --> E_ZC`,
    steps: [
      "Pelanggan memilih metode 'Antar via Chat Zendo' di keranjang checkout.",
      "Pelanggan mengisi nama lengkap, nomor telepon aktif, dan mengetikkan alamat pengantaran secara detail.",
      "Server memvalidasi kelengkapan data (alamat pengantaran wajib terisi).",
      "Server menghitung subtotal resmi dan menetapkan ongkir flat tier dasar sebesar Rp 10.000 (tidak ada pelacakan GPS).",
      "Server menyimpan pesanan ke tabel orders dengan status 'processing' dan order_type = 'zendo_chat'.",
      "Server membaca template format pesan dari tabel settings dan mengalihkan peramban pelanggan ke WhatsApp kurir Zendo."
    ],
    dbMatrix: [
      {
        step: "Checkout Antar Chat",
        tables: "orders, order_items, settings, menus",
        readCols: "menus.price, settings.value (nomor WA kurir & template pesan)",
        writeColsYes: "orders (code, customer_name, customer_phone, delivery_address, delivery_fee=10000, status='processing', order_type='zendo_chat'), order_items, menus.stock berkurang",
        writeColsNo: "Tidak ada penulisan data DB jika alamat kosong",
        errorMessage: "'Alamat pengiriman wajib diisi untuk pengiriman Kurir Zendo' (HTTP 422)"
      }
    ]
  },
  {
    id: "C11-alur-checkout-zendotrack",
    tab: "C",
    number: "C11",
    title: "Flowchart Checkout Antar Pelacakan GPS Zendo & Live Tracking",
    subtitle: "Titik Pin Peta, Jarak Haversine, Tarif Bertingkat, Auto-Dispatch Kurir, Anti-Fake GPS (Speed/Accuracy), Live Map",
    description: "Menggambarkan alur checkout pengantaran dengan pelacakan langsung: pemilihan koordinat pin pada peta, perhitungan jarak Haversine, penetapan ongkir tarif bertingkat, penugasan kurir otomatis (auto-dispatch), penerbitan pasangan token lacak, streaming GPS berkala, validasi anti-fake GPS, dan pemantauan live tracking.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S_ZT([Mulai: Checkout Antar GPS]):::startEnd
    IN_ZT[/"Customer Tentukan Titik Pin Koordinat di Peta & Patokan Alamat"/]:::io
    VAL_ZT{"Koordinat Lat/Lng Valid & Dalam Jangkauan?"}:::decision
    ERR_ZT[/"Tolak 422: Alamat pengiriman wajib dipilih pada peta"/]:::io
    HAVER["Hitung Jarak Garis Lurus dari Kafe dengan Rumus Haversine"]:::process
    TIER_FEE["Hitung Tarif Ongkir Bertingkat (0-3km: 10rb, dst., >7km: +2.500/km)"]:::process
    DISPATCH["Algoritma Auto-Dispatch: Pilih Kurir Idle -> Kurir Almost Done"]:::process
    SAVE_ZT["Insert orders (order_type: 'zendo_delivery', status: 'processing')"]:::process
    SAVE_DELIV["Insert zendo_deliveries: tracking_token (publik) & courier_token (kurir)"]:::process
    OPEN_TRACK[/"Buka Halaman Live Map (/zendo/lacak/{tracking_token}) untuk Pelanggan"/]:::io
    KRR_STREAM[/"Kurir Buka /zendo/kurir/{courier_token} & Streaming Koordinat GPS"/]:::io
    ANTI_FAKE{"Validasi Anti-Fake GPS: Speed max 45 m/s, Akurasi max 150m, Radius max 40km?"}:::decision
    LOCK_GPS["Anomali Berulang 3x -> Aktifkan GPS Lock 5 Menit"]:::process
    UPDATE_POS["Simpan Posisi GPS Terkini & Jejak Rute ke route_history JSON"]:::process
    KRR_STATUS["Kurir Update Tahapan: Picked Up -> On The Way -> Delivered"]:::process
    E_ZT([Selesai: Pesanan Selesai Diantar]):::startEnd

    S_ZT --> IN_ZT --> VAL_ZT
    VAL_ZT -- Tidak --> ERR_ZT --> E_ZT
    VAL_ZT -- Ya --> HAVER --> TIER_FEE --> DISPATCH --> SAVE_ZT --> SAVE_DELIV --> OPEN_TRACK
    OPEN_TRACK --> KRR_STREAM --> ANTI_FAKE
    ANTI_FAKE -- Anomali --> LOCK_GPS --> KRR_STREAM
    ANTI_FAKE -- Valid --> UPDATE_POS --> KRR_STATUS --> E_ZT`,
    steps: [
      "Pelanggan menentukan titik pengantaran pada peta Leaflet.js (mengirim customer_lat dan customer_lng) serta alamat detail patokan.",
      "Server menghitung jarak garis lurus dari kafe menggunakan rumus Haversine.",
      "Server menghitung biaya ongkos kirim bertingkat (0-3 km = 10rb, 3-4 km = 12rb, ..., >7 km = 18rb + 2.500/km).",
      "Sistem menjalankan algoritma auto-dispatch untuk memilih kurir Zendo yang sedang idle atau paling awal menerima tugas.",
      "Pesanan disimpan di orders dan rekaman pengantaran dibuat di zendo_deliveries lengkap dengan tracking_token (publik) dan courier_token (rahasia).",
      "Kurir membuka tautan kurir ber-PIN dan menyiarkan koordinat GPS; server menerapkan validasi anti-fake GPS (kecepatan <= 45 m/s, akurasi <= 150m, radius <= 40km).",
      "Pelanggan dan admin memantau pergerakan kurir secara real-time pada peta pelacakan."
    ],
    dbMatrix: [
      {
        step: "Checkout Antar GPS",
        tables: "orders, order_items, zendo_deliveries, settings",
        readCols: "settings.value (koordinat kafe, tabel tarif, daftar kurir)",
        writeColsYes: "orders (transaksi), zendo_deliveries (order_id, courier_index, tracking_token, courier_token, destination_lat, destination_lng, status='assigned')",
        writeColsNo: "Tidak ada penulisan data DB jika koordinat peta tidak dipilih",
        errorMessage: "'Alamat pengiriman wajib dipilih pada peta' (HTTP 422)"
      },
      {
        step: "Streaming GPS Kurir",
        tables: "zendo_deliveries",
        readCols: "courier_token, last_lat, last_lng, last_update",
        writeColsYes: "current_lat, current_lng, route_history (JSON append), last_seen_at",
        writeColsNo: "Koordinat dibuang jika terdeteksi anomali anti-fake GPS",
        errorMessage: "'Peringatan anomali sinyal GPS / GPS Lock aktif 5 menit'"
      },
      {
        step: "Update Tahapan Antar",
        tables: "zendo_deliveries, orders",
        readCols: "zendo_deliveries.status",
        writeColsYes: "zendo_deliveries.status='delivered', delivered_at=NOW(), orders.status='completed'",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C12-alur-status-pesanan",
    tab: "C",
    number: "C12",
    title: "Flowchart Perubahan Siklus Status Pesanan",
    subtitle: "Pending -> Processing -> Ready -> Completed, atau Cancelled (Kembalikan Stok Menu)",
    description: "Menggambarkan alur hidup status pesanan dari saat pertama kali dibuat hingga selesai dinikmati pelanggan, serta pemulihan stok menu secara otomatis ke jumlah semula jika pesanan dibatalkan oleh kasir atau admin.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S_ST([Mulai: Pesanan Dibuat]):::startEnd
    ST_PEND["Status: 'pending' (Menunggu Konfirmasi / Belum Dibayar)"]:::process
    DEC_ADM{"Aksi Admin / Kasir?"}:::decision
    ST_PROC["Status: 'processing' (Sedang Diracik Barista / Dapur)"]:::process
    ST_READY["Status: 'ready' (Sajian Siap Diambil / Siap Diantar)"]:::process
    ST_COMP["Status: 'completed' (Selesai Dinikmati & Lunas)"]:::process
    ST_CANC["Status: 'cancelled' (Dibatalkan Admin / Kasir)"]:::process
    RESTORE_STK["Kembalikan Stok Menu ke Jumlah Semula (Stok Ditambah Kembali)"]:::process
    E_ST([Selesai]):::startEnd

    S_ST --> ST_PEND --> DEC_ADM
    DEC_ADM -- "Konfirmasi & Proses" --> ST_PROC --> DEC_ADM
    DEC_ADM -- "Sajian Siap" --> ST_READY --> DEC_ADM
    DEC_ADM -- "Lunas / Diterima" --> ST_COMP --> E_ST
    DEC_ADM -- "Batalkan Pesanan" --> ST_CANC --> RESTORE_STK --> E_ST`,
    steps: [
      "Pesanan baru masuk dengan status awal 'pending' (Mode Dine In) atau 'processing' (Mode Antar).",
      "Staf barista / dapur mengubah status menjadi 'processing' saat meracik pesanan makanan/minuman.",
      "Setelah hidangan siap, status diubah menjadi 'ready' (siap diambil di kasir atau siap diantar kurir).",
      "Saat hidangan diterima dan lunas dibayar, status diubah menjadi 'completed'.",
      "Jika pesanan dibatalkan ('cancelled') karena alasan tertentu, server secara otomatis mengembalikan stok menu ke jumlah semula."
    ],
    dbMatrix: [
      {
        step: "Perubahan Status Normal",
        tables: "orders",
        readCols: "id, status",
        writeColsYes: "status = ('processing' | 'ready' | 'completed')",
        writeColsNo: "Tidak ada",
        errorMessage: "'Status pesanan tidak valid'"
      },
      {
        step: "Pembatalan Pesanan",
        tables: "orders, order_items, menus",
        readCols: "orders.id, order_items.menu_id, order_items.quantity, menus.stock",
        writeColsYes: "orders.status = 'cancelled', menus.stock bertambah kembali sesuai quantity pesanan",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C13-alur-validasi-penolakan",
    tab: "C",
    number: "C13",
    title: "Flowchart Validasi Penolakan Pesanan Tanpa Penulisan Basis Data",
    subtitle: "Pengecekan Berlapis: Mode OFF, Keranjang Kosong, Qty < 1, Menu Tidak Ditemukan, Stok Habis, Alamat Kosong",
    description: "Menggambarkan alur validasi gerbang ketat pada kontroler saat menerima permintaan checkout: jika salah satu aturan gagal, transaksi langsung ditolak dengan status HTTP 422 dan pesan bersahabat, tanpa menulis baris data apa pun ke basis data.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S_VAL([Mulai: Request Checkout Masuk]):::startEnd
    C1{"Semua Mode Transaksi OFF?"}:::decision
    E1[/"Tolak HTTP 422: Layanan pemesanan sedang dinonaktifkan sementara"/]:::io
    C2{"Keranjang Kosong atau Bukan Array?"}:::decision
    E2[/"Tolak HTTP 422: Keranjang pesanan masih kosong"/]:::io
    C3{"Qty Bukan Integer atau Kurang Dari 1?"}:::decision
    E3[/"Tolak HTTP 422: Jumlah pesanan qty minimal 1"/]:::io
    C4{"Menu ID Tidak Ditemukan di DB?"}:::decision
    E4[/"Tolak HTTP 422: Menu tidak ditemukan dalam daftar kami"/]:::io
    C5{"Menu Habis atau is_available False?"}:::decision
    E5[/"Tolak HTTP 422: Menu sedang tidak tersedia saat ini"/]:::io
    C6{"Mode Zendo Tapi Alamat Kosong?"}:::decision
    E6[/"Tolak HTTP 422: Alamat pengiriman wajib diisi"/]:::io
    PASS_OK["Validasi Lolos Sepenuhnya: Lanjut Proses Pembuatan Pesanan"]:::process
    STOP([Batalkan Request: Nol Penulisan DB]):::startEnd
    E_VAL([Lanjut ke Penulisan DB]):::startEnd

    S_VAL --> C1
    C1 -- Ya --> E1 --> STOP
    C1 -- Tidak --> C2
    C2 -- Ya --> E2 --> STOP
    C2 -- Tidak --> C3
    C3 -- Ya --> E3 --> STOP
    C3 -- Tidak --> C4
    C4 -- Ya --> E4 --> STOP
    C4 -- Tidak --> C5
    C5 -- Ya --> E5 --> STOP
    C5 -- Tidak --> C6
    C6 -- Ya --> E6 --> STOP
    C6 -- Tidak --> PASS_OK --> E_VAL`,
    steps: [
      "Permintaan POST checkout tiba di server melalui route /pesan.",
      "Filter 1: Cek status mode transaksi kafe. Jika seluruh mode dinonaktifkan oleh admin, request ditolak HTTP 422.",
      "Filter 2: Cek struktur item keranjang. Jika kosong atau bukan array valid, tolak: 'Keranjang pesanan masih kosong.'",
      "Filter 3: Cek kuantitas setiap item. Jika bukan bilangan bulat atau < 1, tolak: 'Jumlah pesanan minimal 1.'",
      "Filter 4: Cek keberadaan ID menu di basis data. Jika tidak ditemukan, tolak: 'Menu tidak ditemukan.'",
      "Filter 5: Cek ketersediaan fisik (is_available == true dan stock > 0). Jika habis, tolak: 'Menu sedang tidak tersedia saat ini.'",
      "Filter 6: Cek alamat pengantaran pada metode Zendo. Jika kosong, tolak: 'Alamat pengiriman wajib diisi.'",
      "Jika seluruh tahapan validasi lolos, server baru mulai menulis rekaman transaksi dan memotong stok."
    ],
    dbMatrix: [
      {
        step: "Validasi Checkout Masuk",
        tables: "settings, menus",
        readCols: "settings.value, menus.id, menus.stock, menus.is_available",
        writeColsYes: "Lanjut ke penulisan data tabel orders",
        writeColsNo: "Nol penulisan (0 baris ditulis ke orders/order_items)",
        errorMessage: "Respon JSON HTTP 422 dengan pesan spesifik kegagalan"
      }
    ]
  },
  {
    id: "C14-alur-profil-pengaturan",
    tab: "C",
    number: "C14",
    title: "Flowchart Profil Administrator, Password & Pusat Notifikasi",
    subtitle: "Validasi Hash::check Password Lama, Update Profil, Agregasi Notifikasi Otomatis (Banner, Order, Reservasi, Review)",
    description: "Menggambarkan alur pembaruan profil administrator, pergantian kata sandi dengan verifikasi hash lama via Hash::check, serta sistem agregasi notifikasi otomatis yang mengumpulkan kejadian kafe dan menyimpan status terbaca di tabel settings.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S8([Mulai]):::startEnd
    IN8[/"Admin Input Password Lama, Password Baru & Konfirmasi"/]:::io
    C_CONF{"Konfirmasi Password Cocok & Minimal 8 Karakter?"}:::decision
    ERR_CONF[/"Tolak: Konfirmasi password baru tidak cocok atau kurang dari 8 karakter"/]:::io
    C_OLD{"Verifikasi Hash Password Lama via Hash::check?"}:::decision
    ERR_OLD[/"Tolak: Password saat ini yang Anda masukkan salah"/]:::io
    HASH_PW["Hash Password Baru (bcrypt via Hash::make)"]:::process
    SAVE_PW["Update Kolom users.password di Basis Data"]:::process
    SUCC_PW[/"Pesan Sukses: Password akun admin berhasil diubah"/]:::io
    E8([Selesai]):::startEnd

    S8 --> IN8 --> C_CONF
    C_CONF -- Gagal --> ERR_CONF --> E8
    C_CONF -- Lolos --> C_OLD
    C_OLD -- Gagal --> ERR_OLD --> E8
    C_OLD -- Lolos --> HASH_PW --> SAVE_PW --> SUCC_PW --> E8`,
    steps: [
      "Admin membuka menu Profil di /admin/profile, memilih tab Keamanan untuk mengganti kata sandi.",
      "Admin menginputkan password saat ini, password baru, dan konfirmasi password baru.",
      "Server memvalidasi panjang password baru minimal 8 karakter dan konfirmasi cocok.",
      "Server memverifikasi password lama terhadap hash di database menggunakan Hash::check.",
      "Jika benar, password baru di-hash dengan bcrypt dan disimpan ke tabel users.",
      "Di pusat notifikasi, sistem mengagregasi banner hampir expired, pesanan pending, reservasi pending, dan review unpinned, lalu mencatat kunci terbaca ke tabel settings."
    ],
    dbMatrix: [
      {
        step: "Update Profil",
        tables: "users",
        readCols: "id, email",
        writeColsYes: "name, email, avatar (nama berkas baru)",
        writeColsNo: "Tidak ada penulisan",
        errorMessage: "'Alamat email ini sudah digunakan oleh akun lain.'"
      },
      {
        step: "Ganti Password",
        tables: "users",
        readCols: "id, password",
        writeColsYes: "password (hash baru bcrypt)",
        writeColsNo: "Tidak ada penulisan",
        errorMessage: "'Password saat ini yang Anda masukkan salah.'"
      },
      {
        step: "Tandai Baca Notifikasi",
        tables: "settings",
        readCols: "key='admin_read_notification_keys'",
        writeColsYes: "value diperbarui dengan JSON array kunci notifikasi terbaca",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      }
    ]
  },
  {
    id: "C15-alur-laporan-pdf-dashboard",
    tab: "C",
    number: "C15",
    title: "Flowchart Ekspor Laporan Rekapitulasi Transaksi PDF 1.4 Native",
    subtitle: "Generator PDF 1.4 Murni Tanpa Pustaka Eksternal, Filter Rentang Waktu, Dashboard Chart Batang Interaktif",
    description: "Menggambarkan alur pembuatan dan pengunduhan laporan rekapitulasi transaksi menggunakan TransactionReportPdfGenerator (generator biner PDF 1.4 native buatan sendiri tanpa pustaka luar), pratinjau cetak web print view, ekspor CSV, serta visualisasi grafik penjualan.",
    code: `flowchart TD
    classDef startEnd fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef process fill:#E7DED1,stroke:#2A1D17,stroke-width:1px,color:#140E0A;
    classDef decision fill:#C2A378,stroke:#140E0A,stroke-width:1px,color:#FFFFFF;
    classDef io fill:#FAF8F5,stroke:#2A1D17,stroke-width:1px,color:#140E0A;

    S9([Mulai]):::startEnd
    IN9[/"Admin Memilih Periode (Harian/Mingguan/Bulanan) & Format Laporan"/]:::io
    READ_ORD["Query Tabel orders & order_items Sesuai Rentang Tanggal"]:::process
    CALC_MET["Hitung Metrik: Total Omzet Lunas, Gross Sales, Jumlah Selesai & Batal"]:::process
    DEC_FMT{"Pilihan Format Rekap?"}:::decision

    %% PDF
    GEN_PDF["TransactionReportPdfGenerator: Kompilasi Stream Biner PDF 1.4 Native"]:::process
    OUT_PDF[/"Unduh Berkas rekap-transaksi-{periode}.pdf Secara Otomatis"/]:::io

    %% Print Web
    GEN_PRINT["Render View admin.orders.report-print (CSS @media print)"]:::process
    OUT_PRINT[/"Tampilkan Halaman Cetak Pratinjau di Tab Baru Peramban"/]:::io

    %% CSV
    GEN_CSV["Generate Baris CSV dengan Separator Koma & Header Kolom"]:::process
    OUT_CSV[/"Unduh Berkas rekap-transaksi-{periode}.csv"/]:::io

    E9([Selesai]):::startEnd

    S9 --> IN9 --> READ_ORD --> CALC_MET --> DEC_FMT
    DEC_FMT -- "format == 'pdf'" --> GEN_PDF --> OUT_PDF --> E9
    DEC_FMT -- "format == 'print'" --> GEN_PRINT --> OUT_PRINT --> E9
    DEC_FMT -- "format == 'csv'" --> GEN_CSV --> OUT_CSV --> E9`,
    steps: [
      "Admin membuka menu Pesanan di /admin/pesanan dan mengklik tombol pintas 1-klik: 'PDF Hari Ini', 'PDF Minggu Ini', atau 'PDF Bulan Ini'.",
      "Server memfilter data transaksi orders dan order_items sesuai rentang periode yang diminta.",
      "Server menghitung ringkasan: omzet lunas, penjualan kotor, pesanan selesai, dan pesanan dibatalkan.",
      "Jika format = pdf: generator PDF native (TransactionReportPdfGenerator) mengompilasi stream biner PDF 1.4 murni tanpa library eksternal.",
      "Browser admin langsung mengunduh berkas rekap-transaksi-{periode}.pdf.",
      "Jika format = print atau csv: server merender halaman cetak pratinjau browser atau mengalirkan berkas CSV."
    ],
    dbMatrix: [
      {
        step: "Kompilasi Dashboard",
        tables: "orders, order_items, menus, reservations, settings",
        readCols: "orders.total, orders.status, orders.payment_status, orders.created_at, order_items.quantity, menus.is_available, reservations.status, settings.value",
        writeColsYes: "Tidak ada (operasi baca & agregasi data)",
        writeColsNo: "Tidak ada",
        errorMessage: "Tidak ada"
      },
      {
        step: "Toggle Saklar Grafik",
        tables: "settings",
        readCols: "key='hide_dashboard_chart'",
        writeColsYes: "key='hide_dashboard_chart', value=('0' | '1')",
        writeColsNo: "Tidak ada",
        errorMessage: "'Pengaturan grafik berhasil diperbarui.'"
      },
      {
        step: "Unduh PDF Rekap",
        tables: "orders, order_items, settings",
        readCols: "Seluruh kolom transaksi pada rentang periode yang diminta",
        writeColsYes: "Tidak menulis DB; stream biner PDF 1.4 dikirim ke client",
        writeColsNo: "Tidak ada",
        errorMessage: "'Tidak ada data transaksi pada periode yang dipilih.'"
      }
    ]
  }
];

if (typeof globalThis !== 'undefined') {
  globalThis.DIAGRAMS = DIAGRAMS;
}
if (typeof window !== 'undefined') {
  window.DIAGRAMS = DIAGRAMS;
}
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { DIAGRAMS };
}

