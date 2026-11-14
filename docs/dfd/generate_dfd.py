import os
import matplotlib.pyplot as plt
import matplotlib.patches as patches
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.section import WD_SECTION, WD_ORIENTATION

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

# ─────────────────────────────────────────────────────────────
#  COMMON DRAWING HELPERS
# ─────────────────────────────────────────────────────────────

def setup_canvas(title, subtitle):
    fig, ax = plt.subplots(figsize=(14, 8.6), dpi=300)
    fig.patch.set_facecolor('#FFFFFF')
    ax.set_facecolor('#FFFFFF')
    ax.set_xlim(0, 14)
    ax.set_ylim(0, 8.6)
    ax.axis('off')

    # Header
    ax.text(7.0, 8.16, title, ha='center', va='center',
            fontsize=17, fontweight='bold', color='#142846', fontfamily='Arial')
    ax.text(7.0, 7.82, subtitle, ha='center', va='center',
            fontsize=10, color='#5a6978', fontfamily='Arial')
    ax.plot([0.6, 13.4], [7.56, 7.56], color='#dce2e8', lw=1.2)

    return fig, ax

def draw_entity(ax, x, y, w, h, text, fontsize=11):
    rect = patches.Rectangle((x, y), w, h, facecolor='#FFFFFF',
                             edgecolor='#5a5a5a', linewidth=1.7, zorder=3)
    ax.add_patch(rect)
    ax.text(x + w / 2, y + h / 2, text, ha='center', va='center',
            fontsize=fontsize, color='#140E0A', fontweight='bold',
            fontfamily='Arial', zorder=4, multialignment='center')

def draw_process(ax, x, y, w, h, text, fontsize=10.5):
    rect = patches.Rectangle((x, y), w, h, facecolor='#FFFFFF',
                             edgecolor='#5a5a5a', linewidth=1.5, zorder=3)
    ax.add_patch(rect)
    ax.text(x + w / 2, y + h / 2, text, ha='center', va='center',
            fontsize=fontsize, color='#140E0A', fontfamily='Arial',
            zorder=4, multialignment='center')

def draw_circle(ax, cx, cy, rx, ry, text, fontsize=12):
    ellipse = patches.Ellipse((cx, cy), rx * 2, ry * 2, facecolor='#FFFFFF',
                              edgecolor='#5a5a5a', linewidth=1.7, zorder=3)
    ax.add_patch(ellipse)
    ax.text(cx, cy, text, ha='center', va='center',
            fontsize=fontsize, color='#140E0A', fontweight='bold',
            fontfamily='Arial', zorder=4, multialignment='center')

def draw_datastore(ax, x1, x2, y, text, height=0.46, fontsize=9.0):
    ax.plot([x1, x2], [y + height / 2, y + height / 2], color='#2980b9', lw=2.8, zorder=3)
    ax.plot([x1, x2], [y - height / 2, y - height / 2], color='#2980b9', lw=2.8, zorder=3)
    ax.text((x1 + x2) / 2, y, text, ha='center', va='center',
            fontsize=fontsize, color='#140E0A', fontweight='bold',
            fontfamily='Arial', zorder=4, multialignment='center')

def draw_arrow(ax, start, end, label=None, label_pos=None, label_offset=(0, 0),
               color='#000000', lw=1.5, fontsize=9, arrowstyle='-|>', mutation_scale=14):
    arrow = patches.FancyArrowPatch(start, end, arrowstyle=arrowstyle,
                                   mutation_scale=mutation_scale, color=color,
                                   linewidth=lw, zorder=2)
    ax.add_patch(arrow)
    if label:
        if label_pos is None:
            lx = (start[0] + end[0]) / 2 + label_offset[0]
            ly = (start[1] + end[1]) / 2 + label_offset[1]
        else:
            lx, ly = label_pos[0] + label_offset[0], label_pos[1] + label_offset[1]
        ax.text(lx, ly, label, ha='center', va='center', fontsize=fontsize,
                color='#1c4e73', fontfamily='Arial', zorder=5,
                bbox=dict(boxstyle='square,pad=0.2', facecolor='#FFFFFF', edgecolor='none'))

