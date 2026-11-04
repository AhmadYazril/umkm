@extends('layouts.admin')
@section('title', 'Banner Halaman Depan — Nucomu Cafe')
@section('page_title', 'Banner Halaman Depan')

@section('content')
<div class="space-y-[18px] max-w-7xl mx-auto pt-1">
    
    {{-- Header Section: Komponen Reusable x-page-header (Sederhana, Tenang & Elegan) --}}
    <x-page-header title="Banner Halaman Depan">
        <x-slot:actions>
            {{-- Tautan Teks Kecil "? Petunjuk" (13px, abu #6B7270, tanpa latar/border, hover warna aksen) --}}
            <button type="button"
                    id="banner-help-panel-trigger"
                    class="admin-help-link btn-press"
                    aria-expanded="false"
                    aria-controls="banner-help-panel-content"
                    onclick="toggleHelpPanel('banner-help-panel')">
                <i class="fa-regular fa-circle-question text-[12.5px] shrink-0" aria-hidden="true"></i>
                <span class="toggle-label" data-open="Petunjuk" data-close="Tutup petunjuk">Petunjuk</span>
            </button>

            {{-- Tombol Utama: Tambah Banner Baru --}}
            <a href="{{ route('admin.banners.create') }}"
               class="admin-btn-primary shadow-xs btn-press">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Banner Baru</span>
            </a>
        </x-slot:actions>

        <x-slot:help>
            <x-help-panel id="banner-help-panel" :trigger="false">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">
                    {{-- Bagian 1: Cara menambah banner --}}
                    <div class="space-y-2">
                        <h4 class="text-[13.5px] font-bold flex items-center gap-1.5" style="color: var(--admin-text-main);">
                            <i class="fa-solid fa-list-ol text-[11px] shrink-0" style="color: var(--admin-btn-primary-bg);"></i>
                            <span>Cara Menambah Banner</span>
                        </h4>
                        <ol class="space-y-1.5 text-[13px] list-decimal list-inside pl-0.5 leading-relaxed" style="color: var(--admin-text-sub);">
                            <li>Klik tombol <strong class="font-semibold" style="color: var(--admin-text-main);">Tambah Banner Baru</strong> di atas.</li>
                            <li>Isi judul promo, teks tombol aksi (misal: "Pesan Sekarang"), dan tautan tujuan.</li>
                            <li>Pilih jenis media (foto/video), lalu unggah berkas promosi.</li>
                            <li>Tentukan tanggal &amp; jam mulai serta selesai tayang, lalu klik Simpan.</li>
                        </ol>
                    </div>

                    {{-- Bagian 2: Ukuran foto/video yang disarankan --}}
                    <div class="space-y-2">
                        <h4 class="text-[13.5px] font-bold flex items-center gap-1.5" style="color: var(--admin-text-main);">
                            <i class="fa-solid fa-photo-film text-[11px] shrink-0" style="color: var(--admin-btn-primary-bg);"></i>
                            <span>Ukuran Media Disarankan</span>
                        </h4>
                        <ul class="space-y-1.5 text-[13px] leading-relaxed" style="color: var(--admin-text-sub);">
                            <li class="flex items-start gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full mt-1.5 shrink-0" style="background-color: var(--admin-btn-primary-bg);"></span>
                                <span><strong class="font-semibold" style="color: var(--admin-text-main);">Foto:</strong> Rasio landscape disarankan 1920&times;800 px atau 1920&times;1080 px, minimal lebar <strong>1600 px</strong>. Format JPG, PNG, atau WebP, maksimal <strong>5 MB</strong>.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full mt-1.5 shrink-0" style="background-color: var(--admin-btn-primary-bg);"></span>
                                <span><strong class="font-semibold" style="color: var(--admin-text-main);">Video:</strong> Rasio 16:9 format MP4, WebM, atau MOV, ukuran file maksimal <strong>20 MB</strong>.</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Bagian 3: Tips & Ketentuan --}}
                    <div class="space-y-2">
                        <h4 class="text-[13.5px] font-bold flex items-center gap-1.5" style="color: var(--admin-text-main);">
                            <i class="fa-regular fa-lightbulb text-[11px] shrink-0" style="color: var(--admin-status-expiring-text);"></i>
                            <span>Tips Pengelolaan</span>
                        </h4>
                        <ul class="space-y-1.5 text-[13px] leading-relaxed" style="color: var(--admin-text-sub);">
                            <li class="flex items-start gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full mt-1.5 shrink-0" style="background-color: var(--admin-status-expiring-bg-solid);"></span>
                                <span>Banner <strong class="font-semibold" style="color: var(--admin-text-main);">hanya tampil di layar komputer</strong>. Di HP banner tidak ditampilkan.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full mt-1.5 shrink-0" style="background-color: var(--admin-status-expiring-bg-solid);"></span>
                                <span>Banner otomatis <strong class="font-semibold" style="color: var(--admin-text-main);">berhenti tampil</strong> saat jadwal waktu selesai terlewati.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full mt-1.5 shrink-0" style="background-color: var(--admin-status-expiring-bg-solid);"></span>
                                <span>Perpanjang waktu tayang kapan saja lewat tombol <strong class="font-semibold" style="color: var(--admin-text-main);">Perpanjang waktu</strong> tanpa perlu edit ulang form.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full mt-1.5 shrink-0" style="background-color: var(--admin-status-expiring-bg-solid);"></span>
                                <span>Notifikasi pengingat otomatis muncul <strong class="font-semibold" style="color: var(--admin-text-main);">30 menit</strong> sebelum banner berakhir.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </x-help-panel>
        </x-slot:help>
    </x-page-header>

    {{-- Catatan Penjelasan Layar Komputer --}}
    <div class="p-3 sm:p-3.5 rounded-xl border flex items-center gap-2.5 text-[13px] leading-relaxed"
         style="background-color: var(--admin-bg-active, #F4F7F5); border-color: var(--admin-border-card, #E2E8E5); color: var(--admin-text-sub, #4A5552);">
        <i class="fa-solid fa-desktop text-[14px] shrink-0" style="color: var(--admin-btn-primary-bg, #2F6B4F);"></i>
        <span><strong class="font-semibold" style="color: var(--admin-text-main, #1F2926);">Catatan:</strong> Banner hanya tampil di layar komputer. Di HP banner tidak ditampilkan.</span>
    </div>

    {{-- Filter Navigasi Status (Chip Filter Adaptif via CSS Variables) --}}
    <div class="flex items-center gap-1.5 overflow-x-auto text-[13px] no-scrollbar py-0.5">
        <a href="{{ route('admin.banners.index', ['status' => 'all']) }}" 
           class="admin-chip-filter btn-press {{ $statusFilter === 'all' ? 'is-active' : '' }}">
            <span>Semua ({{ $totalCount }})</span>
        </a>

            <a href="{{ route('admin.banners.index', ['status' => 'active']) }}" 
               class="admin-chip-filter btn-press {{ $statusFilter === 'active' ? 'is-active' : '' }}">
                <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $statusFilter === 'active' ? '#FFFFFF' : 'var(--admin-status-active-bg-solid)' }};"></span>
                <span>Sedang Tampil ({{ $activeCount }})</span>
            </a>

            @if($expiringCount > 0 || $statusFilter === 'expiring')
                <a href="{{ route('admin.banners.index', ['status' => 'expiring']) }}" 
                   class="admin-chip-filter btn-press {{ $statusFilter === 'expiring' ? 'is-active' : '' }}">
                    <i class="fa-solid fa-clock text-xs"></i>
                    <span>Segera Berakhir ({{ $expiringCount }})</span>
                </a>
            @endif

            <a href="{{ route('admin.banners.index', ['status' => 'upcoming']) }}" 
               class="admin-chip-filter btn-press {{ $statusFilter === 'upcoming' ? 'is-active' : '' }}">
                <span>Akan Tampil</span>
            </a>

            <a href="{{ route('admin.banners.index', ['status' => 'expired']) }}" 
               class="admin-chip-filter btn-press {{ $statusFilter === 'expired' ? 'is-active' : '' }}">
                <span>Sudah Berakhir ({{ $expiredCount }})</span>
            </a>
        </div>
    </div>

    {{-- Daftar Banner (Grid 2 Kolom di Layar Lebar, Desain Kartu Adaptif Bersih) --}}
    @if($banners->count() > 0)
        <div class="{{ $banners->count() > 1 ? 'grid grid-cols-1 xl:grid-cols-2 gap-4 lg:gap-5' : 'space-y-4' }}">
            @foreach($banners as $banner)
                @php
                    $status = $banner->status;
                    $isExpiring = $banner->is_expiring_soon;
                @endphp
                <div class="admin-card group relative flex flex-col md:flex-row items-stretch p-3.5 sm:p-4 gap-3.5 sm:gap-4 overflow-hidden">
                    
                    {{-- Foto di Kiri: Rasio 16:9, Lebar ~210px, Sudut Membulat 10px --}}
                    <div class="w-full md:w-[210px] md:min-w-[210px] md:max-w-[210px] aspect-video rounded-[10px] overflow-hidden relative self-start md:self-center shrink-0 border"
                         style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card);">
                        @if($banner->media_type === 'video')
                            <video src="{{ $banner->media_url }}" class="w-full h-full object-cover" muted playsinline loop onmouseover="this.play()" onmouseout="this.pause()"></video>
                        @else
                            <img src="{{ $banner->media_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                        @endif

                        {{-- Badge Status di Atas Foto (Pojok Kiri Atas) --}}
                        <div class="absolute top-2 left-2 z-10">
                            @if($isExpiring)
                                <span class="admin-status-badge status-expiring animate-pulse"
                                      title="Waktu tayang banner hampir selesai">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--admin-status-expiring-bg-solid);"></span>
                                    <span>Segera Berakhir</span>
                                </span>
                            @elseif($status === 'active')
                                <span class="admin-status-badge status-active"
                                      title="Banner sedang aktif tampil di website">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--admin-status-active-bg-solid);"></span>
                                    <span>Sedang Tampil</span>
                                </span>
                            @elseif($status === 'upcoming')
                                <span class="admin-status-badge status-upcoming"
                                      title="Banner akan otomatis mulai sesuai jadwal">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--admin-status-upcoming-bg-solid);"></span>
                                    <span>Akan Tampil</span>
                                </span>
                            @elseif($status === 'expired')
                                <span class="admin-status-badge status-expired"
                                      title="Jadwal tayang banner sudah lewat">
                                    <span>Sudah Berakhir</span>
                                </span>
                            @else
                                <span class="admin-status-badge status-expired"
                                      title="Banner dinonaktifkan sementara oleh admin">
                                    <span>Dinonaktifkan</span>
                                </span>
                            @endif
                        </div>

                        {{-- Tag Jenis Media & Mode di Pojok Bawah Foto --}}
                        <div class="absolute bottom-2 left-2 flex items-center gap-1 z-10">
                            <span class="px-1.5 py-0.5 rounded-md bg-black/75 backdrop-blur-md text-white text-[9.5px] font-medium flex items-center gap-1 shadow-xs">
                                @if($banner->media_type === 'video')
                                    <i class="fa-solid fa-video text-[8px]"></i>
                                    <span>Video</span>
                                @else
                                    <i class="fa-regular fa-image text-[8px]"></i>
                                    <span>Foto</span>
                                @endif
                            </span>
                            <span class="px-1.5 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-white/95 text-[9.5px] font-medium flex items-center gap-1 shadow-xs">
                                <span>{{ $banner->layout_mode_label }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Kanan: Konten Informasi, Progres Waktu & Baris Tombol Aksi Lengkap --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-between space-y-2 py-0.5">
                        
                        <div class="space-y-1">
                            {{-- Subtitle / Kategori Kecil --}}
                            @if($banner->subtitle)
                                @php
                                    $cleanSubtitle = str_ireplace(['&bull;', '&amp;bull;', '•'], ' · ', $banner->subtitle);
                                @endphp
                                <p class="text-[12px] truncate font-medium max-w-full" style="color: var(--admin-text-muted);" title="{{ $cleanSubtitle }}">
                                    {{ $cleanSubtitle }}
                                </p>
                            @endif

                            {{-- Judul Banner (Plus Jakarta Sans Sans-serif, 15-16px, Normal Numbers) --}}
                            <h2 class="text-[15px] sm:text-[16px] font-bold leading-snug line-clamp-1 sm:line-clamp-2" style="color: var(--admin-text-main);" title="{{ $banner->title }}">
                                {{ $banner->title }}
                            </h2>

                            {{-- Keterangan Dibatasi Maksimal 2 Baris (line-clamp-2, font 13px) --}}
                            @if($banner->description)
                                <p class="text-[13px] line-clamp-2 leading-relaxed" style="color: var(--admin-text-sub);">
                                    {{ $banner->description }}
                                </p>
                            @endif
                        </div>

                        {{-- Jadwal & Bar Progres Waktu --}}
                        <div class="space-y-1 pt-0.5">
                            <div class="flex items-center justify-between text-[12px]">
                                <span class="inline-flex items-center gap-1.5 truncate" style="color: var(--admin-text-muted);">
                                    <i class="fa-regular fa-calendar text-[11px]" style="color: var(--admin-text-muted);"></i>
                                    <span class="truncate">{{ $banner->schedule_human }}</span>
                                </span>
                                <span class="font-semibold text-[11.5px] sm:text-[12px] shrink-0 ml-2" 
                                      style="color: {{ $isExpiring ? 'var(--admin-status-expiring-text)' : ($status === 'active' ? 'var(--admin-status-active-text)' : 'var(--admin-text-muted)') }};">
                                    {{ $banner->progress_label }}
                                </span>
                            </div>
                            <div class="w-full rounded-full h-[4px] overflow-hidden" style="background-color: var(--admin-progress-track);" title="{{ $banner->progress_percentage }}% durasi tayang telah berjalan">
                                <div class="h-full rounded-full transition-all duration-500" 
                                     style="width: {{ $banner->progress_percentage }}%; background-color: {{ $isExpiring ? 'var(--admin-status-expiring-bg-solid)' : 'var(--admin-progress-bar)' }};"></div>
                            </div>
                        </div>

                        {{-- Tombol Aksi Lengkap dengan Teks Jelas --}}
                        <div class="pt-2 border-t flex flex-wrap items-center justify-between gap-1.5" style="border-top-color: var(--admin-border-card);">
                            <div class="flex flex-wrap items-center gap-1.5">
                                {{-- Edit --}}
                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                   class="admin-btn-primary shadow-xs btn-press !h-[32px] !min-h-[44px] sm:!min-h-[32px] !px-3 !rounded-lg !text-[12.5px]"
                                   title="Ubah judul, foto, atau jadwal banner">
                                    <i class="fa-regular fa-pen-to-square text-[12px]"></i>
                                    <span>Edit</span>
                                </a>

                                {{-- Perpanjang Waktu --}}
                                <button type="button" onclick="openExtendModal('{{ $banner->id }}', '{{ addslashes($banner->title) }}', '{{ $banner->end_time->format('Y-m-d\TH:i') }}')"
                                        class="admin-btn-secondary shadow-xs btn-press !h-[32px] !min-h-[44px] sm:!min-h-[32px] !px-2.5 !rounded-lg !text-[12.5px]"
                                        title="Perpanjang waktu tayang banner ini">
                                    <i class="fa-regular fa-clock text-[12px]" style="color: var(--admin-text-muted);"></i>
                                    <span>Perpanjang waktu</span>
                                </button>

                                {{-- Matikan / Aktifkan --}}
                                <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST" class="inline">
                                    @csrf
                                    @if($banner->is_active)
                                        <button type="submit" 
                                                class="admin-btn-secondary shadow-xs btn-press !h-[32px] !min-h-[44px] sm:!min-h-[32px] !px-2.5 !rounded-lg !text-[12.5px]"
                                                title="Sembunyikan banner ini sementara dari website">
                                            <i class="fa-solid fa-eye-slash text-[12px]" style="color: var(--admin-text-muted);"></i>
                                            <span>Matikan</span>
                                        </button>
                                    @else
                                        <button type="submit" 
                                                class="admin-btn-primary shadow-xs btn-press !h-[32px] !min-h-[44px] sm:!min-h-[32px] !px-2.5 !rounded-lg !text-[12.5px]"
                                                title="Tampilkan kembali banner ini di website">
                                            <i class="fa-solid fa-eye text-[12px]"></i>
                                            <span>Aktifkan</span>
                                        </button>
                                    @endif
                                </form>
                            </div>

                            {{-- Hapus --}}
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner \'{{ addslashes($banner->title) }}\'? File foto/video juga akan terhapus.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-2.5 rounded-lg text-[12.5px] font-semibold transition-colors flex items-center gap-1.5"
                                        style="color: var(--admin-status-danger-text);"
                                        title="Hapus banner ini secara permanen">
                                    <i class="fa-regular fa-trash-can text-[12px]"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $banners->links() }}
        </div>
    @else
        <div class="admin-card p-8 sm:p-12 text-center max-w-lg mx-auto">
            <div class="w-14 h-14 rounded-2xl border flex items-center justify-center mx-auto mb-4"
                 style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                <i class="fa-solid fa-bullhorn text-xl" style="color: var(--admin-text-muted);"></i>
            </div>
            @if($statusFilter !== 'all')
                <h3 class="text-base font-bold" style="color: var(--admin-text-main);">Tidak Ada Banner di Kategori Ini</h3>
                <p class="text-[14px] mt-2 leading-relaxed" style="color: var(--admin-text-sub);">
                    Tidak ditemukan banner dengan status filter yang dipilih. Silakan kembali ke semua banner atau buat banner baru.
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('admin.banners.index', ['status' => 'all']) }}" 
                       class="admin-btn-secondary min-h-[44px] px-4 py-2.5 rounded-xl text-[14px] btn-press">
                        Lihat Semua Banner
                    </a>
                    <a href="{{ route('admin.banners.create') }}" 
                       class="admin-btn-primary min-h-[44px] px-5 py-2.5 rounded-xl text-[14px] shadow-xs btn-press">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Banner Baru</span>
                    </a>
                </div>
            @else
                <h3 class="text-base font-bold" style="color: var(--admin-text-main);">Belum Ada Banner Halaman Depan</h3>
                <p class="text-[14px] mt-2 leading-relaxed" style="color: var(--admin-text-sub);">
                    Banner yang Anda tambahkan di sini akan langsung tampil di bagian paling atas halaman depan website kafe untuk menyambut pengunjung.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.banners.create') }}" 
                       class="admin-btn-primary min-h-[44px] px-5 py-2.5 rounded-xl text-[14px] shadow-xs btn-press">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Banner Baru</span>
                    </a>
                </div>
            @endif
        </div>
    @endif

