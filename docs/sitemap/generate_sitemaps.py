import os
from PIL import Image, ImageDraw, ImageFont

def render_sitemap(data, output_path, scale=2):
    num_cols = len(data['columns'])
    col_w = data.get('col_w', 232)
    col_gap = data.get('col_gap', 20)
    margin_x = data.get('margin_x', 50)
    margin_top = 36
    header_h = 66
    divider_y = margin_top + header_h + 10
    
    root_w = data.get('root_w', 230)
    root_h = 44
    root_y = divider_y + 26
    
    crossbar_y = root_y + root_h + 24
    col_header_y = crossbar_y + 22
    col_header_h = 42
    
    card_start_y = col_header_y + col_header_h + 18
    card_h = 52
    card_gap = 10
    trunk_inset = 9
    branch_gap = 15
    
    max_cards = max(len(col['cards']) for col in data['columns'])
    total_w = margin_x * 2 + num_cols * col_w + (num_cols - 1) * col_gap
    total_h = card_start_y + max_cards * card_h + (max_cards - 1) * card_gap + 45
    
    # 2x supersampled dimensions
    w_2x = int(total_w * scale)
    h_2x = int(total_h * scale)
    
    # Soft, premium canvas background matching reference image (#F8F9FA)
    canvas_bg = (248, 249, 251)
    img = Image.new('RGB', (w_2x, h_2x), canvas_bg)
    draw = ImageDraw.Draw(img)
    
    def s(val):
        return int(val * scale)
    
    # Load Segoe UI Fonts
    bold_path = "C:/Windows/Fonts/segoeuib.ttf"
    reg_path = "C:/Windows/Fonts/segoeui.ttf"
    semib_path = "C:/Windows/Fonts/seguisb.ttf"
    if not os.path.exists(semib_path):
        semib_path = bold_path
        
    font_main_title = ImageFont.truetype(bold_path, s(25))
    font_subtitle = ImageFont.truetype(reg_path, s(13.5))
    font_badge_title = ImageFont.truetype(semib_path, s(12))
    font_badge_sub = ImageFont.truetype(reg_path, s(10.5))
    
    font_root = ImageFont.truetype(bold_path, s(14.5))
    font_col_header = ImageFont.truetype(bold_path, s(13))
    font_card_title = ImageFont.truetype(bold_path, s(11.5))
    font_card_sub = ImageFont.truetype(reg_path, s(10))
    
    # Colors matching reference image exactly
    c_text_dark = (32, 34, 38)
    c_text_muted = (108, 112, 122)
    c_line = (50, 53, 60)
    c_tree_line = (68, 72, 80)
    c_root_bg = (40, 42, 46)
    c_col_bg = (216, 218, 223)
    c_card_bg = (255, 255, 255)
    c_card_border = (222, 225, 230)
    c_divider = (224, 227, 232)
    c_badge_bg = (229, 232, 237)
    
    # 1. Header (Left: Title & Subtitle)
    draw.text((s(margin_x), s(margin_top + 2)), data['title'], fill=c_text_dark, font=font_main_title)
    draw.text((s(margin_x), s(margin_top + 38)), data['subtitle'], fill=c_text_muted, font=font_subtitle)
    
    # 2. Header (Right: Badge Pill)
    badge_w = 200
    badge_h = 44
    badge_x0 = total_w - margin_x - badge_w
    badge_y0 = margin_top + 8
    draw.rounded_rectangle((s(badge_x0), s(badge_y0), s(badge_x0 + badge_w), s(badge_y0 + badge_h)),
                           radius=s(12), fill=c_badge_bg, outline=c_card_border, width=s(1))
    draw.text((s(badge_x0 + badge_w / 2), s(badge_y0 + 13)), data['badge_title'],
              fill=c_text_dark, font=font_badge_title, anchor='mm')
    draw.text((s(badge_x0 + badge_w / 2), s(badge_y0 + 29)), data['badge_sub'],
              fill=c_text_muted, font=font_badge_sub, anchor='mm')
    
    # 3. Horizontal Header Divider
    draw.line([(s(margin_x), s(divider_y)), (s(total_w - margin_x), s(divider_y))],
              fill=c_divider, width=s(1))
    
    # 4. Root Node (Center)
    root_x0 = (total_w - root_w) / 2
    draw.rounded_rectangle((s(root_x0), s(root_y), s(root_x0 + root_w), s(root_y + root_h)),
                           radius=s(8), fill=c_root_bg)
    draw.text((s(total_w / 2), s(root_y + root_h / 2)), data['root_title'],
              fill=(255, 255, 255), font=font_root, anchor='mm')
    
    # 5. Connectors from Root Node to Column Headers
    root_bottom_center = (s(total_w / 2), s(root_y + root_h))
    # Vertical trunk down to crossbar
    draw.line([root_bottom_center, (s(total_w / 2), s(crossbar_y))], fill=c_line, width=s(1.5))
    
    # Calculate column centers
    col_centers = []
    for i in range(num_cols):
        cx = margin_x + i * (col_w + col_gap) + col_w / 2
        col_centers.append(cx)
        
    # Horizontal crossbar
    draw.line([(s(col_centers[0]), s(crossbar_y)), (s(col_centers[-1]), s(crossbar_y))],
              fill=c_line, width=s(1.5))
    
    # Vertical drops from crossbar down to each column header top-center
    for cx in col_centers:
        draw.line([(s(cx), s(crossbar_y)), (s(cx), s(col_header_y))], fill=c_line, width=s(1.5))
        
    # 6. Render Columns and Sub-Cards
    for i, col in enumerate(data['columns']):
        col_x0 = margin_x + i * (col_w + col_gap)
        col_x1 = col_x0 + col_w
        
        # Column Header Box
        draw.rounded_rectangle((s(col_x0), s(col_header_y), s(col_x1), s(col_header_y + col_header_h)),
                               radius=s(8), fill=c_col_bg)
        draw.text((s(col_x0 + col_w / 2), s(col_header_y + col_header_h / 2)), col['title'],
                  fill=c_text_dark, font=font_col_header, anchor='mm')
        
        # Tree connectors for this column
        trunk_x = col_x0 + trunk_inset
        cards = col['cards']
        if not cards:
            continue
            
        last_card_branch_y = card_start_y + (len(cards) - 1) * (card_h + card_gap) + card_h / 2
        
        # Vertical trunk starts at bottom of column header and stops at the last card's horizontal branch
        draw.line([(s(trunk_x), s(col_header_y + col_header_h)), (s(trunk_x), s(last_card_branch_y))],
                  fill=c_tree_line, width=s(1.5))
        
        # Cards
        card_x0 = trunk_x + branch_gap
        card_x1 = col_x1 # Card right edge aligns flush with column header right edge
        
        for j, card in enumerate(cards):
            cy0 = card_start_y + j * (card_h + card_gap)
            cy1 = cy0 + card_h
            branch_y = cy0 + card_h / 2
            
            # Horizontal branch from trunk into the left edge of the card
            draw.line([(s(trunk_x), s(branch_y)), (s(card_x0), s(branch_y))],
                      fill=c_tree_line, width=s(1.5))
            
            # Card Rounded Box (Pure white, clean outline)
            draw.rounded_rectangle((s(card_x0), s(cy0), s(card_x1), s(cy1)),
                                   radius=s(7), fill=c_card_bg, outline=c_card_border, width=s(1.2))
            
            # Card Text (Title & Subtitle with nice internal padding)
            pad_left = 11
            draw.text((s(card_x0 + pad_left), s(cy0 + 9)), card['title'],
                      fill=c_text_dark, font=font_card_title)
            draw.text((s(card_x0 + pad_left), s(cy0 + 28)), f"({card['desc']})",
                      fill=c_text_muted, font=font_card_sub)

    # Output crisp image (scaling down by Lanczos for anti-aliasing)
    final_w = int(total_w * (scale / 1.333333)) # ~2200px width, crisp Retina/Print grade
    final_h = int(total_h * (scale / 1.333333))
    final_img = img.resize((final_w, final_h), Image.Resampling.LANCZOS)
    
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    final_img.save(output_path, "PNG", dpi=(300, 300), optimize=True)
    print(f"Saved: {output_path} ({final_w}x{final_h})")