def draw_polyline_arrow(ax, points, label=None, label_pos=None, label_offset=(0, 0),
                        color='#000000', lw=1.5, fontsize=9, mutation_scale=14):
    for i in range(len(points) - 2):
        ax.plot([points[i][0], points[i+1][0]], [points[i][1], points[i+1][1]],
                color=color, lw=lw, zorder=2)
    arrow = patches.FancyArrowPatch(points[-2], points[-1], arrowstyle='-|>',
                                   mutation_scale=mutation_scale, color=color,
                                   linewidth=lw, zorder=2)
    ax.add_patch(arrow)
    if label:
        if label_pos is None:
            mid_idx = len(points) // 2
            lx = points[mid_idx][0] + label_offset[0]
            ly = points[mid_idx][1] + label_offset[1]
        else:
            lx, ly = label_pos[0] + label_offset[0], label_pos[1] + label_offset[1]
        ax.text(lx, ly, label, ha='center', va='center', fontsize=fontsize,
                color='#1c4e73', fontfamily='Arial', zorder=5,
                bbox=dict(boxstyle='square,pad=0.2', facecolor='#FFFFFF', edgecolor='none'))


# ─────────────────────────────────────────────────────────────
#  1. DFD LEVEL 1
# ─────────────────────────────────────────────────────────────
def build_dfd_level_1():
    title = "DFD LEVEL 1 — SISTEM INFORMASI UMKM NUCOMU CAFE"
    subtitle = "Model Aliran Data Utama: Autentikasi Pengguna, Pemesanan Menu & Reservasi, Pelunasan Kasir POS, dan Logistik Kurir Zendo"
    fig, ax = setup_canvas(title, subtitle)

    # Entitas
    # Pelanggan (Kiri)
    draw_entity(ax, 0.45, 3.45, 1.85, 0.95, "Pelanggan", fontsize=12)
    # Admin, Kasir & Kurir Zendo (Kanan)
    draw_entity(ax, 9.40, 3.45, 2.45, 0.95, "Admin, Kasir &\nKurir Zendo", fontsize=11)

    # Lingkaran Pusat
    draw_circle(ax, 5.85, 3.92, 1.25, 1.25, "Sistem Aplikasi\nUMKM Nucomu\nCafe", fontsize=11.5)

    # Top Row Processes (Rantai Login dari Entitas Staf ke Kiri menuju Sistem)
    # Box 1: 1. Autentikasi & Akses Sistem
    draw_process(ax, 9.30, 6.05, 2.35, 0.85, "1. Autentikasi &\nAkses Sistem", fontsize=10.5)
    # Box 1.1: 1.1 Verifikasi Kredensial & PIN
    draw_process(ax, 6.30, 6.05, 2.25, 0.85, "1.1 Verifikasi\nKredensial & PIN", fontsize=10.5)
    # Box 1.2: 1.2 Penerbitan Sesi & Token
    draw_process(ax, 3.35, 6.05, 2.15, 0.85, "1.2 Penerbitan\nSesi & Token", fontsize=10.5)

    # Data Store Top (users & settings)
    draw_datastore(ax, 9.15, 11.80, 7.18, "users & settings (Data Akun, Role & PIN)", height=0.44, fontsize=9.0)
    ax.plot([10.47, 10.47], [6.96, 6.90], color='#2980b9', lw=1.5, zorder=2)

    # Panah Top Row:
    # Dari Entitas Kanan (Staf) naik ke Box 1
    draw_polyline_arrow(ax, [(10.62, 4.40), (10.62, 5.25), (10.62, 6.05)],
                        label="Kredensial & PIN Staf", label_pos=(10.62, 5.25), label_offset=(-1.15, 0))
    # Box 1 -> Box 1.1
    draw_arrow(ax, (9.30, 6.47), (8.55, 6.47), label="Data Kredensial", label_offset=(0, 0.16))
    # Box 1.1 -> Box 1.2
    draw_arrow(ax, (6.30, 6.47), (5.50, 6.47), label="Hasil Validasi", label_offset=(0, 0.16))
    # Box 1.2 turun ke Lingkaran Pusat
    draw_polyline_arrow(ax, [(4.42, 6.05), (4.42, 5.35), (5.85, 5.35), (5.85, 5.17)],
                        label="Hak Akses & Sesi Aktif", label_pos=(5.13, 5.35), label_offset=(0, 0.18))

    # Bottom Row Processes
    # Box 2: 2. Pemesanan & Reservasi
    draw_process(ax, 0.45, 1.25, 2.30, 0.85, "2. Pemesanan &\nReservasi", fontsize=10.5)
    # Box 3: 3. Operasional Kasir & Pengantaran Zendo
    draw_process(ax, 6.85, 1.25, 2.55, 0.85, "3. Operasional Kasir &\nPengantaran Zendo", fontsize=10.5)

    # Aliran Pelanggan -> Box 2 (line at x=0.80)
    draw_polyline_arrow(ax, [(0.80, 3.45), (0.80, 2.65), (0.80, 2.10)], label=None)
    # Box 2 -> Lingkaran Pusat (line starts at x=2.45, up to 2.85, then to 5.80)
    draw_polyline_arrow(ax, [(2.45, 2.10), (2.45, 2.85), (5.80, 2.85), (5.80, 2.67)],
                        label="Data Pemesanan & Draf WA", label_pos=(4.12, 2.85), label_offset=(0, 0.18))
    # Label for Pelanggan -> Box 2 centered neatly between x=0.80 and x=2.45
    ax.text(1.62, 2.60, "Pilih Menu &\nForm Reservasi", ha='center', va='center', fontsize=9,
            color='#1c4e73', fontfamily='Arial', zorder=5,
            bbox=dict(boxstyle='square,pad=0.2', facecolor='#FFFFFF', edgecolor='none'))

    # Box 2 -> Box 3
    draw_arrow(ax, (2.75, 1.67), (6.85, 1.67), label="Slip Tagihan QR & Data Order", label_offset=(0, 0.18))
    # Box 3 -> Entitas Kanan (Staf)
    draw_polyline_arrow(ax, [(9.40, 1.67), (10.35, 1.67), (10.35, 3.45)],
                        label="Konfirmasi Bayar &\nTugas Antar", label_pos=(10.35, 2.56), label_offset=(1.25, 0))

    # Data Store Bawah (posisi y disesuaikan agar margin bawah konsisten & lega):
    # 1. orders & order_items
    draw_datastore(ax, 5.70, 7.85, 0.45, "orders & order_items\n(Data Transaksi & Item)", height=0.46, fontsize=8.8)
    # 2. reservations, reservation_fields & zendo_deliveries (Revisi: masukkan reservation_fields)
    draw_datastore(ax, 8.25, 11.25, 0.45, "reservations, reservation_fields\n& zendo_deliveries\n(Reservasi, Kolom & Kurir GPS)", height=0.68, fontsize=8.0)
    
    # Cabang panah dari Box 3 ke Data Store Bawah
    draw_polyline_arrow(ax, [(7.70, 1.25), (7.70, 0.88), (6.77, 0.88), (6.77, 0.68)],
                        label=None, lw=1.4)
    draw_polyline_arrow(ax, [(8.45, 1.25), (8.45, 0.95), (9.75, 0.95), (9.75, 0.79)],
                        label=None, lw=1.4)

    # Data Store Kanan Jauh (Revisi: masukkan menu_options bersama menus, categories, tables)
    draw_datastore(ax, 11.90, 13.90, 3.92, "menus, categories,\nmenu_options & tables\n(Katalog, Opsi & Meja)", height=0.76, fontsize=8.0)
    draw_arrow(ax, (11.85, 3.92), (11.90, 3.92), color='#2980b9', lw=1.6)

    # Aliran antara Pelanggan dan Lingkaran Pusat
    # Top arrow: Lingkaran -> Pelanggan
    draw_arrow(ax, (4.60, 4.15), (2.30, 4.15),
               label="Slip QR, Struk & Tautan GrabFood", label_offset=(0, 0.20))
    # Bottom arrow: Pelanggan -> Lingkaran
    draw_arrow(ax, (2.30, 3.70), (4.60, 3.70),
               label="Rating & Ulasan Pengalaman", label_offset=(0, -0.20))

    # Aliran antara Lingkaran Pusat dan Entitas Staf
    # Top arrow: Lingkaran -> Staf
    draw_arrow(ax, (7.10, 4.15), (9.40, 4.15),
               label="Notifikasi Order &\nReservasi WA", label_offset=(0, 0.22))
    # Bottom arrow: Staf -> Lingkaran
    draw_arrow(ax, (9.40, 3.70), (7.10, 3.70),
               label="Pembaruan Stok, Menu\n& Status Antar", label_offset=(0, -0.22))

    out_path = os.path.join(OUTPUT_DIR, 'dfd_level_1.png')
    fig.savefig(out_path, dpi=300, facecolor='#FFFFFF', bbox_inches='tight')
    plt.close(fig)
    print(f"Created: {out_path}")


