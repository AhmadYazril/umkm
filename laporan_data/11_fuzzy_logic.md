# TAHAP 11: ANALISIS LOGIKA FUZZY (FUZZY LOGIC)
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Status Implementasi di Kode:** **TIDAK DITEMUKAN**

---

## 1. Hasil Pemeriksaan Kode Program
Berdasarkan penelusuran kata kunci teknis (`fuzzy`, `fuzzification`, `defuzzification`, `mamdani`, `sugeno`, `tsukamoto`) di seluruh direktori `app/`, `config/`, `routes/`, dan `database/`:
- **Status:** **TIDAK DITEMUKAN** implementasi logika fuzzy di dalam kode proyek ini.
- Logika bisnis saat ini sepenuhnya menggunakan **logika biner (*crisp logic*)** berbasis percabangan `if-else` konvensional (misalnya tarif ongkir bertingkat menggunakan batas jarak tegas `<= 3.0 km`, dan Anti-Fake GPS menggunakan ambang batas kecepatan statis `> 45 m/s`).

---

## 2. Usulan Penerapan Logika Fuzzy yang Masuk Akal (Tanpa Mengubah Kode)
Sebagai bahan penyusunan rekomendasi akademis laporan akhir (SAS), berikut adalah 3 usulan modul yang sangat relevan untuk ditingkatkan menggunakan kecerdasan komputasi Logika Fuzzy:

### Usulan 1: Estimasi Waktu Tunggu dan Penyiapan Pesanan (*Dynamic Food Preparation Time*)
Saat ini pelanggan tidak mendapatkan estimasi menit kapan pesanan siap disajikan. Logika Fuzzy metode **Mamdani** dapat diterapkan untuk menghitung estimasi waktu secara adaptif.

#### (a) Variabel Masukan (*Input Variables*):
1. **`Jumlah_Item` (Kuantitas total item pesanan dalam antrean):**
   - Himpunan Fuzzy: *Sedikit* [1 s.d. 4 porsi], *Sedang* [3 s.d. 10 porsi], *Banyak* [8 s.d. 25+ porsi].
2. **`Kepadatan_Dapur` (Jumlah pesanan aktif berstatus `processing` di kasir):**
   - Himpunan Fuzzy: *Lengang* [1 s.d. 3 antrean], *Normal* [2 s.d. 6 antrean], *Padat / Rush Hour* [5 s.d. 15+ antrean].

#### (b) Variabel Keluaran (*Output Variable*):
- **`Estimasi_Waktu_Tunggu` (Menit):**
  - Himpunan Fuzzy: *Cepat* [5 s.d. 15 menit], *Standar* [12 s.d. 25 menit], *Lama* [20 s.d. 45 menit].

#### (c) Contoh Basis Aturan (*Fuzzy Rule Base*):
- `[R1]` IF `Jumlah_Item` *Sedikit* AND `Kepadatan_Dapur` *Lengang* THEN `Estimasi_Waktu_Tunggu` *Cepat*.
- `[R2]` IF `Jumlah_Item` *Sedang* AND `Kepadatan_Dapur` *Normal* THEN `Estimasi_Waktu_Tunggu` *Standar*.
- `[R3]` IF `Jumlah_Item` *Banyak* OR `Kepadatan_Dapur` *Padat* THEN `Estimasi_Waktu_Tunggu` *Lama*.
- `[R4]` IF `Jumlah_Item` *Sedikit* AND `Kepadatan_Dapur` *Padat* THEN `Estimasi_Waktu_Tunggu` *Standar*.

#### (d) Metode Defuzzifikasi:
- Menggunakan metode **Centroid (Center of Gravity)** untuk menghasilkan nilai riil menit yang ditampilkan ke layar slip QR pelanggan.

---

### Usulan 2: Penentuan Prioritas Pengadaan Ulang Stok Bahan (*Dynamic Inventory Reorder Point*)
Saat ini menu hanya berstatus ada (`is_available = 1`) atau habis (`stock = 0`). Logika Fuzzy dapat memprediksi tingkat urgensi belanja bahan sebelum kafe kehabisan stok sajian favorit.

#### (a) Variabel Masukan (*Input Variables*):
1. **`Sisa_Stok_Porsi` (Sisa stok tercatat di tabel `menus`):**
   - Himpunan Fuzzy: *Kritis* [0 s.d. 5 porsi], *Menipis* [4 s.d. 15 porsi], *Aman* [12 s.d. 50+ porsi].
