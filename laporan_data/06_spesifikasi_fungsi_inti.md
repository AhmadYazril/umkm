# TAHAP 6: SPESIFIKASI FUNGSI INTI SISTEM
**Proyek:** Sistem Informasi Profil dan E-Order Nucomu Cafe (Laravel)
**Format Standar:** 8 Sub-bagian per Fungsi Inti (Tabel Input, Tabel Output, Layar, Query Utama, Function Key/Object, Layar Pesan, Proses/Algoritma, Report)

---

## Fungsi 1: Login dan Logout Staf (Admin & Kasir)
### (1) Tabel Input
`users`

### (2) Tabel Output
`users` (pemberian token sesi cookie), cookie browser `nucomu_admin_auth_v1` dan `nucomu_kasir_auth_v2`

### (3) Layar
- Admin: `resources/views/admin/login.blade.php`<br>- Kasir: `resources/views/kasir/login.blade.php`

### (4) Query Utama
- Query Verifikasi Admin:
```php
$user = User::where('email', $request->email)->first();
Hash::check($request->password, $user->password);
```
- Query Verifikasi Kasir:
```php
$user = User::where('role', 'kasir')->first();
Hash::check($request->password, $user->password);
```

### (5) Function Key / Object pada Layar
- Input `email` (admin)
- Input `password` (admin & kasir)
- Checkbox `remember`
- Tombol `btn-submit` ("Masuk" / "Buka Kasir")
- Tombol `btn-logout` ("Keluar Sesi")

### (6) Layar Pesan
- "Email dan password admin wajib diisi."
- "Kredensial yang Anda masukkan tidak cocok dengan data kami."
- "Akun Anda tidak memiliki hak akses administrator."
- "Kata sandi kasir yang Anda masukkan salah."
- Toast sukses: "Login berhasil, mengalihkan..." / "Sesi kasir berhasil ditutup."

### (7) Proses / Algoritma
1. Pengguna membuka rute `/admin/login` atau `/kasir/login`.
2. Pengguna mengisi kredensial dan menekan tombol login.
3. Server memvalidasi keberadaan field input via `$request->validate()`.
4. Server mencari record pengguna di tabel `users` berdasarkan email atau role `kasir`.
5. Server mencocokkan password mentah dengan hash bcrypt menggunakan `Hash::check()`.
6. Jika valid, server mengenerate token HMAC SHA-256 (`nucomu_admin_auth_v1` atau `nucomu_kasir_auth_v2`) dan menyematkannya ke cookie browser.
7. Server mengarahkan pengguna ke `/admin/dashboard` atau `/kasir`.
8. Saat logout, server menghapus sesi auth dan menarik kembali (revoke/expire) cookie otentikasi.

### (8) Report
Status sesi aktif, redirect URL, dan badge user di topbar.

---

## Fungsi 2: Checkout Pesanan Mandiri (Dine In / Take Away / Chat Zendo / Delivery)
### (1) Tabel Input
`menus`, `tables`, `settings`

### (2) Tabel Output
`orders`, `order_items`, `zendo_deliveries`, `menus` (stok berkurang)

### (3) Layar
`resources/views/components/cart-drawer.blade.php` dan `resources/views/home/index.blade.php`

### (4) Query Utama
```php
DB::transaction(function () use ($request, ...) {
    $menu = Menu::whereKey($validMenuId)->lockForUpdate()->first();
    $menu->decrement('stock', $qty);
    $order = Order::create([...]);
    $order->items()->createMany($normalizedItems);
    if ($isZendoTracking) {
        ZendoDelivery::create([...]);
    }
});
```

### (5) Function Key / Object pada Layar
- Radio button Mode Pesanan (Dine-In, Chat Zendo, Antar Zendo GPS)
- Input `customer_name`, `customer_phone`, `table_no`, `delivery_address`
- Pin koordinat peta pelanggan (`customer_lat`, `customer_lng`)
- Radio button Pembayaran (Tunai di Kasir / QRIS)
- Tombol `btn-checkout` ("Kirim Pesanan" / "Buka WhatsApp")

### (6) Layar Pesan
- "Keranjang pesanan masih kosong."
- "Nama lengkap pemesan wajib diisi."
- "Alamat pengiriman wajib dipilih pada peta atau diisi untuk pengiriman Kurir Zendo."
- "Menu ':name' sedang tidak tersedia saat ini."
- "Stok menu ':name' tidak mencukupi (tersisa :stock porsi)."

