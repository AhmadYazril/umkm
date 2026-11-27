# TAHAP 2: ALIRAN INFORMASI (DATA FLOW DIAGRAM & USE CASE)
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Standar Notasi:** Gane-Sarson Diagramming Standard & UML Use Case

---

## 1. DFD Level 0 (Diagram Konteks)
Diagram Konteks memposisikan keseluruhan sistem informasi sebagai satu proses tunggal (`0`) dan memetakan batas sistem (*system boundary*) terhadap semua entitas eksternal.

```mermaid
flowchart TB
    classDef ent fill:#1E293B,stroke:#94A3B8,stroke-width:2px,color:#FFFFFF;
    classDef sys fill:#F1F5F9,stroke:#0F172A,stroke-width:3px,color:#0F172A;

    SYS["0<br>SISTEM INFORMASI PROFIL & E-ORDER NUCOMU CAFE"]:::sys

    E_CST["Pelanggan / Pengunjung"]:::ent
    E_KSR["Kasir POS"]:::ent
    E_KRR["Kurir Zendo"]:::ent
    E_ADM["Administrator"]:::ent
    E_WA["WhatsApp Gateway / Web"]:::ent
    E_GMP["Google Places API"]:::ent

    %% Pelanggan
    E_CST -->|"Permintaan katalog, ulasan, data reservasi, keranjang & checkout 3 mode"| SYS
    SYS -->|"Tampilan menu & varian, ulasan terkurasi, slip QR (NCM-xxx), live map tracking"| E_CST

    %% Kasir
    E_KSR -->|"Kredensial kasir, scan kode slip QR, update stok fisik, konfirmasi pelunasan"| SYS
    SYS -->|"Katalog POS, detail pesanan hasil scan, cetak struk termal"| E_KSR

    %% Kurir
    E_KRR -->|"PIN verifikasi kurir, device token, streaming koordinat GPS, update status antar"| SYS
    SYS -->|"Detail alamat antar, rute peta GPS, status device binding, peringatan anti-fake"| E_KRR

    %% Admin
    E_ADM -->|"Kredensial admin, kelola konten/menu/kategori/banner/ulasan/meja/ongkir"| SYS
    SYS -->|"Dasbor analitik omzet, daftar pesanan, notifikasi, ekspor laporan PDF & CSV"| E_ADM

    %% Eksternal WhatsApp & Google
    SYS -->|"Draf pesan pesanan Zendo & draf reservasi meja"| E_WA
    E_GMP -->|"Payload data ulasan bintang 5 Google Maps"| SYS
```

### Tabel 2.1 Keterangan Entitas dan Aliran Data Diagram Konteks
| Kode | Nama Komponen | Tipe | Keterangan Aliran Data |
|---|---|---|---|
| `SYS-0` | Sistem Informasi Profil & E-Order Nucomu Cafe | Proses Inti | Proses sentral pengolah seluruh data transaksi, profil, dan pelacakan |
| `E_CST` | Pelanggan / Pengunjung | Entitas Luar | Mengakses profil, memesan 3 mode checkout, mengirim ulasan dan reservasi |
| `E_KSR` | Kasir POS | Entitas Luar | Memindai slip QR, melunasi transaksi, mengelola stok cepat, dan mencetak struk |
| `E_KRR` | Kurir Zendo | Entitas Luar | Mengautentikasi perangkat via PIN, memperbarui koordinat GPS, dan mengubah status antar |
| `E_ADM` | Administrator | Entitas Luar | Mengelola konten, konfigurasi harga/ongkir/menu, analitik penjualan, dan ekspor laporan |
| `E_WA` | WhatsApp Gateway / Web | Entitas Luar | Media pengiriman draf pesanan chat Zendo dan notifikasi konfirmasi reservasi meja |
| `E_GMP` | Google Places API | Entitas Luar | Penyedia data ulasan publik Google Maps kafe (jika API Key aktif) |

---

## 2. DFD Level 1 (Dekomposisi Sistem Utama)
DFD Level 1 memecah sistem utama menjadi 8 sub-proses utama yang terhubung ke 17 data store fisik aplikasi:

```mermaid
flowchart TB
    classDef ent fill:#1E293B,stroke:#94A3B8,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#F1F5F9,stroke:#0F172A,stroke-width:2px,color:#0F172A;
    classDef store fill:#FFFFFF,stroke:#475569,stroke-width:2px,stroke-dasharray: 4 4,color:#0F172A;

    %% Entitas
    E_CST["Pelanggan"]:::ent
    E_KSR["Kasir"]:::ent
    E_KRR["Kurir"]:::ent
    E_ADM["Admin"]:::ent
    E_WA["WhatsApp"]:::ent

    %% Proses Level 1
    P1["1.0<br>Autentikasi & Sesi"]:::proc
    P2["2.0<br>Kelola Profil & Konten"]:::proc
    P3["3.0<br>Kelola Menu & Kategori"]:::proc
    P4["4.0<br>Reservasi & Form Builder"]:::proc
    P5["5.0<br>Kurasi Ulasan Pelanggan"]:::proc
    P6["6.0<br>Transaksi Pemesanan & POS"]:::proc
    P7["7.0<br>Logistik & Pelacakan Kurir"]:::proc
    P8["8.0<br>Dasbor & Laporan Penjualan"]:::proc

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

    %% Aliran Proses 1.0
    E_ADM -->|"Email & password admin"| P1
    E_KSR -->|"Password staf kasir"| P1
    P1 <-->|"Verifikasi hash password & token cookie"| D1
    P1 -->|"Sesi Admin & Kasir Aktif"| E_ADM

    %% Aliran Proses 2.0
    E_ADM -->|"Kelola banner, about, fasilitas, galeri, event, jam"| P2
    P2 <-->|"Simpan & ambil data profil"| D10 & D11 & D12 & D13 & D15 & D17
    P2 -->|"Tampilan beranda & info profil"| E_CST

    %% Aliran Proses 3.0
    E_ADM -->|"Master menu, kategori, foto, harga, status"| P3
    E_KSR -->|"Pembaruan stok fisik menu"| P3
    P3 <-->|"Simpan & ambil menu, varian, kategori"| D2 & D3 & D4
    P3 -->|"Katalog menu publik & kasir"| E_CST & E_KSR

    %% Aliran Proses 4.0
    E_ADM -->|"Struktur field custom & target WA"| P4
    P4 <-->|"Simpan/ambil konfigurasi field"| D9
    E_CST -->|"Isian form reservasi"| P4
    P4 -->|"Simpan data reservasi status pending"| D8
    P4 -->|"Format draf WA reservasi"| E_WA

    %% Aliran Proses 5.0
    E_CST -->|"Kirim ulasan baru & rating"| P5
    P5 -->|"Simpan ulasan website"| D14
    E_ADM -->|"Pin/unpin, toggle status, hapus spam"| P5
    D14 -->|"Tampilkan ulasan terkurasi"| P5
    P5 -->|"Ulasan publik beranda"| E_CST

    %% Aliran Proses 6.0
    E_CST -->|"Checkout keranjang (3 mode)"| P6
    E_KSR -->|"Scan slip QR & pelunasan kasir"| P6
    D3 & D5 & D10 -->|"Data harga, meja, mode transaksi & ongkir"| P6
    P6 -->|"Simpan pesanan & potong stok"| D6 & D7 & D3
    P6 -->|"Inisiasi tugas pengantaran kurir"| D16
    P6 -->|"Slip QR pesanan (NCM-xxx)"| E_CST
    P6 -->|"Draf pesan WhatsApp Zendo"| E_WA
    P6 -->|"Cetak struk termal kasir"| E_KSR

    %% Aliran Proses 7.0
    E_KRR -->|"Verifikasi PIN, koordinat GPS, update status"| P7
    P7 <-->|"Simpan koordinat, log rute, device token"| D16
    P7 -->|"Update status pesanan ke orders"| D6
    P7 -->|"Tampilan posisi live kurir di peta"| E_CST

    %% Aliran Proses 8.0
    D6 & D7 & D10 -->|"Data transaksi & pengaturan grafik"| P8
    P8 -->|"Grafik dasbor omzet, ekspor PDF 1.4 & CSV"| E_ADM
```