# ─────────────────────────────────────────────────────────────
#  2. DFD LEVEL 2
# ─────────────────────────────────────────────────────────────
def build_dfd_level_2():
    title = "DFD LEVEL 2 — RINCIAN SUB-PROSES PEMESANAN & RESERVASI"
    subtitle = "Dekomposisi Rinci Proses 2.0: Pilih Menu, Keranjang, Validasi Kuantitas & Meja, Checkout 3 Mode, dan Slip Tagihan QR"
    fig, ax = setup_canvas(title, subtitle)

    # Entitas
    draw_entity(ax, 0.45, 3.45, 1.85, 0.95, "Pelanggan", fontsize=12)
    draw_entity(ax, 9.40, 3.45, 2.40, 0.95, "Kasir POS &\nAdmin", fontsize=11.5)

    # Lingkaran Pusat (Revisi: "2.0 Pemesanan & Reservasi", hapus "Nucomu Cafe")
    draw_circle(ax, 5.85, 3.92, 1.25, 1.25, "2.0 Pemesanan\n& Reservasi", fontsize=12.5)

    # Top Row Processes:
    # 2.1 Pilih Menu & Varian
    draw_process(ax, 3.25, 6.05, 2.05, 0.85, "2.1 Pilih Menu\n& Varian", fontsize=10.5)
    # 2.2 Kelola Keranjang
    draw_process(ax, 6.05, 6.05, 2.05, 0.85, "2.2 Kelola\nKeranjang", fontsize=10.5)
    # 2.3 Validasi Kuantitas & Meja
    draw_process(ax, 8.85, 6.05, 2.25, 0.85, "2.3 Validasi\nKuantitas & Meja", fontsize=10.5)

    # Data Store Top (menus, categories & menu_options)
    draw_datastore(ax, 2.90, 5.60, 7.18, "menus, categories & menu_options\n(Katalog Menu & Varian)", height=0.46, fontsize=8.8)
    ax.plot([4.25, 4.25], [6.95, 6.90], color='#2980b9', lw=1.5, zorder=2)

    # Panah Top Row:
    # Pelanggan -> 2.1
    draw_polyline_arrow(ax, [(1.37, 4.40), (1.37, 6.47), (3.25, 6.47)],
                        label="Pilih Menu &\nVarian", label_pos=(1.37, 5.45), label_offset=(-0.80, 0))
    # 2.1 -> 2.2
    draw_arrow(ax, (5.30, 6.47), (6.05, 6.47), label="Tambah Item", label_offset=(0, 0.16))
    # 2.2 -> 2.3
    draw_arrow(ax, (8.10, 6.47), (8.85, 6.47), label="Cek Kuantitas", label_offset=(0, 0.16))
    # 2.3 -> Lingkaran Pusat
    draw_polyline_arrow(ax, [(9.97, 6.05), (9.97, 5.35), (5.85, 5.35), (5.85, 5.17)],
                        label="Item Keranjang & Meja Valid", label_pos=(7.91, 5.35), label_offset=(0, 0.18))

    # Bottom Row Processes:
    # 2.4 Checkout 3 Mode & Reservasi
    draw_process(ax, 0.45, 1.25, 2.45, 0.85, "2.4 Checkout 3 Mode\n& Reservasi Meja", fontsize=10.5)
    # 2.5 Pembuatan Tagihan & Slip QR
    draw_process(ax, 6.85, 1.25, 2.55, 0.85, "2.5 Pembuatan Tagihan\n& Slip QR (Unpaid)", fontsize=10.5)

    # Pelanggan -> 2.4 (line at x=0.80)
    draw_polyline_arrow(ax, [(0.80, 3.45), (0.80, 2.65), (0.80, 2.10)], label=None)
    # 2.4 -> Lingkaran Pusat (line at x=2.55)
    draw_polyline_arrow(ax, [(2.55, 2.10), (2.55, 2.85), (5.80, 2.85), (5.80, 2.67)],
                        label="Data Pemesanan & Draf WA", label_pos=(4.17, 2.85), label_offset=(0, 0.18))
    # Label Pelanggan -> 2.4 centered neatly between x=0.80 and x=2.55
    ax.text(1.67, 2.60, "Isian Form Checkout\n/ Reservasi", ha='center', va='center', fontsize=9,
            color='#1c4e73', fontfamily='Arial', zorder=5,
            bbox=dict(boxstyle='square,pad=0.2', facecolor='#FFFFFF', edgecolor='none'))

    # 2.4 -> 2.5
    draw_arrow(ax, (2.90, 1.67), (6.85, 1.67), label="Kalkulasi Subtotal & Metode Bayar", label_offset=(0, 0.18))
    # 2.5 -> Kasir POS & Admin
    draw_polyline_arrow(ax, [(9.40, 1.67), (10.42, 1.67), (10.42, 3.45)],
                        label="Data Tagihan Baru &\nKode Slip NCM", label_pos=(10.42, 2.56), label_offset=(1.35, 0))

    # Data Store Bawah:
    draw_datastore(ax, 5.70, 7.85, 0.45, "orders & order_items\n(Pesanan Status Unpaid)", height=0.46, fontsize=8.8)
    draw_datastore(ax, 8.45, 11.05, 0.45, "reservations & reservation_fields\n(Reservasi & Kolom Dinamis)", height=0.46, fontsize=8.8)
    # Cabang panah dari 2.5 ke Data Store Bawah
    draw_polyline_arrow(ax, [(7.70, 1.25), (7.70, 0.88), (6.77, 0.88), (6.77, 0.68)], label=None, lw=1.4)
    draw_polyline_arrow(ax, [(8.45, 1.25), (8.45, 0.88), (9.75, 0.88), (9.75, 0.68)], label=None, lw=1.4)

    # Data Store Kanan Jauh (tables & settings)
    draw_datastore(ax, 11.95, 13.85, 3.92, "tables & settings\n(Meja & 3 Mode)", height=0.58, fontsize=8.8)
    draw_arrow(ax, (11.80, 3.92), (11.95, 3.92), color='#2980b9', lw=1.6)

    # Aliran Pelanggan <-> Lingkaran Pusat
    draw_arrow(ax, (4.60, 4.15), (2.30, 4.15),
               label="Slip Kode QR (Unpaid) &\nTautan GrabFood", label_offset=(0, 0.22))
    draw_arrow(ax, (2.30, 3.70), (4.60, 3.70),
               label="Kirim Ulasan &\nRating Bintang", label_offset=(0, -0.22))

    # Aliran Lingkaran Pusat <-> Kasir POS & Admin
    draw_arrow(ax, (7.10, 4.15), (9.40, 4.15),
               label="Pesan WhatsApp\nReservasi & Order Baru", label_offset=(0, 0.22))
    draw_arrow(ax, (9.40, 3.70), (7.10, 3.70),
               label="Sinkronisasi Status\nMeja & Stok", label_offset=(0, -0.22))

    out_path = os.path.join(OUTPUT_DIR, 'dfd_level_2.png')
    fig.savefig(out_path, dpi=300, facecolor='#FFFFFF', bbox_inches='tight')
    plt.close(fig)
    print(f"Created: {out_path}")