### (7) Proses / Algoritma
1. Pelanggan memilih menu dan menekan tombol checkout pada Cart Drawer.
2. JavaScript memvalidasi input formulir di sisi klien dan mengirim payload POST ke `/pesan`.
3. Server memvalidasi ketersediaan mode transaksi di tabel `settings`.
4. Server menghitung jarak garis lurus via Haversine formula dan tarif ongkir bertingkat jika mode antar Zendo.
5. Server membuka `DB::transaction` dan mengunci baris menu via `Menu::lockForUpdate()`.
6. Server memeriksa stok menu: jika stok kurang, transaksi dibatalkan (rollback). Jika cukup, stok dikurangi (`decrement`).
7. Server membuat kode pesanan `NCM-Ymd-xxx` dan menyimpan record ke `orders` dan `order_items`.
8. Jika mode delivery Zendo, server menginisiasi tugas kurir di `zendo_deliveries`.
9. Server mengembalikan respon JSON berisi kode pesanan, URL slip QR, atau URL WhatsApp Zendo.

### (8) Report
Slip pesanan digital / Halaman QR (`/qr/pesanan/{code}`), link WhatsApp terformat, atau tautan live tracking kurir.

---

## Fungsi 3: Pencarian dan Pemindaian Slip QR oleh Kasir
### (1) Tabel Input
`orders`, `order_items`

### (2) Tabel Output
None (Operasi Read-Only)

### (3) Layar
- Kasir: `resources/views/kasir/index.blade.php` (Modal Scanner & Search Bar)<br>- Admin: `resources/views/admin/orders/scan.blade.php`

### (4) Query Utama
```php
$order = Order::with('items')
    ->where('code', $searchCode)
    ->first();
```

### (5) Function Key / Object pada Layar
- Input teks `order_search` ("Ketik kode NCM-xxx")
- Tombol `btn-scan-qr` (Membuka kamera HTML5 scanner)
- Video viewfinder pemindai kamera
- Tombol `btn-lookup-order` ("Cari Pesanan")

### (6) Layar Pesan
- "Pesanan dengan kode ':code' tidak ditemukan."
- "Kamera berhasil diaktifkan. Arahkan ke QR code pelanggan."
- "Izin akses kamera ditolak oleh peramban."

### (7) Proses / Algoritma
1. Pelanggan menunjukkan slip QR pesanan (`NCM-xxx`) di layar ponselnya.
2. Kasir menekan tombol pemindai kamera atau mengetik kode pesanan di kolom pencarian.
3. Skrip JavaScript kamera membaca string kode dari frame video.
4. Sistem mengirim request GET ke `/kasir/pesanan/cari?q={kode}`.
5. Server mencari data transaksi di tabel `orders` beserta relasi `order_items`.
6. Jika ditemukan, rincian pesanan, meja/takeaway, dan subtotal langsung dimuat ke panel kasir.
7. Jika pesanan sudah lunas sebelumnya, sistem memberikan penanda badge 'SUDAH LUNAS'.

### (8) Report
Tampilan detail tagihan pesanan di layar POS kasir.

---

## Fungsi 4: Pelunasan Transaksi Kasir
### (1) Tabel Input
`orders`, `order_items`, `settings`

### (2) Tabel Output
`orders` (`payment_status = 'paid'`, `status = 'completed'`, `paid_at = now()`)

### (3) Layar
`resources/views/kasir/index.blade.php` (Panel Pembayaran Kasir)

### (4) Query Utama
```php
DB::transaction(function () use ($order, $cashReceived) {
    $order->update([
        'payment_status' => 'paid',
        'status' => 'completed',
        'paid_at' => Carbon::now(),
    ]);
});
```

### (5) Function Key / Object pada Layar
- Input `cash_received` (Nominal uang tunai diterima)
- Tombol pecahan cepat (Uang Pas, 10k, 20k, 50k, 100k)
- Kalkulator kembalian otomatis (*Change Display*)
- Tombol `btn-pay-cash` ("Bayar Tunai")
- Tombol `btn-pay-qris` ("Konfirmasi QRIS")
- Tombol `btn-print-receipt` ("Cetak Struk")

### (6) Layar Pesan
- "Nominal uang yang diterima kurang dari total tagihan."
- "Transaksi berhasil dilunasi. Kembalian: Rp :change"
- "Gagal memperbarui status transaksi: :error"