### Tabel 2.2 Deskripsi 8 Sub-Proses DFD Level 1
| No | ID Proses | Nama Proses | Masukan (*Input*) | Ringkasan Proses | Keluaran (*Output*) | Tabel Basis Data Terlibat |
|---|---|---|---|---|---|---|
| 1 | `1.0` | Autentikasi & Sesi | Email/password admin, password kasir | Memvalidasi kredensial pengguna via Hash bcrypt dan menerbitkan cookie otentikasi HMAC SHA-256 | Status sesi aktif, redirect dashboard admin / POS kasir | `users` |
| 2 | `2.0` | Kelola Profil & Konten | Data banner, jadwal buka, narasi about, fasilitas, galeri, agenda acara | Menyimpan dan mengelola data konten profil kafe untuk disajikan di beranda publik | Tampilan visual profil beranda, slider banner, galeri foto | `banners`, `settings`, `operating_hours`, `facilities`, `gallery_items`, `events` |
| 3 | `3.0` | Kelola Menu & Kategori | Form master menu (nama, kategori, harga, foto, stok), stok cepat kasir | Menyimpan master menu dengan soft delete, relasi kategori berjenjang, dan pembaruan stok cepat | Katalog menu publik, daftar item POS kasir | `categories`, `menus`, `menu_options` |
| 4 | `4.0` | Reservasi & Form Builder | Isian tamu reservasi, konfigurasi field kustom admin | Memvalidasi input dinamis, mengunggah berkas opsional, menyimpan record reservasi status pending, dan merakit URL WhatsApp | Draf pesan WA ke admin, tabel reservasi masuk admin | `reservations`, `reservation_fields`, `settings` |
| 5 | `5.0` | Kurasi Ulasan Pelanggan | Form review & rating bintang pelanggan, aksi pin/unpin admin | Menampung testimoni mandiri, memvalidasi rating (1-5), kurasi admin untuk meminang ulasan unggulan ke beranda | Slider ulasan di beranda, tabel kurasi ulasan admin | `testimonials`, `settings` |
| 6 | `6.0` | Transaksi Pemesanan & POS | Item keranjang belanja, meja/takeaway, pin GPS alamat, scan slip QR kasir | Memproses 3 mode checkout dengan DB::transaction dan lockForUpdate stok, menerbitkan kode NCM, dan melayani kasir POS | Slip QR (NCM-xxx), draf WA Zendo, struk termal, rekaman transaksi | `orders`, `order_items`, `menus`, `tables`, `settings`, `zendo_deliveries` |
| 7 | `7.0` | Logistik & Pelacakan Kurir | PIN kurir, token sesi, koordinat GPS streaming, perubahan status antar | Mengikat perangkat kurir, validasi anti-fake GPS (akurasi/kecepatan), merekam polyline rute, dan menyiarkan koordinat | Peta live Leaflet.js real-time, status tahapan antar | `zendo_deliveries`, `orders`, `settings` |
| 8 | `8.0` | Dasbor & Laporan Penjualan | Rentang tanggal laporan, filter status pembayaran | Menghitung 4 metrik omzet, memetakan tren harian/mingguan/bulanan, dan mengompilasi laporan PDF/CSV | Dasbor analitik, file rekapitulasi PDF 1.4 Native, file CSV UTF-8 | `orders`, `order_items`, `settings` |

---

## 3. DFD Level 2 Sub-Proses Spesifik