2. **`Laju_Penjualan_Harian` (Rata-rata porsi terjual per hari dalam 7 hari terakhir):**
   - Himpunan Fuzzy: *Lambat* [1 s.d. 5 cup/hari], *Sedang* [4 s.d. 15 cup/hari], *Cepat / Best-Seller* [12 s.d. 35+ cup/hari].

#### (b) Variabel Keluaran (*Output Variable*):
- **`Indeks_Urgensi_Restock` (Skala 0 s.d. 100):**
  - Himpunan Fuzzy: *Rendah* [0 s.d. 40], *Perhatian* [30 s.d. 70], *Mendesak / Segera Belanja* [60 s.d. 100].

#### (c) Contoh Basis Aturan (*Fuzzy Rule Base*):
- `[R1]` IF `Sisa_Stok_Porsi` *Kritis* AND `Laju_Penjualan_Harian` *Cepat* THEN `Indeks_Urgensi_Restock` *Mendesak*.
- `[R2]` IF `Sisa_Stok_Porsi` *Menipis* AND `Laju_Penjualan_Harian` *Cepat* THEN `Indeks_Urgensi_Restock` *Mendesak*.
- `[R3]` IF `Sisa_Stok_Porsi` *Menipis* AND `Laju_Penjualan_Harian` *Lambat* THEN `Indeks_Urgensi_Restock` *Perhatian*.
- `[R4]` IF `Sisa_Stok_Porsi` *Aman* THEN `Indeks_Urgensi_Restock` *Rendah*.

#### (d) Metode Defuzzifikasi:
- Menggunakan metode **Mean of Maximum (MOM)** untuk memicu notifikasi peringatan dini di dasbor admin jika indeks urgensi melebihi nilai 70.

---

### Usulan 3: Penilaian Risiko Anomali Anti-Fake GPS Kurir (*Fuzzy Anomaly Risk Scoring*)
Saat ini verifikasi GPS di `ZendoTrackingController` memakai batas kaku (misal `speed > 45 m/s`). Dengan Logika Fuzzy metode **Sugeno**, sistem dapat menilai derajat keaslian sinyal GPS secara lebih halus.

#### (a) Variabel Masukan (*Input Variables*):
1. **`Akurasi_GPS` (Meter):**
   - Himpunan Fuzzy: *Presisi* [0 s.d. 15 m], *Sedang* [10 s.d. 50 m], *Buruk* [40 s.d. 200 m].
2. **`Lompatan_Kecepatan` (Perpindahan meter / detik):**
   - Himpunan Fuzzy: *Wajar* [0 s.d. 12 m/s], *Tinggi* [10 s.d. 25 m/s], *Ekstrem / Teleport* [20 s.d. 60+ m/s].
3. **`Penyimpangan_Waktu` (Clock Skew dalam detik):**
   - Himpunan Fuzzy: *Sinkron* [0 s.d. 5 detik], *Deviasi_Kecil* [4 s.d. 30 detik], *Deviasi_Tinggi* [25 s.d. 120+ detik].

#### (b) Variabel Keluaran (*Output Sugeno Singleton*):
- `Tingkat_Kecurigaan_GPS` = { *Normal* = 0, *Sinyal_Lemah* = 0.4, *Indikasi_Palsu* = 1.0 }.

#### (c) Contoh Basis Aturan (*Fuzzy Rule Base*):
- `[R1]` IF `Akurasi_GPS` *Presisi* AND `Lompatan_Kecepatan` *Wajar* AND `Penyimpangan_Waktu` *Sinkron* THEN `Tingkat_Kecurigaan_GPS` = 0 (Normal).
- `[R2]` IF `Akurasi_GPS` *Buruk* AND `Lompatan_Kecepatan` *Wajar* THEN `Tingkat_Kecurigaan_GPS` = 0.4 (Sinyal Lemah, tidak perlu dikunci).
- `[R3]` IF `Lompatan_Kecepatan` *Ekstrem* OR `Penyimpangan_Waktu` *Deviasi_Tinggi* THEN `Tingkat_Kecurigaan_GPS` = 1.0 (Mock Location / Fake GPS).

#### (d) Manfaat Nyata:
- Mencegah terjadinya penguncian salah (*false positive lock*) saat kurir melewati area pepohonan lebat di pedesaan Kedungwaru Tulungagung di mana sinyal satelit menurun secara alami.
