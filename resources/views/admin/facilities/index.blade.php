@extends('layouts.admin')
@section('title', 'Fasilitas — Nucomu Cafe')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-10">
    
    {{-- 1. Header Halaman Standar (x-page-header dengan Judul Tenang, Tombol ? Petunjuk & + Tambah Fasilitas) --}}
    <x-page-header title="Fasilitas">
        <x-slot name="actions">
            <div class="flex items-center gap-2.5">
                {{-- Tombol Petunjuk --}}
                <button type="button"
                        class="admin-help-link btn-press"
                        id="facilities-help-trigger"
                        aria-expanded="false"
                        aria-controls="facilities-help-panel-content"
                        onclick="toggleHelpPanel('facilities-help-panel')">
                    <i class="fa-regular fa-circle-question text-[13px]" aria-hidden="true"></i>
                    <span class="toggle-label" data-open="? Petunjuk" data-close="Tutup petunjuk">? Petunjuk</span>
                    <i class="fa-solid fa-chevron-down text-[10px] toggle-chevron" aria-hidden="true"></i>
                </button>

                {{-- Tombol Tambah Fasilitas (Hijau Solid, Teks Putih, Kontras >= 4.5:1) --}}
                <a href="{{ route('admin.facilities.create') }}"
                   class="min-h-[36px] px-4 py-2 rounded-xl text-[13.5px] font-semibold transition flex items-center gap-2 shadow-sm cursor-pointer btn-press"
                   style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;"
                   title="Tambah fasilitas cafe baru">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Tambah Fasilitas</span>
                </a>
            </div>
        </x-slot>

        {{-- Panel Petunjuk Penggunaan (x-help-panel) --}}
        <x-slot name="help">
            <x-help-panel id="facilities-help-panel" :trigger="false">
                <div class="space-y-4">
                    {{-- Judul Panel Petunjuk --}}
                    <div>
                        <h3 class="text-[15px] font-bold tracking-tight" style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                            Panduan Mengelola Carousel Fasilitas
                        </h3>
                        <p class="text-[12.5px] mt-0.5 m-0" style="color: var(--admin-text-sub, #555D58);">
                            Halaman ini mengatur carousel fasilitas interaktif pada bagian "Fasilitas" di halaman depan website kafe.
                        </p>
                    </div>

                    {{-- 4 Langkah Singkat Menambah Fasilitas --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-1">
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">1</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Nama &amp; Tombol</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Tuliskan nama tombol pilihan (misal: Indoor, Outdoor, Musholla) dan judul utama fasilitas.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">2</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Penjelasan &amp; Poin</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Tuliskan suasana ruangan dan 3&ndash;5 poin keunggulan utama yang dicari pengunjung.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">3</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Unggah 1&ndash;3 Foto</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Pilih 1 foto utama (wajib) dan hingga 2 foto pendukung beresolusi tajam (rasio 4:3).
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">4</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Urutan Tampil</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Urutan fasilitas dari atas ke bawah pada daftar ini = urutan tampil di website. Gunakan tombol panah untuk memindahkannya.
                            </p>
                        </div>
                    </div>

                    {{-- Arti Tiap Bagian & Di Mana Tampil di Website --}}
                    <div class="p-3.5 rounded-xl border text-[12.5px] leading-relaxed space-y-2"
                         style="background-color: var(--admin-bg-sidebar-hover, #F0F4F1); border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-circle-nodes text-xs" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                            <span>Arti Tiap Bagian &amp; Letaknya di Website</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 pt-1">
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Nama Tombol Pilihan:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Muncul sebagai tombol filter di menu atas carousel fasilitas website.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Judul &amp; Penjelasan:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Tampil di sisi kiri carousel saat fasilitas dipilih oleh pengunjung.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Foto Fasilitas:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Foto 1 tampil besar di kanan; foto 2 dan 3 jadi gambar pendukung yang bisa diklik.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Spesifikasi & Batas Ukuran Foto --}}
                    <div class="p-3.5 rounded-xl border text-[12.5px] leading-relaxed space-y-2"
                         style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-camera text-xs" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                            <span>Panduan Format &amp; Ukuran Foto</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1 text-[12px]">
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Rasio Foto:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">4:3 (disarankan <strong>1200 &times; 900 px</strong>) agar tidak terpotong.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Format File:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">JPG, JPEG, PNG, atau WebP.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Batas Ukuran Server:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Maksimal <strong>{{ $facilityPhotoLimits['formatted'] ?? '5 MB' }}</strong> per foto.</span>
                            </div>
                        </div>
                        <p class="text-[11.5px] m-0 text-emerald-900 pt-0.5">
                            Minimal 1 foto (wajib), maksimal 3 foto. Foto pertama otomatis menjadi foto utama.
                        </p>
                    </div>

                    {{-- Tips Menulis Keunggulan --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[12px] pt-1"
                         style="color: var(--admin-text-sub, #555D58);">
                        <p class="m-0">
                            <strong>Tips Menulis:</strong> Cantumkan 3&ndash;5 keunggulan nyata (misal: "Stopkontak di setiap meja", "Wi-Fi cepat &amp; stabil", "Area ibadah tenang").
                        </p>
                    </div>
                </div>
            </x-help-panel>
        </x-slot>
    </x-page-header>

    {{-- Alert Notifikasi Sukses / Gagal --}}
    @if(session('success'))
        <div class="p-3.5 sm:p-4 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-[13px] leading-relaxed anim-fade-up"
             style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); border-color: #C3DCD0; color: #164E33;">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 shrink-0 text-base" aria-hidden="true"></i>
                <div>
                    <span class="font-bold">Berhasil.</span>
                    <span class="opacity-90 ml-1">{{ session('success') }}</span>
                </div>
            </div>
            <a href="{{ route('home') }}#facilities" target="_blank" rel="noopener noreferrer"
               class="font-bold underline hover:opacity-80 transition shrink-0 flex items-center gap-1.5 self-end sm:self-auto text-emerald-900">
                <span>Lihat hasilnya di website</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
            </a>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl border bg-rose-50 border-rose-200 text-rose-900 text-[13px] shadow-xs flex items-start gap-2.5 anim-fade-up">
            <i class="fa-solid fa-circle-xmark text-rose-600 mt-0.5 text-base shrink-0" aria-hidden="true"></i>
            <div>
                <span class="font-bold">Terjadi kesalahan:</span>
                <p class="m-0 mt-0.5 text-[12.5px] leading-relaxed">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- 2. Status Kalimat Ringkas Dinamis --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[13px]"
         style="color: var(--admin-text-sub, #555D58);">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full {{ $activeCount > 0 ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
            <span>
                <strong>{{ $activeCount }}</strong> fasilitas aktif dan tampil di website{{ $inactiveCount > 0 ? ' (' . $inactiveCount . ' disembunyikan)' : '' }}.
            </span>
        </div>
        @if($totalCount > 1)
            <span class="text-[12px] flex items-center gap-1.5" style="color: var(--admin-text-muted, #7A847E);">
                <i class="fa-solid fa-arrow-down-short-wide text-[11px]" aria-hidden="true"></i>
                <span>Urutan dari atas ke bawah = urutan tampil di website</span>
            </span>
        @endif
    </div>

    {{-- Catatan Peringatan Jika Semua Fasilitas Dimatikan / Disembunyikan --}}
    @if($totalCount > 0 && $activeCount === 0)
        <div class="p-4 rounded-xl border border-amber-300 bg-amber-50 text-amber-950 text-[13px] flex items-start gap-3 shadow-xs">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 text-base shrink-0" aria-hidden="true"></i>
            <div class="space-y-0.5">
                <span class="font-bold text-amber-900">Semua fasilitas saat ini berstatus nonaktif (disembunyikan).</span>
                <p class="text-[12.5px] text-amber-800 m-0 leading-relaxed">
                    Bagian <strong>Fasilitas</strong> di halaman depan website kafe akan kosong / tidak tampil ke pengunjung hingga minimal satu fasilitas diaktifkan kembali.
                </p>
            </div>
        </div>
    @endif

    {{-- 3. Daftar Baris Ringkas Fasilitas (Tinggi 96-110px per Baris) --}}
    @if($facilities->count() > 0)
        <div class="space-y-3" id="facilitiesList">
            @foreach($facilities as $index => $facility)
                @php
                    $photos = $facility->formatted_photos;
                    $features = is_array($facility->features) ? $facility->features : [];
                    $primaryPhoto = !empty($photos) ? $photos[0] : null;
                    $totalPhotos = count($photos);
                    $isFirst = $index === 0;
                    $isLast = $index === ($facilities->count() - 1);
                @endphp

                <div class="admin-card p-3.5 sm:p-4 rounded-2xl border transition-all flex flex-col md:flex-row md:items-center justify-between gap-3.5 min-h-[96px]"
                     id="facility-row-{{ $facility->id }}"
                     data-id="{{ $facility->id }}"
                     style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4);">
                    
                    {{-- Bagian Kiri: Tombol Urutan, Foto Utama, & Detail Fasilitas --}}
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                        
                        {{-- Tombol Naik / Turun Urutan --}}
                        <div class="flex flex-col gap-1 shrink-0">
                            {{-- Tombol Naik --}}
                            <form action="{{ route('admin.facilities.reorder') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                <input type="hidden" name="direction" value="up">
                                <button type="submit"
                                        {{ $isFirst ? 'disabled' : '' }}
                                        class="w-7 h-7 rounded-lg border flex items-center justify-center text-[11px] transition cursor-pointer disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[var(--admin-bg-sidebar-hover,#F0F4F1)]"
                                        style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                                        title="{{ $isFirst ? 'Sudah berada di urutan teratas' : 'Pindahkan naik' }}">
                                    <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
                                    <span class="sr-only">Naik</span>
                                </button>
                            </form>

                            {{-- Tombol Turun --}}
                            <form action="{{ route('admin.facilities.reorder') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                <input type="hidden" name="direction" value="down">
                                <button type="submit"
                                        {{ $isLast ? 'disabled' : '' }}
                                        class="w-7 h-7 rounded-lg border flex items-center justify-center text-[11px] transition cursor-pointer disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[var(--admin-bg-sidebar-hover,#F0F4F1)]"
                                        style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                                        title="{{ $isLast ? 'Sudah berada di urutan terbawah' : 'Pindahkan turun' }}">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                    <span class="sr-only">Turun</span>
                                </button>
                            </form>
                        </div>

                        {{-- Foto Utama Kecil (Rasio 4:3) dengan Penanda Jumlah Foto --}}
                        <div class="w-24 h-18 sm:w-28 sm:h-20 rounded-xl overflow-hidden border shrink-0 relative bg-stone-100 flex items-center justify-center select-none shadow-2xs"
                             style="border-color: var(--admin-border-card, #E6E8E4);">
                            @if($primaryPhoto && !empty($primaryPhoto['src']))
                                <img src="{{ $primaryPhoto['src'] }}" 
                                     alt="{{ $primaryPhoto['alt'] ?? $facility->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-stone-400">
                                    <i class="fa-regular fa-image text-lg" aria-hidden="true"></i>
                                </div>
                            @endif

                            {{-- Penanda "+2 foto" Bila Ada --}}
                            @if($totalPhotos > 1)
                                <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded-md text-[10px] font-semibold text-white bg-black/70 backdrop-blur-xs shadow-xs">
                                    +{{ $totalPhotos - 1 }} foto
                                </span>
                            @endif
                        </div>

                        {{-- Rincian Teks: Judul, Chip Tombol, Poin, & Deskripsi Ringkas --}}
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Judul Fasilitas --}}
                                <h3 class="text-[14px] sm:text-[15px] font-bold truncate m-0 leading-snug"
                                    style="color: var(--admin-text-main, #1B1F1D);">
                                    {{ $facility->title ?: $facility->label }}
                                </h3>

                                {{-- Chip Nama Tombol Pilihan --}}
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold border shrink-0"
                                      style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); border-color: #C3DCD0; color: var(--admin-btn-primary-bg, #2F6B4F);">
                                    Tombol: {{ $facility->tab_name ?: strtoupper($facility->slug) }}
                                </span>

                                {{-- Jumlah Keunggulan --}}
                                <span class="text-[12px] font-medium shrink-0 flex items-center gap-1"
                                      style="color: var(--admin-text-sub, #555D58);">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-[11px]" aria-hidden="true"></i>
                                    <span>{{ count($features) }} keunggulan</span>
                                </span>
                            </div>

                            {{-- Penjelasan Satu Baris (Dipotong Rapi) --}}
                            <p class="text-[12.5px] leading-relaxed truncate m-0 max-w-xl"
                               style="color: var(--admin-text-sub, #555D58);"
                               title="{{ $facility->description }}">
                                {{ $facility->description }}
                            </p>
                        </div>

                    </div>

                    {{-- Bagian Kanan: Saklar Tampil di Website (Toggle) & Tombol Aksi (Edit, Hapus) --}}
                    <div class="flex items-center gap-3 shrink-0 self-end md:self-center pt-2 md:pt-0 border-t md:border-t-0 w-full md:w-auto justify-between md:justify-end"
                         style="border-color: var(--admin-border-card, #E6E8E4);">
                        
                        {{-- Saklar "Tampil di website" (Toggle Sungguhan dengan label On/Off) --}}
                        <form action="{{ route('admin.facilities.toggle', $facility) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <label class="inline-flex items-center gap-2 cursor-pointer select-none" title="{{ $facility->is_active ? 'Klik untuk sembunyikan dari website' : 'Klik untuk tampilkan di website' }}">
                                <input type="checkbox"
                                       class="sr-only peer"
                                       {{ $facility->is_active ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                
                                {{-- Switch Track & Knob --}}
                                <div class="w-10 h-5 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[var(--admin-btn-primary-bg,#2F6B4F)] relative"></div>
                                
                                <span class="text-[12.5px] font-semibold {{ $facility->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                                    {{ $facility->is_active ? 'Tampil' : 'Mati' }}
                                </span>
                            </label>
                        </form>

                        <div class="flex items-center gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                               class="min-h-[36px] sm:min-h-[34px] px-3.5 py-1.5 rounded-xl border text-[13px] font-semibold transition flex items-center gap-1.5 hover:bg-[var(--admin-bg-sidebar-hover,#F0F4F1)] cursor-pointer"
                               style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                               title="Edit data fasilitas ini">
                                <i class="fa-regular fa-pen-to-square text-xs" aria-hidden="true"></i>
                                <span>Edit</span>
                            </a>

                            {{-- Tombol Hapus (Teks Merah Bata, Membuka Dialog Konfirmasi) --}}
                            <button type="button"
                                    onclick="openDeleteFacilityModal({{ $facility->id }}, '{{ addslashes($facility->title ?: $facility->tab_name) }}', '{{ route('admin.facilities.destroy', $facility) }}', '{{ route('admin.facilities.toggle', $facility) }}')"
                                    class="min-h-[36px] sm:min-h-[34px] px-3.5 py-1.5 rounded-xl border border-rose-200 text-rose-700 hover:bg-rose-50 text-[13px] font-semibold transition flex items-center gap-1.5 cursor-pointer"
                                    style="background-color: #FFFFFF;"
                                    title="Hapus fasilitas ini">
                                <i class="fa-regular fa-trash-can text-xs" aria-hidden="true"></i>
                                <span>Hapus</span>
                            </button>
                        </div>

                    </div>

                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State Ramah --}}
        <div class="admin-card p-10 sm:p-14 text-center max-w-md mx-auto space-y-4"
             style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); border-radius: var(--admin-radius-card, 12px);">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto"
                 style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                <i class="fa-solid fa-layer-group text-2xl" aria-hidden="true"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-[16px] font-bold tracking-tight m-0" style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                    Belum Ada Fasilitas
                </h3>
                <p class="text-[13px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                    Tambahkan fasilitas kafe (seperti Indoor, Outdoor, Musholla) lengkap dengan daftar keunggulan dan 1&ndash;3 foto untuk ditampilkan di website.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('admin.facilities.create') }}"
                   class="min-h-[40px] px-5 py-2.5 rounded-xl text-[13.5px] font-semibold transition inline-flex items-center gap-2 shadow-sm cursor-pointer"
                   style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Tambah Fasilitas Pertama</span>
                </a>
            </div>
        </div>
    @endif

</div>

{{-- MODAL KONFIRMASI HAPUS FASILITAS (3 Pilihan: Batal, Sembunyikan Saja, Hapus Permanen) --}}
<div id="deleteFacilityModal" 
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4 transition-opacity duration-200"
     role="dialog"
     aria-modal="true"
     aria-labelledby="deleteModalTitle">
    
    <div class="admin-card w-full max-w-md p-6 rounded-2xl shadow-dropdown space-y-5 animate-scale-up"
         style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4);">
        
        {{-- Header Dialog --}}
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-base" aria-hidden="true"></i>
            </div>
            <div class="space-y-1">
                <h3 id="deleteModalTitle" class="text-[16px] font-bold text-stone-900 m-0" style="font-family: var(--font-heading);">
                    Hapus Fasilitas?
                </h3>
                <p id="deleteModalDescription" class="text-[13px] text-stone-600 leading-relaxed m-0">
                    Apakah Anda yakin ingin menghapus fasilitas ini? Foto ikut terhapus dan tidak bisa dikembalikan.
                </p>
            </div>
        </div>

        {{-- Form Aksi Tersembunyi --}}
        <form id="deleteFacilityForm" method="POST" action="">
            @csrf
            @method('DELETE')
        </form>

        <form id="hideFacilityForm" method="POST" action="">
            @csrf
        </form>

        {{-- 3 Tombol Aksi: Batal, Sembunyikan Saja, Hapus --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2.5 pt-2 border-t"
             style="border-color: var(--admin-border-card, #E6E8E4);">
            
            {{-- 1. Batal --}}
            <button type="button" 
                    onclick="closeDeleteFacilityModal()"
                    class="min-h-[38px] px-4 py-2 rounded-xl border text-[13px] font-semibold transition cursor-pointer"
                    style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                Batal
            </button>

            {{-- 2. Sembunyikan Saja (Nonaktifkan tanpa menghapus data / foto) --}}
            <button type="button" 
                    onclick="submitHideFacility()"
                    class="min-h-[38px] px-4 py-2 rounded-xl border border-amber-300 bg-amber-50 text-amber-900 hover:bg-amber-100 text-[13px] font-semibold transition cursor-pointer flex items-center justify-center gap-1.5"
                    title="Nonaktifkan agar tidak tampil di website tanpa menghapus data">
                <i class="fa-regular fa-eye-slash text-xs" aria-hidden="true"></i>
                <span>Sembunyikan Saja</span>
            </button>

            {{-- 3. Hapus Permanen --}}
            <button type="button" 
                    onclick="submitDeleteFacility()"
                    class="min-h-[38px] px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-[13px] font-semibold transition cursor-pointer flex items-center justify-center gap-1.5 shadow-xs"
                    title="Hapus fasilitas dan file foto secara permanen">
                <i class="fa-solid fa-trash-can text-xs" aria-hidden="true"></i>
                <span>Hapus</span>
            </button>

        </div>
    </div>
</div>

@push('scripts')
<script>
    let activeDeleteUrl = '';
    let activeToggleUrl = '';

    function openDeleteFacilityModal(id, title, deleteUrl, toggleUrl) {
        activeDeleteUrl = deleteUrl;
        activeToggleUrl = toggleUrl;

        const modal = document.getElementById('deleteFacilityModal');
        const desc = document.getElementById('deleteModalDescription');
        const deleteForm = document.getElementById('deleteFacilityForm');
        const hideForm = document.getElementById('hideFacilityForm');

        if (desc) {
            desc.innerHTML = `Hapus fasilitas <strong>${title}</strong>? Foto ikut terhapus dan tidak bisa dikembalikan.`;
        }
        if (deleteForm) deleteForm.action = deleteUrl;
        if (hideForm) hideForm.action = toggleUrl;

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDeleteFacilityModal() {
        const modal = document.getElementById('deleteFacilityModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitDeleteFacility() {
        const deleteForm = document.getElementById('deleteFacilityForm');
        if (deleteForm) deleteForm.submit();
    }

    function submitHideFacility() {
        const hideForm = document.getElementById('hideFacilityForm');
        if (hideForm) hideForm.submit();
    }

    // Tutup modal jika klik tombol Esc
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteFacilityModal();
        }
    });

    // Tutup modal jika klik di luar box
    document.getElementById('deleteFacilityModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteFacilityModal();
        }
    });
</script>
@endpush
@endsection