### 3.1 DFD Level 2: Modul Pemesanan / Checkout Pelanggan (Proses 6.2 s/d 6.4)
```mermaid
flowchart TB
    classDef ent fill:#1E293B,stroke:#94A3B8,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#F1F5F9,stroke:#0F172A,stroke-width:2px,color:#0F172A;
    classDef store fill:#FFFFFF,stroke:#475569,stroke-width:2px,stroke-dasharray: 4 4,color:#0F172A;

    E_CST["Pelanggan"]:::ent
    E_WA["WhatsApp Zendo"]:::ent

    P6_A["6.1<br>Validasi Keranjang & Mode Transaksi"]:::proc
    P6_B["6.2<br>Penguncian Stok Menu (lockForUpdate)"]:::proc
    P6_C["6.3<br>Kalkulasi Jarak & Ongkir Bertingkat"]:::proc
    P6_D["6.4<br>Penerbitan Kode Pesanan (NCM-Ymd-xxx)"]:::proc
    P6_E["6.5<br>Distribusi Luaran Sesuai Mode"]:::proc

    D3[("D3: menus")]:::store
    D6[("D6: orders")]:::store
    D7[("D7: order_items")]:::store
    D10[("D10: settings")]:::store
    D16[("D16: zendo_deliveries")]:::store

    E_CST -->|"Items keranjang, mode pilihan, nama, meja/alamat"| P6_A
    D10 -->|"Status aktif 3 mode pesanan"| P6_A
    P6_A -->|"Daftar item tervalidasi"| P6_B
    P6_B <-->|"Lock baris menu & kurangi stok fisik"| D3
    P6_B -->|"Subtotal harga resmi server"| P6_C
    P6_C <-->|"Koordinat kafe & tarif bertingkat"| D10
    P6_C -->|"Total akhir (Subtotal + Ongkir)"| P6_D
    P6_D <-->|"Hitung urutan hari ini & simpan record"| D6
    P6_D -->|"Simpan baris item pesanan"| D7
    P6_D -->|"Inisiasi tugas pengiriman kurir (Mode 3)"| D16
    P6_D -->|"Data pesanan tersimpan"| P6_E
    P6_E -->|"Halaman Slip QR (Mode 1)"| E_CST
    P6_E -->|"Draf pesan chat WA (Mode 2)"| E_WA
    P6_E -->|"Tautan live tracking (Mode 3)"| E_CST
```

### Tabel 2.3 Keterangan DFD Level 2 Pemesanan / Checkout
| Kode | Nama Proses / Komponen | Keterangan Operasional |
|---|---|---|
| `6.1` | Validasi Keranjang & Mode | Memeriksa ketersediaan item, format nomor HP, dan apakah mode transaksi yang dipilih sedang aktif di tabel `settings` |
| `6.2` | Penguncian Stok Menu | Memanggil `Menu::whereKey(...)->lockForUpdate()` di dalam `DB::transaction` untuk mencegah *race condition* stok |
| `6.3` | Kalkulasi Ongkir Bertingkat | Menghitung jarak lurus Haversine kafe-pelanggan, memvalidasi jarak rute jalan, dan mengonversi ke tarif tier ongkir |
| `6.4` | Penerbitan Kode Pesanan | Membuat kode unik terstandar `NCM-Ymd-xxx` dan menyimpan entitas utama `orders`, `order_items`, serta `zendo_deliveries` |
| `6.5` | Distribusi Luaran Mode | Mengarahkan respon JSON: Mode 1 ke slip QR `/qr/pesanan/{code}`, Mode 2 ke API WhatsApp Zendo, Mode 3 ke `/zendo/lacak/{token}` |

---

### 3.2 DFD Level 2: Modul Kasir & Pelunasan (Proses 6.5 & 6.6)
```mermaid
flowchart TB
    classDef ent fill:#1E293B,stroke:#94A3B8,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#F1F5F9,stroke:#0F172A,stroke-width:2px,color:#0F172A;
    classDef store fill:#FFFFFF,stroke:#475569,stroke-width:2px,stroke-dasharray: 4 4,color:#0F172A;

    E_KSR["Kasir POS"]:::ent

    P_K1["6.5.1<br>Pencarian / Scan Slip QR Pesanan"]:::proc
    P_K2["6.5.2<br>Tampilkan Ringkasan Tagihan POS"]:::proc
    P_K3["6.6.1<br>Terima Pembayaran Tunai / QRIS"]:::proc
    P_K4["6.6.2<br>Pembaruan Status Lunas (DB::transaction)"]:::proc
    P_K5["6.6.3<br>Kompilasi & Cetak Struk Termal"]:::proc

    D6[("D6: orders")]:::store
    D7[("D7: order_items")]:::store
    D10[("D10: settings")]:::store

    E_KSR -->|"Input kode NCM-xxx atau scan kamera QR"| P_K1
    P_K1 <-->|"Query pesanan berdasarkan kode slip"| D6
    D6 & D7 -->|"Rincian item & total tagihan"| P_K2
    P_K2 -->|"Tampilan modal kasir & kalkulator kembalian"| E_KSR
    E_KSR -->|"Konfirmasi uang tunai diterima / bukti QRIS"| P_K3
    P_K3 -->|"Data pelunasan kasir"| P_K4
    P_K4 -->|"Set payment_status=paid, status=completed"| D6
    P_K4 -->|"Trigger cetak struk"| P_K5
    D10 -->|"Ambil nama kafe, alamat & footer struk"| P_K5
    P_K5 -->|"View struk 58mm/80mm siap cetak thermal"| E_KSR
```

