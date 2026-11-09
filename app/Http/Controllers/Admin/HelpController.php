<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    /**
     * Display the admin user manual, recommended photo guidelines, and FAQs.
     */
    public function index()
    {
        $modules = [
            [
                'id' => 'banner',
                'name' => '1. Hero Section & Banner Promo',
                'icon' => 'fa-solid fa-bullhorn',
                'summary' => 'Kelola banner promo popup/carousel dengan hitung mundur durasi otomatis.',
                'steps' => [
                    'Klik tombol "+ Tambah Banner" pada halaman Banner Promo.',
                    'Isi judul, badge promo (misal: #SpesialSore), dan upload banner foto 16:9.',
                    'Pilih opsi durasi (1 jam, 6 jam, 1 hari, 3 hari, atau custom jam:menit).',
                    'Banner otomatis tayang di beranda dan otomatis kedaluwarsa jika timer habis.',
                    'Gunakan tombol "Perpanjang" kapan saja jika ingin memperpanjang masa tayang.',
                ],
                'photo_guide' => 'Rasio 16:9 atau 21:9 (rekomendasi 1920x1080 px atau 1200x675 px), format JPG/PNG/WebP, ukuran maksimal 2 MB.',
            ],
            [
                'id' => 'about',
                'name' => '2. About / Kisah Kami',
                'icon' => 'fa-solid fa-book-open',
                'summary' => 'Kelola narasi kafe, kutipan, dan video/foto cerita beranda.',
                'steps' => [
                    'Buka menu About di admin panel.',
                    'Sesuaikan Headline Cerita, Paragraf 1 (Filosofi), dan Paragraf 2 (Suasana Kafe).',
                    'Atur teks dan tautan tombol (default: #menu).',
                    'Pilih media: Unggah video suasana MP4 (maks 50 MB) atau gunakan foto cover 16:9.',
                    'Klik "Simpan Perubahan Kisah Kafe" untuk memperbarui tampilan di web customer.',
                ],
                'photo_guide' => 'Foto: Rasio 16:9 (1200x800 px, maks 5 MB). Video: Format MP4, maks 50 MB.',
            ],
            [
                'id' => 'facilities',
                'name' => '3. Fasilitas & Layanan',
                'icon' => 'fa-solid fa-wifi',
                'summary' => 'Daftar fasilitas penunjang kafe dan foto slider pendukung.',
                'steps' => [
                    'Buka menu Fasilitas.',
                    'Tambah fasilitas baru dengan mengisi nama, icon FontAwesome/Material, dan deskripsi singkat.',
                    'Unggah slide foto untuk fasilitas terkait.',
                    'Gunakan tombol toggle ON/OFF untuk menampilkan atau menyembunyikan fasilitas.',
                    'Urutkan posisi kartu dengan tombol urutan.',
                ],
                'photo_guide' => 'Rasio 16:9 atau 4:3 (1200x800 px), JPG/PNG/WebP, maksimal 2 MB.',
            ],
            [
                'id' => 'menus',
                'name' => '4. Kategori & Menu',
                'icon' => 'fa-solid fa-mug-hot',
                'summary' => 'Struktur menu kafe, kategori produk, harga, dan ketersediaan stok.',
                'steps' => [
                    'Buat kategori terlebih dahulu lewat tombol "+ Tambah Kategori" (misal: Coffee, Non-Coffee, Meals, Dessert).',
                    'Tambahkan menu makanan/minuman dengan memilih kategori yang sesuai.',
                    'Unggah foto menu wajib rasio persegi 1:1 (800x800 px, maks 1 MB).',
                    'Atur toggle stok "Tersedia" atau "Habis" jika persediaan sedang kosong.',
                    'Kategori yang masih memuat menu aktif diproteksi dari penghapusan demi keamanan data.',
                ],
                'photo_guide' => 'Wajib rasio 1:1 persegi (rekomendasi 800x800 px), JPG/PNG/WebP, ukuran maksimal 1 MB.',
            ],
            [
                'id' => 'gallery',
                'name' => '5. Gallery & Visual Feed',
                'icon' => 'fa-solid fa-images',
                'summary' => 'Koleksi foto suasana kafe, seduhan kopi, dan visual instagramable.',
                'steps' => [
                    'Buka menu Gallery.',
                    'Setiap foto WAJIB diisi judul/caption foto agar rapi dan informatif.',
                    'Unggah foto suasana kafe (bisa memilih rasio 1:1 atau 4:3).',
                    'Aktifkan atau nonaktifkan foto lewat toggle status.',
                    'Foto yang dihapus otomatis terhapus dari server penyimpanan.',
                ],
                'photo_guide' => 'Rasio 1:1 (1080x1080 px) atau 4:3 (1200x900 px), format JPG/PNG/WebP, maksimal 2 MB.',
            ],
            [
                'id' => 'testimonials',
                'name' => '6. Ulasan & Testimoni',
                'icon' => 'fa-solid fa-star',
                'summary' => 'Sistem kurasi ulasan pelanggan Google Maps & website dengan fitur Pin.',
                'steps' => [
                    'Admin berperan sebagai kurator: ulasan baru dari form web masuk tanpa status pin.',
                    'Klik tombol "Pin Ulasan" pada ulasan terbaik agar tayang di beranda website.',
                    'Gunakan filter (Semua / Sudah Di-Pin / Belum Di-Pin) untuk melihat ulasan.',
                    'Jika ada ulasan spam atau tidak pantas, admin dapat menghapusnya dengan konfirmasi.',
                ],
                'photo_guide' => 'Foto profil pengulas disarankan rasio 1:1 bulat/persegi, maks 1 MB.',
            ],
            [
                'id' => 'reservations',
                'name' => '7. Reservasi & Form Builder',
                'icon' => 'fa-solid fa-calendar-check',
                'summary' => 'Pengaturan formulir reservasi kustom dan penerusan pesan ke WhatsApp tujuan.',
                'steps' => [
                    'Buka menu Reservasi Meja.',
                    'Gunakan Form Builder untuk menambah, menyusun ulang, atau menghapus kolom formulir.',
                    'Tentukan tipe kolom: teks, angka, tanggal, jam, upload foto, atau checkbox pilihan.',
                    'Saat customer mengirim formulir, data tersimpan rapi di database dan WhatsApp otomatis terbuka.',
                    'Nomor WhatsApp tujuan reservasi dapat disesuaikan sewaktu-waktu di form pengaturan.',
                ],
                'photo_guide' => 'Foto bukti/lampiran customer: JPG/PNG/WebP, maksimal 2 MB.',
            ],
            [
                'id' => 'orders',
                'name' => '8. Transaksi & Kasir',
                'icon' => 'fa-solid fa-receipt',
                'summary' => 'Manajemen transaksi kafe: makan di tempat (kasir), kurir Zendo tracking, dan chat WhatsApp.',
                'steps' => [
                    'Atur 3 cara transaksi melalui saklar ON/OFF: Makan di Cafe, Antar via Zendo (Pelacakan), atau Hanya Chat Zendo.',
                    'Pelacakan kurir dan chat Zendo saling mengunci — memilih salah satu otomatis mematikan yang lain.',
                    'Untuk makan di kafe, aktifkan opsi pembayaran Tunai atau QRIS dan unggah gambar kode QRIS kafe.',
                    'Gunakan menu Buka Scanner QR untuk memindai slip barcode pesanan kasir pelanggan.',
                ],
                'photo_guide' => 'QRIS Kafe: Format PNG/JPG rasio persegi 1:1 resolusi tajam, maks 2 MB.',
            ],
            [
                'id' => 'contact',
                'name' => '9. Kontak & Media Sosial',
                'icon' => 'fa-solid fa-address-book',
                'summary' => 'Pusat pengaturan rute Google Maps, nomor WhatsApp kafe, dan tautan sosial media.',
                'steps' => [
                    'Isi nomor WhatsApp Cafe Resmi (otomatis dinormalisasi ke format 628xxx).',
                    'Tautan GrabFood dan nomor kurir Zendo otomatis tersinkronisasi di halaman informasi Kontak.',
                    'Masukkan link Google Maps atau kode embed iframe agar peta interaktif tampil di web.',
                    'Jika link Instagram atau TikTok dikosongkan, ikonnya otomatis disembunyikan di website.',
                ],
                'photo_guide' => 'Logo kafe untuk maps/footer: PNG transparan resolusi tinggi (maks 2 MB).',
            ],
        ];

        $faqs = [
            [
                'q' => 'Mengapa banner promo otomatis hilang dari beranda website?',
                'a' => 'Banner promo di Nucomu Cafe dilengkapi sistem durasi aktif hitung mundur (countdown). Begitu waktu durasi habis, status banner otomatis menjadi kedaluwarsa dan tidak lagi ditampilkan di website agar informasi promo tetap akurat. Anda dapat memperpanjang masa tayang banner kapan saja lewat menu Hero & Banner.',
            ],
            [
                'q' => 'Bagaimana cara mengganti nomor WhatsApp resmi kafe untuk reservasi & kontak?',
                'a' => 'Buka menu Kontak (/admin/contact), masukkan nomor baru pada kolom "Nomor WhatsApp Cafe Resmi" (contoh: 081234567890), lalu klik Simpan. Sistem otomatis menormalisasi nomor ke format internasional 628xxx dan seluruh tombol WhatsApp di website langsung aktif menggunakan nomor baru.',
            ],
            [
                'q' => 'Mengapa ulasan baru dari pelanggan tidak langsung muncul di beranda web?',
                'a' => 'Untuk mencegah spam atau konten tidak pantas, seluruh ulasan yang dikirim lewat formulir web masuk dengan status belum di-pin. Buka menu Ulasan (/admin/testimonials), baca isi ulasan, dan klik tombol "Pin Ulasan" jika ingin menampilkannya di beranda website.',
            ],
            [
                'q' => 'Apakah mengubah link GrabFood atau nomor Zendo di menu Kontak akan mempengaruhi modul Transaksi?',
                'a' => 'Ya, kedua modul tersebut memakai basis data yang sama persis tanpa duplikasi. Mengubah link GrabFood atau nomor Zendo di menu Kontak otomatis memperbarui tombol pemesanan di menu dan keranjang, begitu pula sebaliknya.',
            ],
            [
                'q' => 'Apa yang terjadi jika saya menghapus kategori yang masih memiliki menu?',
                'a' => 'Sistem akan menolak penghapusan dan memberikan peringatan bahasa Indonesia agar menu di dalam kategori tersebut dipindahkan atau dihapus terlebih dahulu. Hal ini menjaga integritas data menu di aplikasi kasir dan website pelanggan.',
            ],
            [
                'q' => 'Berapa batasan ukuran file foto yang dapat diunggah?',
                'a' => 'Standar ukuran berkas foto di panel admin adalah maksimal 2 MB (untuk banner, galeri, fasilitas, dan profil admin) serta maksimal 1 MB untuk foto menu makanan/minuman dengan format yang didukung JPG, JPEG, PNG, dan WebP.',
            ],
        ];

        return view('admin.help.index', compact('modules', 'faqs'));
    }
}