### (7) Proses / Algoritma
1. Kasir memeriksa total tagihan pesanan yang dimuat di layar POS.
2. Kasir memilih metode pembayaran (Tunai atau QRIS).
3. Jika tunai, kasir memasukkan jumlah uang fisik yang diserahkan pelanggan.
4. Sistem menghitung selisih uang kembalian secara otomatis.
5. Kasir menekan tombol konfirmasi bayar lunas.
6. Server menjalankan `DB::transaction` untuk mengupdate `orders.payment_status` menjadi `'paid'` dan `orders.status` menjadi `'completed'`.
7. Sistem menampilkan modal sukses pelunasan dan otomatis memicu dialog cetak struk termal.

### (8) Report
Struk bukti pembayaran termal kasir dan penambahan omzet pada dasbor penjualan.

---

## Fungsi 5: Buka dan Tutup Shift Kasir
### (1) Tabel Input
**TIDAK DITEMUKAN** (Tidak ada tabel `shifts` atau `cashier_sessions`)

### (2) Tabel Output
**TIDAK DITEMUKAN**

### (3) Layar
**TIDAK DITEMUKAN** (Hanya ada layar login kasir biasa di `resources/views/kasir/login.blade.php`)

### (4) Query Utama
**TIDAK DITEMUKAN** di dalam seluruh berkas Controller, Model, dan Migration.

### (5) Function Key / Object pada Layar
**TIDAK DITEMUKAN** (Tidak ada tombol input modal awal, tombol rekap kas laci, atau tombol tutup shift)

### (6) Layar Pesan
**TIDAK DITEMUKAN**

### (7) Proses / Algoritma
1. [FAKTA KODE] Fitur 'Buka dan Tutup Shift Kasir' serta 'Pencatatan Modal Awal' TIDAK DITEMUKAN di dalam basis data maupun kode program.
2. Operasional kasir saat ini hanya berupa autentikasi kata sandi (`KasirController@login`), katalog POS langsung, dan logout (`KasirController@logout`).
3. Tidak ada prosedur penguncian saldo kas awal maupun rekonsiliasi uang fisik saat staf berganti giliran kerja.

### (8) Report
**TIDAK DITEMUKAN** (Status: Perlu Konfirmasi / Rekomendasi Fitur Tambahan)

---

## Fungsi 6: Cetak Struk Transaksi Termal
### (1) Tabel Input
`orders`, `order_items`, `settings`

### (2) Tabel Output
None (Dokumen Cetak Klien)

### (3) Layar
- Kasir: `resources/views/kasir/receipt.blade.php`<br>- Admin: `resources/views/admin/orders/receipt.blade.php`

### (4) Query Utama
```php
$order = Order::with('items')->findOrFail($id);
$settings = Setting::whereIn('key', ['cafe_name', 'cafe_address', 'cafe_phone'])->pluck('value', 'key');
```

### (5) Function Key / Object pada Layar
- Tombol `btn-print` ("Cetak Struk")
- Tombol `btn-back` ("Kembali ke Kasir")
- Skrip auto trigger: `window.addEventListener('load', () => window.print())`

### (6) Layar Pesan
- Dialog cetak peramban peramban (*Browser Print Dialog*)
- "Pesanan tidak ditemukan atau belum dapat dicetak struknya."

### (7) Proses / Algoritma
1. Setelah pesanan dilunasi, kasir menekan tombol 'Cetak Struk' atau diarahkan ke `/kasir/pesanan/{id}/struk`.
2. Server mengambil data pesanan, baris item pesanan, dan profil kafe dari basis data.
3. Blade merender tampilan HTML khusus berdimensi 58mm / 80mm dengan CSS `@media print`.
4. Saat halaman selesai dimuat, JavaScript mengeksekusi `window.print()` secara otomatis.
5. Printer kasir termal mencetak struk fisik bukti pembayaran.

### (8) Report
Lembar cetak fisik struk kasir termal (Header nama kafe, waktu, item, nominal, footer ucapan terima kasih).

---

## Fungsi 7: Update Stok Cepat oleh Kasir
### (1) Tabel Input
`menus`

### (2) Tabel Output
`menus` (`stock = :stock`, `is_available = :is_available`)

### (3) Layar
`resources/views/kasir/index.blade.php` (Modal Manajemen Stok Cepat)