### Tabel 2.4 Keterangan DFD Level 2 Kasir & Pelunasan
| Kode | Nama Proses / Komponen | Keterangan Operasional |
|---|---|---|
| `6.5.1` | Pencarian / Scan Slip QR | Menerima pemindaian kode QR via kamera atau pencarian teks manual pada endpoint `/kasir/pesanan/cari` |
| `6.5.2` | Tampilkan Ringkasan Tagihan | Menampilkan nama pemesan, nomor meja/take away, daftar item, subtotal, dan sisa tagihan |
| `6.6.1` | Terima Pembayaran | Menginput nominal uang bayar kasir dan otomatis menghitung nominal uang kembalian (*change*) |
| `6.6.2` | Pembaruan Status Lunas | Menjalankan `DB::transaction` untuk mengubah `payment_status` menjadi `'paid'` dan `status` pesanan menjadi `'completed'` |
| `6.6.3` | Kompilasi & Cetak Struk | Mengompilasi template struk termal dengan font monospace dan trigger otomatis dialog `window.print()` |

---

### 3.3 DFD Level 2: Modul Pengantaran Kurir Zendo (Proses 7.1 s/d 7.4)
```mermaid
flowchart TB
    classDef ent fill:#1E293B,stroke:#94A3B8,stroke-width:2px,color:#FFFFFF;
    classDef proc fill:#F1F5F9,stroke:#0F172A,stroke-width:2px,color:#0F172A;
    classDef store fill:#FFFFFF,stroke:#475569,stroke-width:2px,stroke-dasharray: 4 4,color:#0F172A;

    E_KRR["Kurir Zendo"]:::ent
    E_CST["Pelanggan"]:::ent

    P7_A["7.1<br>Verifikasi PIN & Ikat Perangkat"]:::proc
    P7_B["7.2<br>Validasi Koordinat Anti-Fake GPS"]:::proc
    P7_C["7.3<br>Pembaruan Posisi & Jejak Rute (Trail)"]:::proc
    P7_D["7.4<br>Perubahan Status Tahapan Antar"]:::proc

    D10[("D10: settings")]:::store
    D16[("D16: zendo_deliveries")]:::store
    D6[("D6: orders")]:::store

    E_KRR -->|"Input PIN 4-6 digit & device fingerprint"| P7_A
    D10 -->|"Validasi daftar PIN kurir resmi kafe"| P7_A
    P7_A -->|"Simpan device_fingerprint & set is_bound=1"| D16
    P7_A -->|"Mode kendali kurir terbuka"| E_KRR

    E_KRR -->|"Streaming koordinat lat, lng, speed, accuracy"| P7_B
    P7_B -->|"Cek akurasi (>100m) & kecepatan (>100)"| P7_B
    P7_B -->|"Koordinat valid terverifikasi"| P7_C
    P7_C -->|"Simpan courier_lat/lng & append route_trail"| D16
    D16 -->|"Siarkan posisi live marker & rute peta"| E_CST

    E_KRR -->|"Update status: menuju lokasi / selesai antar"| P7_D
    P7_D -->|"Set status pengantaran: delivered/completed"| D16
    P7_D -->|"Sinkronkan status pesanan selesai"| D6
    P7_D -->|"Pemberitahuan pesanan tiba di tujuan"| E_CST
```

### Tabel 2.5 Keterangan DFD Level 2 Pengantaran Kurir Zendo
| Kode | Nama Proses / Komponen | Keterangan Operasional |
|---|---|---|
| `7.1` | Verifikasi PIN & Ikat Perangkat | Kurir memasukkan PIN rahasia untuk mengikat perangkat HP fisik (`device_fingerprint`) ke token pengiriman |
| `7.2` | Validasi Anti-Fake GPS | Menganalisis parameter `accuracy` (>100 meter ditolak) dan parameter `speed` dari GPS Geolocation API |
| `7.3` | Pembaruan Posisi & Jejak | Menyimpan koordinat terkini kurir dan menambahkan riwayat lintasan ke kolom JSON `route_trail` |
| `7.4` | Perubahan Status Pengantaran | Mengubah status logistik menjadi `delivering` hingga `delivered` dan menyinkronkan status pesanan ke `orders` |