</div>

{{-- Modal Quick Extend Duration --}}
<div id="modalExtendDuration" class="fixed inset-0 z-[80] bg-black/40 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="admin-card p-6 sm:p-7 max-w-md w-full shadow-dropdown anim-fade-up">
        <div class="flex items-center justify-between pb-3.5 border-b mb-4" style="border-bottom-color: var(--admin-border-card);">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl border flex items-center justify-center text-xs"
                     style="background-color: var(--admin-status-expiring-bg); color: var(--admin-status-expiring-text); border-color: var(--admin-status-expiring-border);">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold" style="color: var(--admin-text-main);">Perpanjang Waktu Tampil</h3>
                    <p class="text-[13px] truncate max-w-[240px]" style="color: var(--admin-text-muted);" id="modalExtendBannerTitle">Pilih perpanjangan waktu</p>
                </div>
            </div>
            <button type="button" onclick="closeExtendModal()" class="w-8 h-8 flex items-center justify-center rounded-lg opacity-70 hover:opacity-100" style="color: var(--admin-text-main);">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="formExtendDuration" method="POST" action="">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[13.5px] font-semibold mb-2" style="color: var(--admin-text-main);">Pilih Tambahan Waktu Cepat</label>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        @foreach([
                            '30_minutes' => '+30 Menit',
                            '1_hour' => '+1 Jam',
                            '6_hours' => '+6 Jam',
                            '1_day' => '+1 Hari',
                            '3_days' => '+3 Hari',
                            '7_days' => '+1 Minggu'
                        ] as $val => $lbl)
                            <label class="cursor-pointer">
                                <input type="radio" name="extend_by" value="{{ $val }}" {{ $val === '1_hour' ? 'checked' : '' }} class="peer sr-only">
                                <div class="min-h-[40px] p-2 rounded-xl border text-center font-medium text-[12.5px] transition flex items-center justify-center peer-checked:font-bold"
                                     style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                                    {{ $lbl }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-2 border-t" style="border-top-color: var(--admin-border-card);">
                    <label for="customEndTimeInput" class="block text-[13.5px] font-semibold mb-1.5" style="color: var(--admin-text-main);">Atau Tentukan Jam &amp; Tanggal Selesai Baru</label>
                    <input type="datetime-local" id="customEndTimeInput" name="new_end_time"
                           class="w-full border rounded-xl px-3.5 py-2.5 text-[13.5px] focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12px] mt-1 block" style="color: var(--admin-text-muted);">Biarkan kosong jika memilih opsi perpanjangan cepat di atas.</span>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeExtendModal()" class="admin-btn-secondary min-h-[44px] sm:min-h-[34px] sm:h-[34px] px-4 py-2 rounded-xl text-[13px] font-medium btn-press">
                        Batal
                    </button>
                    <button type="submit" class="admin-btn-primary min-h-[44px] sm:min-h-[34px] sm:h-[34px] px-5 py-2 rounded-xl text-[13px] font-semibold shadow-xs btn-press">
                        Simpan Perpanjangan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openExtendModal(bannerId, bannerTitle, currentEndTime) {
        const modal = document.getElementById('modalExtendDuration');
        const form = document.getElementById('formExtendDuration');
        const titleEl = document.getElementById('modalExtendBannerTitle');
        const inputCustom = document.getElementById('customEndTimeInput');

        if (!modal || !form) return;

        form.action = `/admin/banner/${bannerId}/perpanjang`;
        titleEl.textContent = bannerTitle;
        if (inputCustom && currentEndTime) {
            inputCustom.value = currentEndTime;
        }

        modal.classList.remove('hidden');
    }

    function closeExtendModal() {
        const modal = document.getElementById('modalExtendDuration');
        if (modal) modal.classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('modalExtendDuration')?.addEventListener('click', function(e) {
        if (e.target === this) closeExtendModal();
    });
</script>
@endsection