# ─────────────────────────────────────────────────────────────
#  3. DFD LEVEL 3
# ─────────────────────────────────────────────────────────────
def build_dfd_level_3():
    title = "DFD LEVEL 3 — RINCIAN PROSES ATOMIK KASIR POS & PENGANTARAN ZENDO"
    subtitle = "Dekomposisi Rinci Proses 3.0: Scan QR Slip, Pelunasan Kasir, Potong Stok Otomatis, Tracking GPS, dan Antar Zendo"
    fig, ax = setup_canvas(title, subtitle)

    # Entitas
    draw_entity(ax, 0.45, 3.45, 1.85, 0.95, "Pelanggan", fontsize=12)
    # Entitas Kanan Level 3: Kasir & Kurir Zendo (GPS)
    draw_entity(ax, 9.40, 3.45, 2.45, 0.95, "Kasir & Kurir\nZendo (GPS)", fontsize=11)

    # Lingkaran Pusat (3.0 Operasional Kasir & Pengantaran Zendo)
    draw_circle(ax, 5.85, 3.92, 1.25, 1.25, "3.0 Operasional Kasir\n& Pengantaran\nZendo", fontsize=11.5)

    # Top Row Processes:
    # 3.1 Scan QR & Lookup Slip
    draw_process(ax, 3.25, 6.05, 2.05, 0.85, "3.1 Scan QR &\nLookup Slip", fontsize=10.5)
    # 3.2 Konfirmasi Pembayaran Kasir (Paid)
    draw_process(ax, 6.05, 6.05, 2.15, 0.85, "3.2 Konfirmasi\nBayar Kasir (Paid)", fontsize=10.5)
    # 3.3 Potong Stok Menu Otomatis
    draw_process(ax, 8.95, 6.05, 2.15, 0.85, "3.3 Potong Stok\nMenu Otomatis", fontsize=10.5)

    # Data Store Top (menus & order_items)
    draw_datastore(ax, 2.90, 5.60, 7.18, "menus & order_items\n(Kontrol Stok & Transaksi)", height=0.46, fontsize=8.8)
    ax.plot([4.25, 4.25], [6.95, 6.90], color='#2980b9', lw=1.5, zorder=2)

    # Panah Top Row:
    # Pelanggan -> 3.1
    draw_polyline_arrow(ax, [(1.37, 4.40), (1.37, 6.47), (3.25, 6.47)],
                        label="Tunjukkan Slip QR\n/ Kode NCM", label_pos=(1.37, 5.45), label_offset=(-0.82, 0))
    # 3.1 -> 3.2
    draw_arrow(ax, (5.30, 6.47), (6.05, 6.47), label="Data Pesanan", label_offset=(0, 0.16))
    # 3.2 -> 3.3
    draw_arrow(ax, (8.20, 6.47), (8.95, 6.47), label="Status Lunas (Paid)", label_offset=(0, 0.16))
    # 3.3 -> Lingkaran Pusat
    draw_polyline_arrow(ax, [(10.02, 6.05), (10.02, 5.35), (5.85, 5.35), (5.85, 5.17)],
                        label="Stok Terpotong & Order Siap Antar", label_pos=(7.93, 5.35), label_offset=(0, 0.18))

    # Bottom Row Processes:
    # 3.4 Pelacakan Pesanan GPS
    draw_process(ax, 0.45, 1.25, 2.45, 0.85, "3.4 Pelacakan\nPesanan GPS", fontsize=10.5)
    # 3.5 Verifikasi PIN & Pengantaran
    draw_process(ax, 6.85, 1.25, 2.55, 0.85, "3.5 Verifikasi PIN\n& Antar Pesanan", fontsize=10.5)

    # Pelanggan -> 3.4 (line at x=0.80)
    draw_polyline_arrow(ax, [(0.80, 3.45), (0.80, 2.65), (0.80, 2.10)], label=None)
    # 3.4 -> Lingkaran Pusat (line at x=2.55)
    draw_polyline_arrow(ax, [(2.55, 2.10), (2.55, 2.85), (5.80, 2.85), (5.80, 2.67)],
                        label="Permintaan Koordinat Kurir", label_pos=(4.17, 2.85), label_offset=(0, 0.18))
    # Label Pelanggan -> 3.4 centered neatly between x=0.80 and x=2.55
    ax.text(1.67, 2.60, "Buka Tautan\nToken Tracking", ha='center', va='center', fontsize=9,
            color='#1c4e73', fontfamily='Arial', zorder=5,
            bbox=dict(boxstyle='square,pad=0.2', facecolor='#FFFFFF', edgecolor='none'))

    # 3.4 -> 3.5
    draw_arrow(ax, (2.90, 1.67), (6.85, 1.67), label="Penugasan Kurir & Rute Tujuan", label_offset=(0, 0.18))
    # 3.5 -> Kasir & Kurir Zendo (GPS)
    draw_polyline_arrow(ax, [(9.40, 1.67), (10.47, 1.67), (10.47, 3.45)],
                        label="Validasi PIN Perangkat\n& Rute Antar", label_pos=(10.47, 2.56), label_offset=(1.35, 0))

    # Data Store Bawah:
    draw_datastore(ax, 5.70, 7.85, 0.45, "zendo_deliveries\n(GPS Kurir, Rute & Status)", height=0.46, fontsize=8.8)
    draw_datastore(ax, 8.45, 11.05, 0.45, "orders\n(Pembaruan Status Selesai)", height=0.46, fontsize=8.8)
    # Cabang panah dari 3.5 ke Data Store Bawah
    draw_polyline_arrow(ax, [(7.70, 1.25), (7.70, 0.88), (6.77, 0.88), (6.77, 0.68)], label=None, lw=1.4)
    draw_polyline_arrow(ax, [(8.45, 1.25), (8.45, 0.88), (9.75, 0.88), (9.75, 0.68)], label=None, lw=1.4)

    # Data Store Kanan Jauh (settings)
    draw_datastore(ax, 12.05, 13.85, 3.92, "settings\n(Tarif, Token &\nKoordinat Kafe)", height=0.66, fontsize=8.8)
    draw_arrow(ax, (11.85, 3.92), (12.05, 3.92), color='#2980b9', lw=1.6)

    # Aliran Pelanggan <-> Lingkaran Pusat
    draw_arrow(ax, (4.60, 4.15), (2.30, 4.15),
               label="Peta GPS Live &\nStruk Thermal", label_offset=(0, 0.22))
    draw_arrow(ax, (2.30, 3.70), (4.60, 3.70),
               label="Konfirmasi Pesanan\nDiterima", label_offset=(0, -0.22))

    # Aliran Lingkaran Pusat <-> Kasir & Kurir Zendo (GPS)
    draw_arrow(ax, (9.40, 4.15), (7.10, 4.15),
               label="Streaming Koordinat\nGPS Kurir", label_offset=(0, 0.22))
    draw_arrow(ax, (7.10, 3.70), (9.40, 3.70),
               label="Rute Peta Navigasi &\nDetail Pemesan", label_offset=(0, -0.22))

    out_path = os.path.join(OUTPUT_DIR, 'dfd_level_3.png')
    fig.savefig(out_path, dpi=300, facecolor='#FFFFFF', bbox_inches='tight')
    plt.close(fig)
    print(f"Created: {out_path}")