---

## 4. Use Case Diagram Sistem
Diagram Use Case memetakan interaksi fungsional antara 4 aktor utama (*Pelanggan*, *Kasir*, *Kurir*, dan *Admin*) dengan modul-modul sistem:

```mermaid
flowchart LR
    classDef actor fill:#1E293B,stroke:#94A3B8,stroke-width:2px,color:#FFFFFF;
    classDef uc fill:#FFFFFF,stroke:#0F172A,stroke-width:1.5px,color:#0F172A;

    %% Aktor
    A_CST["Aktor: Pelanggan"]:::actor
    A_KSR["Aktor: Kasir"]:::actor
    A_KRR["Aktor: Kurir"]:::actor
    A_ADM["Aktor: Admin"]:::actor

    %% Use Cases Pelanggan
    UC_P1([Lihat Profil, Fasilitas & Agenda]):::uc
    UC_P2([Pencarian & Detail Menu]):::uc
    UC_P3([Kirim Ulasan & Rating]):::uc
    UC_P4([Pengajuan Reservasi Meja]):::uc
    UC_P5([Pesan Mandiri 3 Mode Checkout]):::uc
    UC_P6([Tampilkan Slip QR Pesanan]):::uc
    UC_P7([Pelacakan Live GPS Kurir]):::uc

    %% Use Cases Kasir
    UC_K1([Login / Logout Sesi Kasir]):::uc
    UC_K2([Katalog POS & Order Langsung]):::uc
    UC_K3([Scan & Pencarian Slip QR]):::uc
    UC_K4([Pelunasan Transaksi]):::uc
    UC_K5([Cetak Struk Termal]):::uc
    UC_K6([Update Stok Cepat]):::uc

    %% Use Cases Kurir
    UC_R1([Autentikasi PIN & Ikat Perangkat]):::uc
    UC_R2([Streaming Koordinat GPS Antar]):::uc
    UC_R3([Perbarui Status Pengantaran]):::uc

    %% Use Cases Admin
    UC_A1([Login / Logout Sesi Admin]):::uc
    UC_A2([Pantau Dasbor Analitik Omzet]):::uc
    UC_A3([Kelola Konten & Banner Promo]):::uc
    UC_A4([CRUD Menu & Kategori]):::uc
    UC_A5([Kurasi Testimoni Pelanggan]):::uc
    UC_A6([Form Builder & Konfirmasi Reservasi]):::uc
    UC_A7([Konfigurasi 3 Mode & QRIS]):::uc
    UC_A8([Tarif Ongkir & Manajemen Kurir]):::uc
    UC_A9([Ekspor Laporan Penjualan PDF/CSV]):::uc
    UC_A10([Kelola Jam Kerja, Meja & Profil]):::uc

    %% Relasi Pelanggan
    A_CST --- UC_P1
    A_CST --- UC_P2
    A_CST --- UC_P3
    A_CST --- UC_P4
    A_CST --- UC_P5
    A_CST --- UC_P6
    A_CST --- UC_P7

    %% Relasi Kasir
    A_KSR --- UC_K1
    A_KSR --- UC_K2
    A_KSR --- UC_K3
    A_KSR --- UC_K4
    A_KSR --- UC_K5
    A_KSR --- UC_K6

    %% Relasi Kurir
    A_KRR --- UC_R1
    A_KRR --- UC_R2
    A_KRR --- UC_R3

    %% Relasi Admin
    A_ADM --- UC_A1
    A_ADM --- UC_A2
    A_ADM --- UC_A3
    A_ADM --- UC_A4
    A_ADM --- UC_A5
    A_ADM --- UC_A6
    A_ADM --- UC_A7
    A_ADM --- UC_A8
    A_ADM --- UC_A9
    A_ADM --- UC_A10
```

