# DIAGRAM ALIR DATA (DATA FLOW DIAGRAM - DFD)
**Sistem:** Sistem Informasi & Transaksi Pemesanan Nucomu Cafe & Roastery  
**Notasi Standar:** Gane dan Sarson (*Gane-Sarson Diagramming Standard*)  
**Konsistensi:** Tingkat Konteks, DFD Level 0, dan DFD Level 1 Seimbang (*Balanced DFD*)

---

## 1. Notasi Gane-Sarson dan Legenda Simbol

Dokumen ini menggunakan notasi **Gane dan Sarson** secara konsisten di seluruh diagram alir data.

```mermaid
flowchart LR
    classDef entStyle fill:#140E0A,stroke:#C2A378,stroke-width:2px,color:#FFFFFF;
    classDef procStyle fill:#E7DED1,stroke:#2A1D17,stroke-width:2px,color:#140E0A;
    classDef storeStyle fill:#FAF8F5,stroke:#2A1D17,stroke-width:2px,stroke-dasharray: 5 5,color:#140E0A;

    ENT["Entitas Luar<br>(External Entity)"]:::entStyle
    PROC["No. | Nama Proses<br>(Proses Transformasi)"]:::procStyle
    STORE[("D# : nama_tabel<br>(Penyimpanan Data)")]:::storeStyle

    ENT -->|"Aliran Data (Kata Benda)"| PROC
    PROC -->|"Penyimpanan Data"| STORE
    STORE -->|"Pembacaan Data"| PROC
    PROC -->|"Informasi / Laporan"| ENT
```

### Legenda Komponen:
1. **Entitas Luar (*External Entity*):** Persegi panjang dengan batas tegas warna gelap. Menggambarkan asal sumber data masukan atau tujuan akhir informasi (Admin, Kasir, Customer, Kurir Zendo, WhatsApp API/Web, Google Places API, Layanan QRIS, Perangkat Scanner).
2. **Proses (*Process*):** Persegi panjang dengan sudut tumpul (*rounded rectangle*) yang memuat nomor hierarki proses dan kata kerja aktif. Mengubah data masukan menjadi keluaran.
3. **Penyimpanan Data (*Data Store*):** Simbol penyimpanan data bernomor urut `D1` s/d `D17` yang **menggunakan nama tabel fisik sebenarnya** di basis data MySQL/MariaDB Nucomu Cafe.
4. **Aliran Data (*Data Flow*):** Garis panah berarah yang wajib dinamai dengan frasa kata benda (misalnya: *kredensial login, data pesanan, draf pesan WhatsApp, koordinat GPS*).

### Aturan Ketat DFD yang Dipenuhi:
- **Keseimbangan Aliran (*Balancing*):** Setiap aliran data yang masuk dan keluar dari proses pada tingkat yang lebih tinggi (Diagram Konteks & Level 0) konsisten dipecah secara rinci pada proses turunan Level 1.
- **Tidak Ada Jalur Langsung Ilegal:** Tidak ada aliran data langsung dari Entitas Luar ke Entitas Luar lain, maupun dari Data Store ke Data Store lain tanpa melalui proses pengolahan.
- **Tanpa Lubang Hitam (*Black Hole*) atau Keajaiban (*Miracle*):** Setiap proses memiliki minimal satu aliran masukan (*input*) dan satu aliran keluaran (*output*).
- **Integritas Penulisan Data Store:** Seluruh tabel database memiliki proses yang menulis (*write/create/update*) dan membaca (*read*).

---

## 2. Diagram Konteks (DFD Level 0 Tingkat Sistem)

Diagram Konteks memposisikan sistem secara utuh sebagai satu proses tunggal bernomor `0` dan memperlihatkan seluruh interaksi data dengan entitas luar di sekelilingnya.

#### Gambar 2.1 Diagram Konteks Sistem Informasi Nucomu Cafe
```mermaid
flowchart TB
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
    E_SCN -->|"Hasil pembacaan scan QR kode pesanan NCM-xxx"| SYS
```

---