if __name__ == '__main__':
    admin_data = {
        'title': 'Sitemap Panel Admin Nucomu Cafe',
        'subtitle': 'Gambaran struktur hierarki navigasi dan modul manajemen backend Nucomu Cafe.',
        'badge_title': 'Struktur Sistem Aktif',
        'badge_sub': '(berdasarkan panel admin)',
        'root_title': 'Panel Admin Nucomu',
        'root_w': 240,
        'col_w': 234,
        'col_gap': 18,
        'margin_x': 45,
        'columns': [
            {
                'title': 'Dashboard',
                'cards': [
                    {'title': 'Ringkasan Metrik', 'desc': 'omset harian, total order, reservasi baru'},
                    {'title': 'Grafik Penjualan', 'desc': 'tren omset 7 hari & komparasi performa'},
                    {'title': 'Status Operasional', 'desc': 'status kasir aktif, kuota meja, jam buka'},
                    {'title': 'Aktivitas Transaksi', 'desc': 'antrean pesanan masuk real-time'},
                ]
            },
            {
                'title': 'Konten & Tampilan',
                'cards': [
                    {'title': 'Banner Hero', 'desc': 'slider foto/video, status aktif, preview desain'},
                    {'title': 'Tentang Cafe', 'desc': 'cerita profil, video room tour 3D, filosofi'},
                    {'title': 'Fasilitas Cafe', 'desc': 'indoor, WiFi, AC, mushola, reorder urutan'},
                    {'title': 'Galeri Dokumentasi', 'desc': 'foto interior, produk & suasana, toggle aktif'},
                    {'title': 'Testimoni & Ulasan', 'desc': 'sinkron Google Maps, pin ulasan terbaik'},
                ]
            },
            {
                'title': 'Katalog Menu',
                'cards': [
                    {'title': 'Kategori Produk', 'desc': 'kopi, non-kopi, makanan berat, snack'},
                    {'title': 'Daftar Menu', 'desc': 'nama item, harga jual, foto produk, deskripsi'},
                    {'title': 'Varian & Opsi', 'desc': 'suhu panas/dingin, level gula, topping'},
                    {'title': 'Kontrol Ketersediaan', 'desc': 'toggle stok habis, menu featured / best seller'},
                    {'title': 'Riwayat & Restore', 'desc': 'restore menu terhapus & log perubahan'},
                ]
            },
            {
                'title': 'Transaksi & Kasir',
                'cards': [
                    {'title': 'Daftar Pesanan Masuk', 'desc': 'antrean order, filter tanggal & status bayar'},
                    {'title': 'Detail & Verifikasi', 'desc': 'rincian item pesanan, nomor meja, pelanggan'},
                    {'title': 'Konfirmasi Pembayaran', 'desc': 'update status lunas: Tunai Cash / QRIS'},
                    {'title': 'Scan Slip QR Digital', 'desc': 'scanner kamera QR code pesanan dari /pesan'},
                    {'title': 'Rekap & Cetak Struk', 'desc': 'export laporan Excel/CSV & cetak nota'},
                ]
            },
            {
                'title': 'Reservasi Meja',
                'cards': [
                    {'title': 'Antrean Reservasi', 'desc': 'jadwal booking meja, jumlah tamu, sesi jam'},
                    {'title': 'Approval Status', 'desc': 'approval status pending, confirmed, cancel'},
                    {'title': 'Form Builder Dinamis', 'desc': 'tambah, edit & kelola field kustom form'},
                    {'title': 'Integrasi WhatsApp', 'desc': 'notifikasi konfirmasi booking ke pelanggan'},
                ]
            },
            {
                'title': 'Logistik & Sistem',
                'cards': [
                    {'title': 'Monitoring Kurir Zendo', 'desc': 'live tracking GPS kurir & penugasan order'},
                    {'title': 'Perangkat Kurir & PIN', 'desc': 'verifikasi PIN & reset token perangkat kurir'},
                    {'title': 'Profil Admin', 'desc': 'keamanan akun, update kata sandi, avatar'},
                    {'title': 'Pusat Notifikasi', 'desc': 'polling order baru & pengingat banner/stok'},
                    {'title': 'Pengaturan Kafe', 'desc': 'jam buka-tutup, kontak, integrasi GrabFood/Maps'},
                ]
            }
        ]
    }
    
    kasir_data = {
        'title': 'Sitemap Kasir POS Nucomu Cafe',
        'subtitle': 'Gambaran alur kerja dan fitur sistem Point-of-Sale (POS) kasir Nucomu Cafe.',
        'badge_title': 'Struktur Operasional Kasir',
        'badge_sub': '(berdasarkan workspace /kasir)',
        'root_title': 'POS Kasir Nucomu',
        'root_w': 220,
        'col_w': 260,
        'col_gap': 22,
        'margin_x': 50,
        'columns': [
            {
                'title': 'Autentikasi & Sesi',
                'cards': [
                    {'title': 'Login PIN Kasir', 'desc': 'otentikasi cepat via nomor PIN / password kasir'},
                    {'title': 'Sesi Persisten', 'desc': 'cookie aman perangkat kasir aktif 1 tahun'},
                    {'title': 'Identitas Kasir', 'desc': 'profil kasir bertugas & proteksi multi-perangkat'},
                    {'title': 'Kunci Sesi & Logout', 'desc': 'keluar sesi atau pergantian shift jaga'},
                ]
            },
            {
                'title': 'Katalog Menu POS',
                'cards': [
                    {'title': 'Filter Kategori', 'desc': 'semua menu, kopi signature, non-kopi, makanan'},
                    {'title': 'Pencarian Instan', 'desc': 'cari menu cepat tanpa reload halaman'},
                    {'title': 'Kustomisasi Varian', 'desc': 'pilih suhu ice/hot, level gula, catatan koki'},
                    {'title': 'Badge Stok Fisik', 'desc': 'indikator sisa porsi & penanda sold out'},
                ]
            },
            {
                'title': 'Keranjang & Pesanan',
                'cards': [
                    {'title': 'Keranjang Interaktif', 'desc': 'tambah item, kelola kuantitas +/-, hapus baris'},
                    {'title': 'Mode Transaksi', 'desc': 'pilih Dine-in makan di tempat atau Take Away'},
                    {'title': 'Penetapan Meja', 'desc': 'pemilihan nomor meja aktif dari daftar meja 1-15'},
                    {'title': 'Data Pelanggan', 'desc': 'input nama pemesan & instruksi khusus'},
                ]
            },
            {
                'title': 'Validasi & Pembayaran',
                'cards': [
                    {'title': 'Scan Slip QR Digital', 'desc': 'kamera scanner slip QR pesanan dari /pesan'},
                    {'title': 'Lookup Kode NCM', 'desc': 'input manual pencarian kode pesanan pelanggan'},
                    {'title': 'Kalkulasi Transaksi', 'desc': 'subtotal otomatis, hitung kembalian tunai'},
                    {'title': 'Pelunasan Bayar', 'desc': 'konfirmasi transaksi lunas Tunai Cash / QRIS'},
                ]
            },
            {
                'title': 'Operasional & Cetak',
                'cards': [
                    {'title': 'Cetak Struk Thermal', 'desc': 'cetak nota pesanan 58mm / 80mm printer kasir'},
                    {'title': 'Kontrol Cepat Stok', 'desc': 'modal ubah status & sisa stok fisik di layar'},
                    {'title': 'Sinkronisasi Otomatis', 'desc': 'background polling data pesanan & stok real-time'},
                    {'title': 'Pintasan Panel Admin', 'desc': 'shortcut cepat membuka portal admin backend'},
                ]
            }
        ]
    }
    
    render_sitemap(admin_data, "docs/sitemap/sitemap_admin.png")
    render_sitemap(kasir_data, "docs/sitemap/sitemap_kasir.png")