### Tabel 2.6 Pemetaan Aktor dan Deskripsi Use Case
| Aktor | Kode Use Case | Nama Use Case | Deskripsi Singkat Tindakan |
|---|---|---|---|
| **Pelanggan** | `UC_P1` | Lihat Profil, Fasilitas & Agenda | Menjelajahi informasi profil, jadwal buka, slider fasilitas, galeri, dan event kafe |
| | `UC_P2` | Pencarian & Detail Menu | Mencari menu via filter kategori, melihat modal varian dan harga pokok menu |
| | `UC_P3` | Kirim Ulasan & Rating | Mengirimkan ulasan pengalaman, rating bintang 1-5, dan unggah foto opsional |
| | `UC_P4` | Pengajuan Reservasi Meja | Mengisi formulir reservasi dinamis beserta tanggal, jam, jumlah orang, dan field kustom |
| | `UC_P5` | Pesan Mandiri 3 Mode Checkout | Memasukkan menu ke keranjang dan checkout via Dine-In/Takeaway, Chat Zendo, atau GPS Antar |
| | `UC_P6` | Tampilkan Slip QR Pesanan | Menampilkan barcode QR kode `NCM-xxx` untuk dipindai oleh kasir di meja kasir |
| | `UC_P7` | Pelacakan Live GPS Kurir | Memantau pergerakan marker kurir secara real-time pada peta interaktif Leaflet.js |
| **Kasir** | `UC_K1` | Login / Logout Sesi Kasir | Masuk ke antarmuka POS kasir menggunakan kata sandi khusus staf kasir |
| | `UC_K2` | Katalog POS & Order Langsung | Menangani transaksi langsung pelanggan yang memesan langsung di meja kasir |
| | `UC_K3` | Scan & Pencarian Slip QR | Memindai slip pesanan menggunakan kamera atau mencari kode slip pesanan pelanggan |
| | `UC_K4` | Pelunasan Transaksi | Menerima pembayaran tunai/QRIS, menginput nominal bayar, dan menghitung kembalian |
| | `UC_K5` | Cetak Struk Termal | Mencetak struk fisik bukti transaksi format kertas termal 58mm / 80mm |
| | `UC_K6` | Update Stok Cepat | Mengubah status ketersediaan menu dan stok fisik langsung dari antarmuka kasir |
| **Kurir** | `UC_R1` | Autentikasi PIN & Ikat Perangkat | Membuka kunci pelacakan menggunakan PIN kurir dan mengikat browser HP kurir |
| | `UC_R2` | Streaming Koordinat GPS Antar | Mengirimkan koordinat lintang/bujur perangkat saat bergerak mengantar pesanan |
| | `UC_R3` | Perbarui Status Pengantaran | Mengubah status logistik menjadi `delivering` hingga pesanan berhasil diserahkan (`delivered`) |
| **Admin** | `UC_A1` | Login / Logout Sesi Admin | Masuk dan keluar panel manajemen admin dengan kredensial terenkripsi |
| | `UC_A2` | Pantau Dasbor Analitik Omzet | Memantau grafik tren omzet, menu terlaris, dan 4 kartu metrik performa bisnis |
| | `UC_A3` | Kelola Konten & Banner Promo | Mengunggah banner berjadwal, memperpanjang durasi, dan mengedit konten profil |
| | `UC_A4` | CRUD Menu & Kategori | Menambah menu baru, mengatur varian, menghapus dengan soft delete, dan memulihkan menu |
| | `UC_A5` | Kurasi Testimoni Pelanggan | Memilih ulasan yang disematkan (*pin*) ke beranda atau menyembunyikan ulasan spam |
| | `UC_A6` | Form Builder & Konfirmasi Reservasi | Menambah kolom formulir reservasi baru dan mengubah status konfirmasi tamu |
| | `UC_A7` | Konfigurasi 3 Mode & QRIS | Mengaktifkan/menonaktifkan mode pemesanan dan mengunggah gambar QRIS statis kafe |
| | `UC_A8` | Tarif Ongkir & Manajemen Kurir | Mengatur daftar nomor kurir Zendo dan menetapkan tarif ongkir jarak bertingkat |
| | `UC_A9` | Ekspor Laporan Penjualan PDF/CSV | Mengunduh berkas laporan rekap transaksi format PDF 1.4 Native dan CSV Excel |
| | `UC_A10` | Kelola Jam Kerja, Meja & Profil | Mengatur hari buka kafe, nomor meja fisik kafe, dan profil pribadi akun admin |