## 3. DFD Level 0 (Proses-Proses Utama Sistem)

DFD Level 0 memecah proses induk `0` menjadi 9 (sembilan) proses utama bernomor `1.0` hingga `9.0`, terhubung ke 17 tabel data store sebenarnya (`D1` s/d `D17`).

#### Gambar 2.2 DFD Level 0 Sistem Informasi Nucomu Cafe
```mermaid
flowchart TB
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
    P9 -->|"Grafik analitik dashboard & unduh PDF 1.4"| E_ADM
```

---

## 4. DFD Level 1 Sub-Proses Transaksi, Pemesanan & Kasir (Proses 6.0)

Sub-proses 6.0 adalah jantung operasional komersial kafe. Sub-proses ini mengelola 3 mode pemesanan pelanggan, perhitungan harga dan stok di server, penerbitan slip QR, operasional kasir POS, pelunasan, hingga cetak struk thermal.

#### Gambar 2.3 DFD Level 1 Proses 6.0 (Transaksi, Pemesanan, Kasir & Scanner Slip)
```mermaid
flowchart TB
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
    E_ADM -->|"Ubah status transaksi & batalkan pesanan"| P6_6
```

---

## 5. DFD Level 1 Sub-Proses Reservasi & Form Builder (Proses 4.0)

Sub-proses 4.0 mengelola pembangun formulir kustom oleh admin, pengisian formulir oleh pelanggan, validasi multi-tipe (termasuk unggah foto), penyimpanan basis data, dan penerusan format ke WhatsApp.

#### Gambar 2.4 DFD Level 1 Proses 4.0 (Reservasi Meja & Form Builder)
```mermaid
flowchart TB
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
    P4_4 -->|"Update status / hapus rekaman"| D8
```

---

## 6. DFD Level 1 Sub-Proses Ulasan & Testimoni Pelanggan (Proses 5.0)

Sub-proses 5.0 mengatur penerimaan ulasan dari publik website, integrasi ulasan Google Maps, penyaringan kurasi oleh administrator (*pinning system*), dan penayangan ulasan terpilih ke beranda.

#### Gambar 2.5 DFD Level 1 Proses 5.0 (Ulasan & Testimoni Pelanggan)
```mermaid
flowchart TB
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
    P5_4 -->|"Daftar ulasan terpilih (Google + Web Pinned)"| E_CST
```

---

## 7. DFD Level 1 Sub-Proses Autentikasi, Sesi & Otorisasi (Proses 1.0)

Sub-proses 1.0 mengamankan akses sistem untuk 3 peran yang membutuhkan hak khusus (Admin, Kasir, dan Kurir Zendo).

#### Gambar 2.6 DFD Level 1 Proses 1.0 (Autentikasi & Sesi Pengguna)
```mermaid
flowchart TB
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
    P1_4 -->|"Hapus sesi, regenerate CSRF & lupakan cookie"| D1
```

---

## 8. DFD Level 1 Sub-Proses Menu, Kategori & Stok (Proses 3.0)

Sub-proses 3.0 mengatur pengelolaan kategori, menu, varian, dan aturan ketat perlindungan data (pencegahan penghapusan jika ada relasi atau riwayat transaksi).

#### Gambar 2.7 DFD Level 1 Proses 3.0 (Katalog Menu, Kategori & Kontrol Stok)
```mermaid
flowchart TB
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
    P3_4 -->|"Konfirmasi proteksi / penghapusan menu"| E_ADM
```

---

## 9. DFD Level 1 Sub-Proses Logistik & Pelacakan Kurir Zendo (Proses 7.0)

Sub-proses 7.0 menangani siklus pengantaran kurir Zendo secara langsung (*live tracking*), verifikasi keamanan GPS (*anti fake-GPS*), dan pemantauan jarak koordinat.

#### Gambar 2.8 DFD Level 1 Proses 7.0 (Logistik & Pelacakan Kurir Zendo)
```mermaid
flowchart TB
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
    D16 -->|"Pantau armada & reset perangkat kurir"| E_ADM
```