### (4) Query Utama
```php
$menu = Menu::findOrFail($request->menu_id);
$menu->update([
    'stock' => $request->stock,
    'is_available' => $request->stock > 0 ? $request->is_available : false,
]);
```

### (5) Function Key / Object pada Layar
- Tombol ikon `btn-quick-stock` pada kartu menu kasir
- Input angka `stock`
- Switch / Checkbox `is_available`
- Tombol `btn-save-stock` ("Simpan Stok")

### (6) Layar Pesan
- "Stok menu ':name' berhasil diperbarui menjadi :stock porsi."
- "Jumlah stok harus berupa bilangan bulat positif."
- "Gagal memperbarui stok: :message"

### (7) Proses / Algoritma
1. Kasir melihat persediaan fisik bahan suatu menu habis di dapur kafe.
2. Kasir mengklik tombol stok cepat pada antarmuka kasir tanpa perlu membuka panel admin.
3. Kasir menginput kuantitas sisa stok baru atau menonaktifkan saklar ketersediaan.
4. Sistem mengirimkan request POST ke `/kasir/stok`.
5. Server memperbarui atribut `stock` dan `is_available` di tabel `menus`.
6. Tampilan katalog kasir dan katalog publik pelanggan langsung tersinkronisasi menampilkan status habis (*Sold Out*).

### (8) Report
Pembaruan lencana status stok menu di layar kasir dan web publik.

---

## Fungsi 8: Pelacakan GPS Kurir Zendo & Anti-Fake GPS
### (1) Tabel Input
`zendo_deliveries`, `orders`, `settings`

### (2) Tabel Output
`zendo_deliveries` (`courier_lat`, `courier_lng`, `route_trail`, `status`, `gps_suspicious_count`)

### (3) Layar
`resources/views/zendo/tracking.blade.php` (Peta Interaktif Leaflet.js Layar Penuh)

### (4) Query Utama
```php
$delivery = ZendoDelivery::where('courier_token', $token)->firstOrFail();
$delivery->update([
    'courier_lat' => $lat,
    'courier_lng' => $lng,
    'route_trail' => $updatedTrail,
    'last_location_at' => Carbon::now(),
]);
```

### (5) Function Key / Object pada Layar
- Modal Verifikasi PIN Kurir (Input 4-6 digit PIN)
- Tombol `btn-bind-device` ("Mulai Pengantaran & Ikat Perangkat")
- Peta interaktif Leaflet.js (Marker Kafe, Marker Pelanggan, Marker Kurir)
- Garis rute lintasan (*Polyline Route Trail*)
- Tombol status kurir ("Menuju Lokasi Pelanggan" -> "Pesanan Telah Tiba")

### (6) Layar Pesan
- "Perangkat kurir berhasil diverifikasi dan terikat ke pesanan ini."
- "Sinyal GPS lemah atau koordinat tidak valid (Akurasi > 100m)."
- "Kecepatan terdeteksi tidak wajar (Anti-Fake GPS Terpicu)."
- "Status pengantaran berhasil diperbarui menjadi :status."

### (7) Proses / Algoritma
1. Kurir membuka tautan pelacakan pengantaran di smartphone.
2. Kurir memasukkan PIN rahasia untuk membuka kontrol kurir dan mengikat sidik jari browser (`is_device_bound = 1`).
3. Peramban kurir meminta izin sensor Geolocation API dan memulai streaming berkala koordinat lintang, bujur, akurasi, dan kecepatan.
4. Server mengevaluasi parameter Anti-Fake GPS: jika akurasi > 100m atau kecepatan > 100, record dicurigai dan peringatan dikembalikan.
5. Jika valid, koordinat kurir disimpan dan ditambahkan ke array JSON `route_trail`.
6. Pelanggan yang membuka halaman lacak yang sama dapat melihat pergerakan kurir secara real-time di atas peta Leaflet.js.
7. Saat tiba di lokasi, kurir menekan tombol 'Pesanan Diterima', mengubah status menjadi `delivered`.

### (8) Report
Visualisasi peta live tracking Leaflet.js dan riwayat lintasan pengantaran.

---

## Fungsi 9: Laporan Penjualan (PDF 1.4 Native & CSV Excel)
### (1) Tabel Input
`orders`, `order_items`, `settings`

### (2) Tabel Output
None (Stream Dokumen Biner PDF & File CSV)

### (3) Layar
`resources/views/admin/orders/index.blade.php` (Filter & Tombol Ekspor)

