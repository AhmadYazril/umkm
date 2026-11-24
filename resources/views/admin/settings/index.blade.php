@extends('layouts.admin')
@section('title', 'Pengaturan Umum Kafe — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-2xs anim-fade-up">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any() || session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold space-y-2 shadow-2xs anim-fade-up">
            <div class="flex items-center gap-2 font-bold">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>{{ session('error') ? 'Terjadi kesalahan unggah:' : 'Terdapat kesalahan pada formulir pengaturan:' }}</span>
            </div>
            @if(session('error'))
                <p class="leading-relaxed pl-5 text-[12.5px] font-medium">{{ session('error') }}</p>
            @endif
            @if($errors->any())
                <ul class="list-disc list-inside space-y-0.5 text-rose-800 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    {{-- Top Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Modul 10 &bull; Pengaturan Umum &amp; Identitas Kafe</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Pengaturan Umum Kafe
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kelola identitas dasar kafe (nama, tagline, logo, dan favicon) serta navigasi pintas ke modul operasional lainnya.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('home') }}" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Lihat Beranda Website Pelanggan">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-muted-text"></i>
                <span>Buka Website</span>
            </a>
            
            <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sesi admin?');">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-pure-white hover:bg-rose-50 border border-hairline hover:border-rose-300 text-rose-700 font-bold text-xs transition shadow-xs flex items-center gap-2 btn-press">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Grid: General Settings (7 Cols) & Shortcut Links (5 Cols) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- Left Column: General Branding Form (7 Cols) --}}
        <div class="lg:col-span-7 space-y-6">

            <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-6">
                <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
                    <div class="w-8 h-8 rounded-xl bg-warm-stone text-ink-black flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-ink-black">Identitas &amp; Branding Company Profile</h2>
                        <p class="text-[11px] text-muted-text">Nama resmi, slogan utama, logo merek, dan favicon peramban</p>
                    </div>
                </div>

                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Nama Kafe --}}
                    <div class="space-y-1.5">
                        <label for="cafe_name" class="text-xs font-bold text-ink-black flex items-center gap-1">
                            <span>Nama Kafe Resmi</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="cafe_name" id="cafe_name" required
                               value="{{ old('cafe_name', $settings['cafe_name'] ?? config('nucomu.brand.name', 'Nucomu Cafe')) }}"
                               placeholder="Contoh: Nucomu Cafe"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        <p class="text-[11px] text-muted-text">Tampil di judul navbar, footer, metadata SEO, dan struk belanja.</p>
                        @error('cafe_name')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tagline / Slogan --}}
                    <div class="space-y-1.5">
                        <label for="cafe_tagline" class="text-xs font-bold text-ink-black">
                            Tagline / Slogan Utama
                        </label>
                        <input type="text" name="cafe_tagline" id="cafe_tagline"
                               value="{{ old('cafe_tagline', $settings['cafe_tagline'] ?? config('nucomu.brand.headline', 'Tempat Kembali, Rasa yang Dirindukan.')) }}"
                               placeholder="Contoh: Tempat Kembali, Rasa yang Dirindukan."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        <p class="text-[11px] text-muted-text">Slogan yang tampil pada header dan kartu profil beranda.</p>
                        @error('cafe_tagline')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sub-Tagline / Konsep Singkat --}}
                    <div class="space-y-1.5">
                        <label for="cafe_subtitle" class="text-xs font-bold text-ink-black">
                            Konsep Singkat / Sub-Tagline
                        </label>
                        <input type="text" name="cafe_subtitle" id="cafe_subtitle"
                               value="{{ old('cafe_subtitle', $settings['cafe_subtitle'] ?? config('nucomu.brand.subname', 'SLOW HOSPITALITY & ROASTERY')) }}"
                               placeholder="Contoh: SLOW HOSPITALITY & ROASTERY"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        <p class="text-[11px] text-muted-text">Label singkat di bawah logo pada navbar dan footer.</p>
                    </div>

                    @php
                        $logoLimits = \App\Support\FileUploadHelper::getEffectiveLimit(2048 * 1024, 'logo kafe');
                        $faviconLimits = \App\Support\FileUploadHelper::getEffectiveLimit(1024 * 1024, 'favicon kafe');
                    @endphp

                    {{-- Logo Upload with Live Preview --}}
                    <div class="space-y-2 pt-3 border-t border-hairline/60">
                        <label class="text-xs font-bold text-ink-black block">Logo Resmi Kafe</label>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-xl bg-warm-canvas/60 border border-hairline">
                            @php
                                $logoFile = $settings['cafe_logo'] ?? null;
                                $logoSrc = ($logoFile && file_exists(public_path('uploads/branding/' . $logoFile)))
                                    ? asset('uploads/branding/' . $logoFile)
                                    : asset('images/nucomu/nucomu_logo_brand.png');
                            @endphp
                            <div class="w-28 h-14 bg-pure-white border border-hairline rounded-xl flex items-center justify-center p-2 shrink-0 shadow-2xs">
                                <img id="previewLogoImg" src="{{ $logoSrc }}" alt="Logo Kafe" class="max-h-full max-w-full object-contain">
                            </div>

                            <div class="space-y-1.5 flex-1">
                                <input type="file" name="cafe_logo" id="inputLogo" accept=".png,.jpg,.jpeg,.webp,.svg"
                                       data-max-bytes="{{ $logoLimits['effective_bytes'] ?? $logoLimits['limit_bytes'] ?? 2097152 }}"
                                       data-allowed-ext="png,jpg,jpeg,webp,svg"
                                       data-feedback-target="#logoFeedback"
                                       class="block w-full text-xs text-muted-text file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-ink-black file:text-white hover:file:bg-charcoal file:cursor-pointer file:transition cursor-pointer">
                                <div id="logoFeedback" class="text-left"></div>
                                <p class="text-[11px] text-muted-text">Format: PNG transparan (disarankan), JPG, WebP &bull; Maks {{ $logoLimits['formatted'] ?? $logoLimits['limit_formatted'] ?? '2 MB' }}.</p>
                                @if(!empty($logoLimits['is_throttled'] ?? $logoLimits['is_limited_by_server']))
                                    <p class="text-[10px] text-amber-800 font-medium">
                                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Batas server PHP saat ini ({{ $logoLimits['server_limit_formatted'] ?? '2 MB' }}) lebih kecil dari batas aplikasi (2 MB).
                                    </p>
                                @endif
                            </div>
                        </div>
                        @error('cafe_logo')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Favicon Upload with Live Preview --}}
                    <div class="space-y-2 pt-3 border-t border-hairline/60">
                        <label class="text-xs font-bold text-ink-black block">Favicon Tab Browser</label>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-xl bg-warm-canvas/60 border border-hairline">
                            @php
                                $favFile = $settings['cafe_favicon'] ?? null;
                                $favSrc = ($favFile && file_exists(public_path('uploads/branding/' . $favFile)))
                                    ? asset('uploads/branding/' . $favFile)
                                    : asset('images/nucomu/nucomu_logo_icon.png');
                            @endphp
                            <div class="w-14 h-14 bg-pure-white border border-hairline rounded-xl flex items-center justify-center p-2 shrink-0 shadow-2xs">
                                <img id="previewFaviconImg" src="{{ $favSrc }}" alt="Favicon" class="w-8 h-8 object-contain">
                            </div>

                            <div class="space-y-1.5 flex-1">
                                <input type="file" name="cafe_favicon" id="inputFavicon" accept=".ico,.png,.webp,.svg"
                                       data-max-bytes="{{ $faviconLimits['effective_bytes'] ?? $faviconLimits['limit_bytes'] ?? 1048576 }}"
                                       data-allowed-ext="ico,png,webp,jpg,jpeg,svg"
                                       data-feedback-target="#faviconFeedback"
                                       class="block w-full text-xs text-muted-text file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-ink-black file:text-white hover:file:bg-charcoal file:cursor-pointer file:transition cursor-pointer">
                                <div id="faviconFeedback" class="text-left"></div>
                                <p class="text-[11px] text-muted-text">Ikon kecil tab peramban. Format: ICO, PNG (32x32 atau 64x64 px) &bull; Maks {{ $faviconLimits['formatted'] ?? $faviconLimits['limit_formatted'] ?? '1 MB' }}.</p>
                                @if(!empty($faviconLimits['is_throttled'] ?? $faviconLimits['is_limited_by_server']))
                                    <p class="text-[10px] text-amber-800 font-medium">
                                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Batas server PHP saat ini ({{ $faviconLimits['server_limit_formatted'] ?? '2 MB' }}) lebih kecil dari batas aplikasi (1 MB).
                                    </p>
                                @endif
                            </div>
                        </div>
                        @error('cafe_favicon')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    @php
                        $isDashboardChartHidden = in_array((string) ($settings['hide_dashboard_chart'] ?? '0'), ['1', 'true', 'yes'], true)
                            || in_array((string) ($settings['show_dashboard_chart'] ?? '1'), ['0', 'false', 'no'], true);
                    @endphp

                    {{-- Pengaturan Tampilan Grafik Keuangan di Dashboard --}}
                    <div class="space-y-2.5 pt-3 border-t border-hairline/60">
                        <label class="text-xs font-bold text-ink-black block">Tampilan Grafik Keuangan di Dashboard</label>
                        <div class="p-4 rounded-xl bg-warm-canvas/60 border border-hairline flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-ink-black">Grafik Keuangan Penjualan</span>
                                    <span id="badgeMainChartSetting" class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase {{ ! $isDashboardChartHidden ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }}">
                                        {{ ! $isDashboardChartHidden ? 'Ditampilkan' : 'Disembunyikan (Hide)' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-muted-text leading-relaxed">
                                    Jika disembunyikan (Hide), grafik keuangan penjualan hilang dari Dashboard dan proses kalkulasi/script grafik tidak dijalankan.
                                </p>
                            </div>
                            <div class="shrink-0 flex items-center gap-2.5">
                                <input type="hidden" name="hide_dashboard_chart" id="inputHideDashboardChart" value="{{ $isDashboardChartHidden ? '1' : '0' }}">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox"
                                           id="toggleShowDashboardChartCheckbox"
                                           class="sr-only peer"
                                           {{ ! $isDashboardChartHidden ? 'checked' : '' }}
                                           onchange="document.getElementById('inputHideDashboardChart').value = this.checked ? '0' : '1'; var b = document.getElementById('badgeMainChartSetting'); if (b) { b.textContent = this.checked ? 'Ditampilkan' : 'Disembunyikan (Hide)'; b.className = this.checked ? 'px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200' : 'px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase bg-warm-stone text-muted-text border border-hairline'; }">
                                    <div class="w-11 h-6 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2F6B4F]"></div>
                                    <span class="ml-2.5 text-xs font-bold text-ink-black">Tampilkan Grafik</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-hairline flex items-center justify-end">
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold uppercase tracking-wider transition-all shadow-xs flex items-center gap-2 btn-press">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Identitas Kafe</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- System & Environment Info Card --}}
            <div class="bg-pure-white rounded-2xl border border-hairline p-5 shadow-xs space-y-3">
                <span class="text-xs font-bold text-ink-black flex items-center gap-2">
                    <i class="fa-solid fa-server text-muted-text"></i>
                    <span>Spesifikasi Sistem &amp; Lingkungan Operasional</span>
                </span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-warm-canvas/50 border border-hairline">
                        <span class="text-[10px] text-muted-text uppercase font-mono block">PHP Versi</span>
                        <span class="font-bold text-ink-black">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="p-3 rounded-xl bg-warm-canvas/50 border border-hairline">
                        <span class="text-[10px] text-muted-text uppercase font-mono block">Laravel</span>
                        <span class="font-bold text-ink-black">v{{ app()->version() }}</span>
                    </div>
                    <div class="p-3 rounded-xl bg-warm-canvas/50 border border-hairline">
                        <span class="text-[10px] text-muted-text uppercase font-mono block">Database</span>
                        <span class="font-bold text-emerald-700">MySQL Terhubung</span>
                    </div>
                    <div class="p-3 rounded-xl bg-warm-canvas/50 border border-hairline">
                        <span class="text-[10px] text-muted-text uppercase font-mono block">Status Web</span>
                        <span class="font-bold text-emerald-700">Online &bull; Live</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Site Theme Control & Shortcut Links (5 Cols) --}}
        <div class="lg:col-span-5 space-y-5">

            {{-- Global Public Site Theme Control Card (Admin Only) --}}
            @php
                $currentSiteTheme = ($siteTheme ?? \App\Models\Setting::getSiteTheme()) === 'dark' ? 'dark' : 'light';
            @endphp
            <div class="bg-pure-white rounded-2xl border border-hairline p-5 shadow-xs space-y-4" id="adminSiteThemeCard" data-saved-theme="{{ $currentSiteTheme }}">
                <div class="flex items-start justify-between gap-3 border-b border-hairline pb-3">
                    <div class="flex items-center gap-2.5">
                        <div id="themeHeaderIconWrap" class="w-9 h-9 rounded-xl {{ $currentSiteTheme === 'dark' ? 'bg-neutral-900 text-amber-200 border border-neutral-800' : 'bg-amber-50 text-amber-700 border border-amber-200' }} flex items-center justify-center shrink-0 text-sm transition-colors">
                            <i id="themeHeaderIcon" class="fa-solid {{ $currentSiteTheme === 'dark' ? 'fa-moon' : 'fa-sun' }}"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-ink-black uppercase tracking-wider">
                                Tema Tampilan Landing Page
                            </h3>
                            <p class="text-[11px] text-muted-text mt-0.5">
                                Aktif untuk semua pengunjung:
                                <strong id="savedThemeLabel" class="text-ink-black">
                                    {{ $currentSiteTheme === 'dark' ? 'Mode Gelap (Dark)' : 'Mode Terang (Light)' }}
                                </strong>
                            </p>
                        </div>
                    </div>
                    <span id="themeDraftBadge" class="hidden px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider shrink-0">
                        Belum Disimpan
                    </span>
                </div>

                {{-- Inline Notification Banner (Success / Error) --}}
                <div id="siteThemeAlert" class="hidden p-3 rounded-xl text-xs font-semibold flex items-center justify-between gap-2 transition-all" role="status" aria-live="polite">
                    <div class="flex items-center gap-2 min-w-0">
                        <i id="siteThemeAlertIcon" class="fa-solid fa-circle-check shrink-0"></i>
                        <span id="siteThemeAlertMessage" class="leading-snug"></span>
                    </div>
                    <button type="button" onclick="document.getElementById('siteThemeAlert').classList.add('hidden')" class="opacity-70 hover:opacity-100 shrink-0" aria-label="Tutup notifikasi">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form id="siteThemeForm" action="{{ route('admin.settings.theme.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Radio Picker: Terang / Gelap --}}
                    <fieldset class="space-y-2">
                        <legend class="text-[11px] font-bold uppercase tracking-wider text-muted-text">
                            Pilih Tema Global Pengunjung
                        </legend>
                        <div class="grid grid-cols-2 gap-2.5">
                            <label for="siteThemeLight"
                                   data-theme-card="light"
                                   class="site-theme-option relative flex flex-col gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all {{ $currentSiteTheme === 'light' ? 'border-ink-black bg-warm-canvas/60 shadow-2xs' : 'border-hairline hover:border-ink-black/40 bg-pure-white' }}">
                                <input type="radio"
                                       id="siteThemeLight"
                                       name="theme"
                                       value="light"
                                       class="sr-only"
                                       {{ $currentSiteTheme === 'light' ? 'checked' : '' }}>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-black">
                                        <i class="fa-solid fa-sun text-amber-500"></i>
                                        <span>Terang</span>
                                    </span>
                                    <span data-theme-check="light" class="w-4 h-4 rounded-full border flex items-center justify-center text-[9px] {{ $currentSiteTheme === 'light' ? 'bg-ink-black border-ink-black text-white' : 'border-hairline text-transparent' }}">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-[#F9F9F9] border border-neutral-300" title="Background #F9F9F9"></span>
                                    <span class="w-4 h-4 rounded-full bg-[#FFFFFF] border border-neutral-300" title="Card #FFFFFF"></span>
                                    <span class="w-4 h-4 rounded-full bg-[#191919] border border-neutral-700" title="Text #191919"></span>
                                    <span class="text-[10px] text-muted-text ml-1">Default</span>
                                </div>
                            </label>

                            <label for="siteThemeDark"
                                   data-theme-card="dark"
                                   class="site-theme-option relative flex flex-col gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all {{ $currentSiteTheme === 'dark' ? 'border-ink-black bg-warm-canvas/60 shadow-2xs' : 'border-hairline hover:border-ink-black/40 bg-pure-white' }}">
                                <input type="radio"
                                       id="siteThemeDark"
                                       name="theme"
                                       value="dark"
                                       class="sr-only"
                                       {{ $currentSiteTheme === 'dark' ? 'checked' : '' }}>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-black">
                                        <i class="fa-solid fa-moon text-indigo-600"></i>
                                        <span>Gelap</span>
                                    </span>
                                    <span data-theme-check="dark" class="w-4 h-4 rounded-full border flex items-center justify-center text-[9px] {{ $currentSiteTheme === 'dark' ? 'bg-ink-black border-ink-black text-white' : 'border-hairline text-transparent' }}">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-[#0C0C0C] border border-neutral-600" title="Background #0C0C0C"></span>
                                    <span class="w-4 h-4 rounded-full bg-[#161616] border border-neutral-600" title="Card #161616"></span>
                                    <span class="w-4 h-4 rounded-full bg-[#F5F2EE] border border-neutral-400" title="Accent #F5F2EE"></span>
                                    <span class="text-[10px] text-muted-text ml-1">Dark Glass</span>
                                </div>
                            </label>
                        </div>
                    </fieldset>

                    {{-- Interactive Live Mini-Preview Before Saving --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-[10.5px]">
                            <span class="font-bold uppercase tracking-wider text-muted-text">Pratinjau Langsung</span>
                            <span id="themePreviewCaption" class="font-mono text-muted-text">
                                {{ $currentSiteTheme === 'dark' ? 'Preview: Gelap (#0C0C0C)' : 'Preview: Terang (#F9F9F9)' }}
                            </span>
                        </div>

                        <div id="siteThemePreviewBox"
                             class="rounded-2xl overflow-hidden border transition-all duration-300 select-none"
                             style="background-color: {{ $currentSiteTheme === 'dark' ? '#0C0C0C' : '#F9F9F9' }}; border-color: {{ $currentSiteTheme === 'dark' ? 'rgba(255,255,255,0.16)' : '#E5E5E5' }}; color: {{ $currentSiteTheme === 'dark' ? '#F5F5F5' : '#191919' }};">
                            {{-- Mini Navbar --}}
                            <div id="previewNavBar"
                                 class="px-3.5 py-2.5 flex items-center justify-between border-b transition-colors duration-300"
                                 style="background-color: {{ $currentSiteTheme === 'dark' ? 'rgba(12,12,12,0.92)' : 'rgba(255,255,255,0.92)' }}; border-color: {{ $currentSiteTheme === 'dark' ? 'rgba(255,255,255,0.10)' : 'rgba(0,0,0,0.08)' }};">
                                <span class="font-serif font-bold text-[11px] tracking-wider uppercase">NUCOMU</span>
                                <div class="flex items-center gap-2 text-[9px] opacity-75">
                                    <span>Tentang</span>
                                    <span>Menu</span>
                                    <span>Galeri</span>
                                </div>
                                <span id="previewNavCart"
                                      class="px-2 py-0.5 rounded-full text-[8.5px] font-bold transition-colors duration-300"
                                      style="background-color: {{ $currentSiteTheme === 'dark' ? '#F5F2EE' : '#111111' }}; color: {{ $currentSiteTheme === 'dark' ? '#111111' : '#FFFFFF' }};">
                                    Cart
                                </span>
                            </div>

                            {{-- Mini Landing Content --}}
                            <div class="p-3.5 space-y-2.5">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="space-y-1 min-w-0">
                                        <span id="previewEyebrow"
                                              class="text-[8.5px] font-bold uppercase tracking-[0.18em] block transition-colors duration-300"
                                              style="color: {{ $currentSiteTheme === 'dark' ? '#B5B5B5' : '#555555' }};">
                                            NUCOMU CAFE TULUNGAGUNG
                                        </span>
                                        <p class="font-serif text-sm font-bold leading-tight">
                                            Crafted Coffee &amp; Warm Space
                                        </p>
                                    </div>
                                    <span id="previewPrimaryBtn"
                                          class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider shrink-0 transition-colors duration-300"
                                          style="background-color: {{ $currentSiteTheme === 'dark' ? '#F5F2EE' : '#1F1F1F' }}; color: {{ $currentSiteTheme === 'dark' ? '#111111' : '#FFFFFF' }};">
                                        Pesan
                                    </span>
                                </div>

                                {{-- Mini Menu Card --}}
                                <div id="previewMenuCard"
                                     class="rounded-xl p-2.5 border flex items-center justify-between gap-2 transition-colors duration-300"
                                     style="background-color: {{ $currentSiteTheme === 'dark' ? '#161616' : '#FFFFFF' }}; border-color: {{ $currentSiteTheme === 'dark' ? 'rgba(255,255,255,0.14)' : '#E5E5E5' }};">
                                    <div class="min-w-0">
                                        <p class="text-[10.5px] font-bold truncate">Nucomu Signature Latte</p>
                                        <p id="previewCardMuted"
                                           class="text-[9px] transition-colors duration-300"
                                           style="color: {{ $currentSiteTheme === 'dark' ? '#B5B5B5' : '#555555' }};">
                                            Single origin Sendang · Rp 22.000
                                        </p>
                                    </div>
                                    <span id="previewOutlineBtn"
                                          class="px-2 py-0.5 rounded-full text-[8.5px] font-semibold border transition-colors duration-300"
                                          style="border-color: {{ $currentSiteTheme === 'dark' ? 'rgba(255,255,255,0.45)' : 'rgba(25,25,25,0.34)' }};">
                                        Detail
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-[11px] text-muted-text leading-relaxed">
                        Pengunjung publik tidak melihat tombol ganti tema. Perubahan tema disimpan di server dan langsung berlaku secara global.
                    </p>

                    <div class="flex items-center gap-2 pt-0.5">
                        <button type="submit"
                                id="saveSiteThemeBtn"
                                class="flex-1 py-2.5 px-4 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold uppercase tracking-wider transition shadow-2xs flex items-center justify-center gap-2 btn-press cursor-pointer disabled:opacity-60">
                            <i id="saveSiteThemeIcon" class="fa-solid fa-floppy-disk"></i>
                            <span id="saveSiteThemeText">Simpan Tema</span>
                        </button>
                        <a href="{{ route('home') }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="py-2.5 px-3.5 rounded-xl bg-warm-stone hover:bg-hairline text-ink-black text-xs font-bold border border-hairline transition flex items-center gap-1.5 shrink-0"
                           title="Buka Landing Page di tab baru">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </form>
            </div>

            {{-- Quick Toggle Card: Grafik Keuangan Penjualan Dashboard --}}
            <div class="bg-pure-white rounded-2xl border border-hairline p-5 shadow-xs space-y-4">
                <div class="flex items-start justify-between gap-3 border-b border-hairline pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl {{ ! $isDashboardChartHidden ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }} flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid {{ ! $isDashboardChartHidden ? 'fa-chart-line' : 'fa-eye-slash' }}"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-ink-black uppercase tracking-wider">
                                Grafik Keuangan Dashboard
                            </h3>
                            <p class="text-[11px] text-muted-text mt-0.5">
                                Status saat ini:
                                <strong class="{{ ! $isDashboardChartHidden ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ ! $isDashboardChartHidden ? 'AKTIF (Ditampilkan)' : 'HIDE (Disembunyikan & Nonaktif)' }}
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>

                <p class="text-[11.5px] text-muted-text leading-relaxed">
                    @if($isDashboardChartHidden)
                        Grafik keuangan penjualan sedang dalam posisi <strong>Hide</strong>. Grafik disembunyikan dari halaman Dashboard dan query maupun script grafik <strong>tidak berjalan</strong>.
                    @else
                        Grafik keuangan penjualan sedang <strong>Aktif</strong> di halaman Dashboard untuk memantau tren pendapatan harian, mingguan, dan bulanan.
                    @endif
                </p>

                <form action="{{ route('admin.settings.toggleChart') }}" method="POST" class="flex items-center justify-between gap-3 pt-1">
                    @csrf
                    <input type="hidden" name="hide_dashboard_chart" value="{{ $isDashboardChartHidden ? '0' : '1' }}">
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-2xs flex items-center justify-center gap-2 btn-press cursor-pointer {{ $isDashboardChartHidden ? 'bg-[#2F6B4F] hover:bg-[#24563E] text-white' : 'bg-warm-stone hover:bg-rose-50 text-ink-black hover:text-rose-800 border border-hairline hover:border-rose-300' }}">
                        <i class="fa-solid {{ $isDashboardChartHidden ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                        <span>{{ $isDashboardChartHidden ? 'Tampilkan Grafik di Dashboard' : 'Sembunyikan (Hide) Grafik Dashboard' }}</span>
                    </button>
                </form>
            </div>

            {{-- Quick Toggle Card: Layanan Delivery Kurir Zendo (Hanya Zendo yang di-Hide, GrabFood Tetap Aktif) --}}
            @php
                $txSettingsCard = app(\App\Http\Controllers\NucomuController::class)->getTransactionSettings();
                $isZendoActive = (bool) ($txSettingsCard['order_mode_zendo'] ?? true);
            @endphp
            <div class="bg-pure-white rounded-2xl border border-hairline p-5 shadow-xs space-y-4">
                <div class="flex items-start justify-between gap-3 border-b border-hairline pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl {{ $isZendoActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }} flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid {{ $isZendoActive ? 'fa-motorcycle' : 'fa-eye-slash' }}"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-ink-black uppercase tracking-wider">
                                Layanan Delivery Zendo
                            </h3>
                            <p class="text-[11px] text-muted-text mt-0.5">
                                Status saat ini:
                                <strong class="{{ $isZendoActive ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $isZendoActive ? 'AKTIF (Ditampilkan)' : 'HIDE (Zendo Disembunyikan)' }}
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>

                <p class="text-[11.5px] text-muted-text leading-relaxed">
                    @if(!$isZendoActive)
                        Fitur <strong>Delivery via WhatsApp (Zendo)</strong> sedang dalam posisi <strong>Hide</strong> di beranda dan modal keranjang. Kartu <strong>GrabFood</strong> dan <strong>Reservasi Meja</strong> tetap tampil normal.
                    @else
                        Fitur <strong>Delivery via WhatsApp (Zendo)</strong> sedang <strong>Aktif</strong> bersama <strong>GrabFood</strong> dan <strong>Reservasi Meja</strong> di halaman depan.
                    @endif
                </p>

                <form action="{{ route('admin.settings.update') }}" method="POST" class="flex items-center justify-between gap-3 pt-1">
                    @csrf
                    <input type="hidden" name="order_mode_zendo" value="{{ $isZendoActive ? '0' : '1' }}">
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-2xs flex items-center justify-center gap-2 btn-press cursor-pointer {{ !$isZendoActive ? 'bg-[#2F6B4F] hover:bg-[#24563E] text-white' : 'bg-warm-stone hover:bg-rose-50 text-ink-black hover:text-rose-800 border border-hairline hover:border-rose-300' }}">
                        <i class="fa-solid {{ !$isZendoActive ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                        <span>{{ !$isZendoActive ? 'Tampilkan Layanan Zendo' : 'Sembunyikan (Hide) Layanan Zendo' }}</span>
                    </button>
                </form>
            </div>
            
            <div class="bg-pure-white rounded-2xl border border-hairline p-5 shadow-xs space-y-4">
                <div class="border-b border-hairline pb-3">
                    <h3 class="text-xs font-bold text-ink-black uppercase tracking-wider">
                        Pintasan Pengaturan Terpusat
                    </h3>
                    <p class="text-[11px] text-muted-text mt-0.5">
                        Setiap modul memiliki halaman pengaturan tersendiri tanpa data ganda:
                    </p>
                </div>

                {{-- Shortcut 1: Kontak & Sosmed (Modul 9) --}}
                <a href="{{ route('admin.contact.index') }}"
                   class="p-3.5 rounded-xl border border-hairline hover:border-ink-black/40 bg-warm-canvas/40 hover:bg-pure-white transition flex items-center justify-between group shadow-2xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-address-book"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-ink-black block group-hover:text-amber-800 transition">Kontak, Peta &amp; Sosmed</span>
                            <span class="text-[11px] text-muted-text truncate block">WhatsApp cafe, maps embed, Instagram, TikTok</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-muted-text group-hover:translate-x-1 transition-transform"></i>
                </a>

                {{-- Shortcut 2: Transaksi & Kurir Zendo (Modul 8) --}}
                <a href="{{ route('admin.orders.index') }}"
                   class="p-3.5 rounded-xl border border-hairline hover:border-ink-black/40 bg-warm-canvas/40 hover:bg-pure-white transition flex items-center justify-between group shadow-2xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-ink-black block group-hover:text-blue-800 transition">Transaksi &amp; Mode Pesanan</span>
                            <span class="text-[11px] text-muted-text truncate block">Zendo Delivery, GrabFood, barcode kasir</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-muted-text group-hover:translate-x-1 transition-transform"></i>
                </a>

                {{-- Shortcut 3: Form Builder Reservasi (Modul 7) --}}
                <a href="{{ route('admin.reservations.index') }}"
                   class="p-3.5 rounded-xl border border-hairline hover:border-ink-black/40 bg-warm-canvas/40 hover:bg-pure-white transition flex items-center justify-between group shadow-2xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 border border-purple-200 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-ink-black block group-hover:text-purple-800 transition">Form Builder Reservasi</span>
                            <span class="text-[11px] text-muted-text truncate block">Atur kolom formulir &amp; nomor tujuan WA</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-muted-text group-hover:translate-x-1 transition-transform"></i>
                </a>

                {{-- Shortcut 4: Banner Promo (Modul 1) --}}
                <a href="{{ route('admin.banners.index') }}"
                   class="p-3.5 rounded-xl border border-hairline hover:border-ink-black/40 bg-warm-canvas/40 hover:bg-pure-white transition flex items-center justify-between group shadow-2xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-ink-black block group-hover:text-amber-800 transition">Banner Promo Countdown</span>
                            <span class="text-[11px] text-muted-text truncate block">Timer durasi otomatis &amp; popup beranda</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-muted-text group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            {{-- Logout & Sesi Keamanan Card --}}
            <div class="bg-rose-50/50 rounded-2xl border border-rose-200 p-5 shadow-xs space-y-3">
                <span class="text-xs font-bold text-rose-950 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-rose-600"></i>
                    <span>Keluar dari Sesi Admin</span>
                </span>
                <p class="text-[11px] text-rose-800 leading-relaxed">
                    Pastikan seluruh perubahan telah tersimpan sebelum mengakhiri sesi pengelolaan panel.
                </p>
                <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun admin?');">
                    @csrf
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider transition shadow-2xs flex items-center justify-center gap-2 btn-press">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar Akun Admin (Logout)</span>
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
(function() {
    // Live preview for Logo
    var inputLogo = document.getElementById('inputLogo');
    var previewLogoImg = document.getElementById('previewLogoImg');
    if (inputLogo && previewLogoImg) {
        inputLogo.addEventListener('change', function(e) {
            var file = e.target.files && e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    previewLogoImg.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Live preview for Favicon
    var inputFavicon = document.getElementById('inputFavicon');
    var previewFaviconImg = document.getElementById('previewFaviconImg');
    if (inputFavicon && previewFaviconImg) {
        inputFavicon.addEventListener('change', function(e) {
            var file = e.target.files && e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    previewFaviconImg.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Global Public Site Theme Selector + Live Mini-Preview + AJAX Save
    var themeCard = document.getElementById('adminSiteThemeCard');
    var themeForm = document.getElementById('siteThemeForm');
    if (themeCard && themeForm) {
        var themeRadios = themeForm.querySelectorAll('input[name="theme"]');
        var draftBadge = document.getElementById('themeDraftBadge');
        var savedLabel = document.getElementById('savedThemeLabel');
        var headerIconWrap = document.getElementById('themeHeaderIconWrap');
        var headerIcon = document.getElementById('themeHeaderIcon');
        var previewBox = document.getElementById('siteThemePreviewBox');
        var previewNavBar = document.getElementById('previewNavBar');
        var previewNavCart = document.getElementById('previewNavCart');
        var previewEyebrow = document.getElementById('previewEyebrow');
        var previewPrimaryBtn = document.getElementById('previewPrimaryBtn');
        var previewMenuCard = document.getElementById('previewMenuCard');
        var previewCardMuted = document.getElementById('previewCardMuted');
        var previewOutlineBtn = document.getElementById('previewOutlineBtn');
        var previewCaption = document.getElementById('themePreviewCaption');
        var alertBox = document.getElementById('siteThemeAlert');
        var alertIcon = document.getElementById('siteThemeAlertIcon');
        var alertMsg = document.getElementById('siteThemeAlertMessage');
        var saveBtn = document.getElementById('saveSiteThemeBtn');
        var saveIcon = document.getElementById('saveSiteThemeIcon');
        var saveText = document.getElementById('saveSiteThemeText');

        function getSelectedTheme() {
            var checked = themeForm.querySelector('input[name="theme"]:checked');
            return checked && checked.value === 'dark' ? 'dark' : 'light';
        }

        function showThemeAlert(type, message) {
            if (!alertBox || !alertMsg || !alertIcon) return;
            alertBox.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-900', 'bg-rose-50', 'border-rose-200', 'text-rose-900');
            alertBox.classList.add('border');
            if (type === 'success') {
                alertBox.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-900');
                alertIcon.className = 'fa-solid fa-circle-check text-emerald-600 shrink-0';
            } else {
                alertBox.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-900');
                alertIcon.className = 'fa-solid fa-triangle-exclamation text-rose-600 shrink-0';
            }
            alertMsg.textContent = message;
        }

        function renderThemePreview(mode) {
            var isDark = mode === 'dark';
            var savedTheme = themeCard.getAttribute('data-saved-theme') || 'light';

            if (draftBadge) {
                draftBadge.classList.toggle('hidden', mode === savedTheme);
            }

            themeForm.querySelectorAll('[data-theme-card]').forEach(function(labelEl) {
                var cardMode = labelEl.getAttribute('data-theme-card');
                var isSelected = cardMode === mode;
                labelEl.classList.toggle('border-ink-black', isSelected);
                labelEl.classList.toggle('bg-warm-canvas/60', isSelected);
                labelEl.classList.toggle('shadow-2xs', isSelected);
                labelEl.classList.toggle('border-hairline', !isSelected);
                labelEl.classList.toggle('bg-pure-white', !isSelected);

                var checkEl = labelEl.querySelector('[data-theme-check]');
                if (checkEl) {
                    checkEl.classList.toggle('bg-ink-black', isSelected);
                    checkEl.classList.toggle('border-ink-black', isSelected);
                    checkEl.classList.toggle('text-white', isSelected);
                    checkEl.classList.toggle('border-hairline', !isSelected);
                    checkEl.classList.toggle('text-transparent', !isSelected);
                }
            });

            if (previewCaption) {
                previewCaption.textContent = isDark ? 'Preview: Gelap (#0C0C0C)' : 'Preview: Terang (#F9F9F9)';
            }
            if (previewBox) {
                previewBox.style.backgroundColor = isDark ? '#0C0C0C' : '#F9F9F9';
                previewBox.style.borderColor = isDark ? 'rgba(255,255,255,0.16)' : '#E5E5E5';
                previewBox.style.color = isDark ? '#F5F5F5' : '#191919';
            }
            if (previewNavBar) {
                previewNavBar.style.backgroundColor = isDark ? 'rgba(12,12,12,0.92)' : 'rgba(255,255,255,0.92)';
                previewNavBar.style.borderColor = isDark ? 'rgba(255,255,255,0.10)' : 'rgba(0,0,0,0.08)';
            }
            if (previewNavCart) {
                previewNavCart.style.backgroundColor = isDark ? '#F5F2EE' : '#111111';
                previewNavCart.style.color = isDark ? '#111111' : '#FFFFFF';
            }
            if (previewEyebrow) {
                previewEyebrow.style.color = isDark ? '#B5B5B5' : '#555555';
            }
            if (previewPrimaryBtn) {
                previewPrimaryBtn.style.backgroundColor = isDark ? '#F5F2EE' : '#1F1F1F';
                previewPrimaryBtn.style.color = isDark ? '#111111' : '#FFFFFF';
            }
            if (previewMenuCard) {
                previewMenuCard.style.backgroundColor = isDark ? '#161616' : '#FFFFFF';
                previewMenuCard.style.borderColor = isDark ? 'rgba(255,255,255,0.14)' : '#E5E5E5';
            }
            if (previewCardMuted) {
                previewCardMuted.style.color = isDark ? '#B5B5B5' : '#555555';
            }
            if (previewOutlineBtn) {
                previewOutlineBtn.style.borderColor = isDark ? 'rgba(255,255,255,0.45)' : 'rgba(25,25,25,0.34)';
            }
        }

        function syncSavedHeader(savedTheme) {
            var isDark = savedTheme === 'dark';
            themeCard.setAttribute('data-saved-theme', savedTheme);
            if (savedLabel) {
                savedLabel.textContent = isDark ? 'Mode Gelap (Dark)' : 'Mode Terang (Light)';
            }
            if (headerIconWrap) {
                headerIconWrap.className = 'w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-sm transition-colors ' +
                    (isDark ? 'bg-neutral-900 text-amber-200 border border-neutral-800' : 'bg-amber-50 text-amber-700 border border-amber-200');
            }
            if (headerIcon) {
                headerIcon.className = 'fa-solid ' + (isDark ? 'fa-moon' : 'fa-sun');
            }
            if (draftBadge) {
                draftBadge.classList.add('hidden');
            }
        }

        themeRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                renderThemePreview(getSelectedTheme());
            });
        });

        themeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var chosenTheme = getSelectedTheme();
            var csrfInput = themeForm.querySelector('input[name="_token"]');
            var csrfToken = csrfInput ? csrfInput.value : '';

            if (saveBtn) saveBtn.disabled = true;
            if (saveIcon) saveIcon.className = 'fa-solid fa-spinner fa-spin';
            if (saveText) saveText.textContent = 'Menyimpan...';

            fetch(themeForm.action, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ theme: chosenTheme })
            })
            .then(function(response) {
                return response.json().catch(function() {
                    return {};
                }).then(function(data) {
                    return { ok: response.ok, status: response.status, data: data };
                });
            })
            .then(function(result) {
                if (!result.ok) {
                    var errMsg = (result.data && result.data.message)
                        ? result.data.message
                        : (result.status === 403
                            ? 'Akses ditolak. Hanya admin yang dapat mengubah tema.'
                            : 'Gagal menyimpan tema situs (HTTP ' + result.status + ').');
                    showThemeAlert('error', errMsg);
                    return;
                }

                var appliedTheme = (result.data && result.data.theme) ? result.data.theme : chosenTheme;
                syncSavedHeader(appliedTheme);
                renderThemePreview(appliedTheme);
                showThemeAlert(
                    'success',
                    (result.data && result.data.message)
                        ? result.data.message
                        : ('Tema situs berhasil diubah ke mode ' + (appliedTheme === 'dark' ? 'Gelap' : 'Terang') + '.')
                );
            })
            .catch(function() {
                showThemeAlert('error', 'Terjadi gangguan jaringan saat menyimpan tema. Silakan coba lagi.');
            })
            .finally(function() {
                if (saveBtn) saveBtn.disabled = false;
                if (saveIcon) saveIcon.className = 'fa-solid fa-floppy-disk';
                if (saveText) saveText.textContent = 'Simpan Tema';
            });
        });
    }
})();
</script>
@endpush
@endsection