# ─────────────────────────────────────────────────────────────
#  4. GENERATE DOCX REPORT
# ─────────────────────────────────────────────────────────────
def build_docx_document():
    doc = Document()

    # Set page layout to Landscape
    section = doc.sections[0]
    section.orientation = WD_ORIENTATION.LANDSCAPE
    section.page_width = Inches(11.69)   # A4 Landscape width
    section.page_height = Inches(8.27)   # A4 Landscape height
    section.top_margin = Inches(0.6)
    section.bottom_margin = Inches(0.6)
    section.left_margin = Inches(0.6)
    section.right_margin = Inches(0.6)

    # Style definitions
    heading_font_name = 'Arial'
    body_font_name = 'Arial'

    # Document Header Title (Revisi: hanya "NUCOMU CAFE")
    doc_title = doc.add_paragraph()
    doc_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run_dt = doc_title.add_run("DOKUMENTASI DATA FLOW DIAGRAM (DFD) LEVEL 1, 2, DAN 3\nSISTEM INFORMASI UMKM NUCOMU CAFE")
    run_dt.font.name = heading_font_name
    run_dt.font.size = Pt(15)
    run_dt.font.bold = True
    run_dt.font.color.rgb = RGBColor(0x14, 0x28, 0x46)
    doc_title.paragraph_format.space_after = Pt(14)

    sections_data = [
        {
            "num": "1. DATA FLOW DIAGRAM (DFD) LEVEL 1 — SISTEM INFORMASI UMKM NUCOMU CAFE",
            "desc": "Diagram Alir Data (DFD) Level 1 ini menggambarkan arsitektur fungsional terintegrasi Sistem Informasi Nucomu Cafe secara menyeluruh. Aliran data menghubungkan entitas Pelanggan di sisi publik dengan entitas Admin, Kasir & Kurir Zendo di sisi operasional. Proses diawali dengan autentikasi staf (Proses 1, 1.1, dan 1.2) yang membaca tabel database 'users' dan 'settings' untuk menerbitkan sesi aktif. Pelanggan dapat memesan makanan/minuman maupun mengajukan reservasi meja melalui Proses 2 (Pemesanan & Reservasi), yang meneruskan data tagihan ke Proses 3 (Operasional Kasir & Pengantaran Zendo). Seluruh tabel pendukung sistem terintegrasi pada level ini: 'menus', 'categories', 'menu_options', dan 'tables' untuk data master katalog kafe; serta 'orders', 'order_items', 'reservations', 'reservation_fields', dan 'zendo_deliveries' untuk pencatatan riwayat komersial dan logistik kafe.",
            "img": os.path.join(OUTPUT_DIR, "dfd_level_1.png")
        },
        {
            "num": "2. DATA FLOW DIAGRAM (DFD) LEVEL 2 — RINCIAN SUB-PROSES PEMESANAN & RESERVASI",
            "desc": "Diagram Alir Data (DFD) Level 2 merupakan dekomposisi rinci dari proses induk '2.0 Pemesanan & Reservasi'. Pada tingkat ini, interaksi pelanggan dipecah menjadi 5 sub-proses terstruktur: (2.1) Memilih menu dan kustomisasi varian suhu/gula dari tabel 'menus', 'categories', dan 'menu_options'; (2.2) Mengelola keranjang belanja; (2.3) Memvalidasi kuantitas item dan nomor meja fisik dari tabel 'tables'; (2.4) Menjalankan checkout 3 mode (Makan di Kafe, Antar via WhatsApp Zendo, atau Antar Pelacakan GPS) serta reservasi meja formulir dinamis ('reservation_fields'); dan (2.5) Pembuatan tagihan subtotal, penetapan metode pembayaran Tunai/QRIS, serta penerbitan lembar Slip QR digital dengan status pembayaran 'unpaid' yang tersimpan di tabel 'orders' dan 'reservations'.",
            "img": os.path.join(OUTPUT_DIR, "dfd_level_2.png")
        },
        {
            "num": "3. DATA FLOW DIAGRAM (DFD) LEVEL 3 — RINCIAN PROSES ATOMIK KASIR POS & PENGANTARAN ZENDO",
            "desc": "Diagram Alir Data (DFD) Level 3 menyajikan dekomposisi tingkat atomik dari proses induk '3.0 Operasional Kasir & Pengantaran Zendo' yang melibatkan entitas Pelanggan serta entitas Kasir & Kurir Zendo (GPS). Sub-proses diawali dengan (3.1) Pemindaian Slip QR atau lookup kode pesanan NCM oleh kasir; dilanjutkan dengan (3.2) Konfirmasi pelunasan kasir di Point of Sale (POS) yang mengubah status pembayaran menjadi 'paid' pada tabel 'orders' dan pencetakan struk kasir thermal; (3.3) Pemotongan stok fisik menu secara otomatis pada tabel 'menus'; (3.4) Pelacakan pesanan real-time oleh pelanggan menggunakan token unik pada peta Leaflet.js; serta (3.5) Verifikasi PIN perangkat kurir dan pengiriman koordinat GPS yang tercatat pada tabel 'zendo_deliveries' hingga pesanan berhasil diantarkan ke tangan pelanggan.",
            "img": os.path.join(OUTPUT_DIR, "dfd_level_3.png")
        }
    ]

    for idx, item in enumerate(sections_data):
        # Section Title
        h = doc.add_paragraph()
        run_h = h.add_run(item["num"])
        run_h.font.name = heading_font_name
        run_h.font.size = Pt(12)
        run_h.font.bold = True
        run_h.font.color.rgb = RGBColor(0x14, 0x28, 0x46)
        h.paragraph_format.space_before = Pt(8)
        h.paragraph_format.space_after = Pt(4)

        # Description Paragraph
        p = doc.add_paragraph()
        run_p = p.add_run(item["desc"])
        run_p.font.name = body_font_name
        run_p.font.size = Pt(10)
        run_p.font.color.rgb = RGBColor(0x37, 0x41, 0x51)
        p.paragraph_format.line_spacing = 1.15
        p.paragraph_format.space_after = Pt(6)

        # Insert Image
        if os.path.exists(item["img"]):
            img_p = doc.add_paragraph()
            img_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            img_p.paragraph_format.space_after = Pt(12)
            run_img = img_p.add_run()
            run_img.add_picture(item["img"], width=Inches(10.4))

        if idx < len(sections_data) - 1:
            doc.add_page_break()

    docx_path = os.path.join(OUTPUT_DIR, "DFD_UMKM_Nucomu_Cafe.docx")
    doc.save(docx_path)
    print(f"Created: {docx_path}")

if __name__ == '__main__':
    print("Generating DFD Level 1, 2, 3 and DOCX Document...")
    build_dfd_level_1()
    build_dfd_level_2()
    build_dfd_level_3()
    build_docx_document()
    print("All tasks finished successfully!")