### (4) Query Utama
```php
$orders = Order::with('items')
    ->whereBetween('created_at', [$startDate, $endDate])
    ->where('payment_status', 'paid')
    ->orderByDesc('created_at')
    ->get();
```

### (5) Function Key / Object pada Layar
- Input `start_date` dan `end_date` (Rentang tanggal laporan)
- Dropdown `payment_status` (Semua, Lunas, Menunggu)
- Tombol `btn-export-pdf` ("Unduh Laporan PDF")
- Tombol `btn-export-csv` ("Unduh Format CSV / Excel")

### (6) Layar Pesan
- "Rentang tanggal laporan tidak valid."
- "Mengunduh berkas laporan rekapitulasi penjualan..."
- "Tidak ada data transaksi pada rentang tanggal yang dipilih."

### (7) Proses / Algoritma
1. Admin membuka halaman manajemen pesanan di `/admin/pesanan`.
2. Admin menentukan filter rentang tanggal dan status transaksi yang ingin direkap.
3. Admin mengklik tombol 'Ekspor Rekap PDF' atau 'Ekspor Rekap CSV'.
4. Server mengkueri data transaksi lunas pada tabel `orders` dan menghitung akumulasi omzet.
5. Jika format PDF: generator `TransactionReportPdfGenerator` merakit struktur biner PDF 1.4 murni (tanpa ketergantungan dompdf/wkhtmltopdf eksternal).
6. Jika format CSV: server merakit baris data dengan header teks ber-encoding UTF-8 BOM untuk kompatibilitas penuh Microsoft Excel.
7. Berkas langsung dialirkan (*streamed download*) ke peramban admin.

### (8) Report
Dokumen resmi Laporan Rekapitulasi Penjualan Nucomu Cafe (PDF biner atau spreadsheet CSV/Excel).

---

## Fungsi 10: Kelola Menu (CRUD dan Soft Delete)
### (1) Tabel Input
`categories`, `menus`, `menu_options`

### (2) Tabel Output
`menus`, `menu_options`

### (3) Layar
- Daftar: `resources/views/admin/menus/index.blade.php`<br>- Form: `resources/views/admin/menus/form.blade.php`

### (4) Query Utama
```php
// Tambah / Update Menu
$menu = Menu::updateOrCreate(['id' => $id], $validatedData);

// Soft Delete
$menu->delete(); // sets deleted_at = now()

// Restore
Menu::onlyTrashed()->findOrFail($id)->restore();

// Force Delete
if ($menu->orderItems()->count() == 0) {
    $menu->forceDelete();
}
```

### (5) Function Key / Object pada Layar
- Input `name`, `category_id`, `price`, `stock`, `badge`, `description`
- Upload berkas `image` (Foto 1:1)
- Repeater baris varian menu (Nama varian & selisih harga)
- Tombol `btn-save-menu` ("Simpan Menu")
- Tombol `btn-edit-menu`
- Tombol `btn-delete-menu` (Memicu konfirmasi soft delete)
- Tombol `btn-restore-menu` (Memulihkan menu di tab tong sampah)
- Tombol `btn-force-delete` (Hapus permanen)

### (6) Layar Pesan
- "Menu sajian baru berhasil ditambahkan."
- "Perubahan menu berhasil disimpan."
- "Menu berhasil dihapus sementara (Soft Delete)."
- "Menu berhasil dipulihkan kembali ke katalog aktif."
- "Menu tidak dapat dihapus permanen karena memiliki riwayat transaksi di kasir. Silakan gunakan soft delete." (Proteksi relasi)

### (7) Proses / Algoritma
1. Admin membuka menu 'Katalog Menu' di panel admin.
2. Admin mengisi form penambahan menu beserta upload foto makanan/minuman berasio 1:1.
3. Server memvalidasi keabsahan data dan menyimpan berkas foto ke `public/uploads/menus/`.
4. Record tersimpan di tabel `menus` beserta rincian variasinya di `menu_options`.
5. Jika admin mengklik hapus: sistem mengeksekusi soft delete (`deleted_at = now()`), sehingga menu hilang dari katalog publik tetapi data historis laporan kasir tetap utuh.
6. Admin dapat memulihkan menu melalui tombol 'Restore' atau menghapus permanen (*Force Delete*) hanya jika menu tersebut belum pernah dipesan pelanggan sama sekali.

### (8) Report
Katalog menu terbarui di beranda publik dan layar kasir POS.

---
