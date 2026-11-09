@extends('layouts.admin')
@section('title', 'Profil Admin & Keamanan — Nucomu Cafe')

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
                <span>{{ session('error') ? 'Terjadi kesalahan unggah:' : 'Terdapat kesalahan pada formulir:' }}</span>
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
                <span>Modul 10 &bull; Akun, Profil &amp; Keamanan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Profil &amp; Keamanan Akun
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kelola identitas akun admin, foto profil avatar, dan kata sandi untuk keamanan akses panel kontrol kafe.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sesi admin Nucomu Cafe?');">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-pure-white hover:bg-rose-50 border border-hairline hover:border-rose-300 text-rose-700 font-bold text-xs transition shadow-xs flex items-center gap-2 btn-press">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Keluar Akun (Logout)</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Grid Layout: Profile Info (Col 7) and Password Change (Col 5) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- Left Column: Profile Data & Avatar Upload (7 Cols) --}}
        <div class="lg:col-span-7 space-y-6">

            <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-hairline">
                    <div class="w-8 h-8 rounded-xl bg-warm-stone text-ink-black flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-ink-black">Informasi Profil Admin</h2>
                        <p class="text-[11px] text-muted-text">Identitas nama, email login, dan foto profil Anda</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Avatar Section with Live Preview & Specs --}}
                    @php
                        $avatarLimits = \App\Support\FileUploadHelper::getEffectiveLimit(2048 * 1024, 'foto profil');
                    @endphp
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-ink-black block">Foto Profil (Avatar)</label>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-5 p-4 rounded-xl bg-warm-canvas/60 border border-hairline">
                            {{-- Avatar Preview Frame --}}
                            <div class="relative shrink-0 mx-auto sm:mx-0">
                                <img id="avatarPreviewImg"
                                     src="{{ $user->avatar_url }}"
                                     alt="{{ $user->name }}"
                                     class="w-20 h-20 rounded-2xl object-cover border-2 border-pure-white shadow-sm bg-warm-stone">
                                <span class="absolute -bottom-1 -right-1 px-1.5 py-0.5 rounded-full bg-ink-black text-white text-[9px] font-mono font-bold shadow-2xs">
                                    1:1
                                </span>
                            </div>

                            <div class="space-y-2 flex-1">
                                <input type="file" name="avatar" id="avatarInput" accept=".jpg,.jpeg,.png,.webp"
                                       data-max-bytes="{{ $avatarLimits['effective_bytes'] ?? $avatarLimits['limit_bytes'] ?? 2097152 }}"
                                       data-allowed-ext="jpg,jpeg,png,webp"
                                       data-feedback-target="#avatarFeedback"
                                       class="block w-full text-xs text-muted-text file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-ink-black file:text-white hover:file:bg-charcoal file:cursor-pointer file:transition cursor-pointer">
                                
                                <div id="avatarFeedback" class="text-left"></div>

                                {{-- Clear Photo Upload Specs Guide --}}
                                <div class="space-y-1 text-[11px] text-muted-text">
                                    <p class="flex items-center gap-1.5 font-medium text-charcoal">
                                        <i class="fa-solid fa-circle-info text-amber-600 text-xs"></i>
                                        <span>Panduan Foto: Rasio <strong>1:1</strong> persegi (500x500 px disarankan)</span>
                                    </p>
                                    <p class="text-[10px]">Format: JPG, JPEG, PNG, WebP &bull; Ukuran maksimal: <strong>{{ $avatarLimits['formatted'] ?? $avatarLimits['limit_formatted'] ?? '2 MB' }}</strong></p>
                                    @if(!empty($avatarLimits['is_throttled'] ?? $avatarLimits['is_limited_by_server']))
                                        <p class="text-[10px] text-amber-800 font-medium">
                                            <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Batas server PHP saat ini ({{ $avatarLimits['server_limit_formatted'] ?? '2 MB' }}) lebih kecil dari batas aplikasi (2 MB).
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Button to Remove Avatar if custom exists --}}
                        @if($user->avatar)
                            <div class="flex justify-end">
                                <button type="button" onclick="document.getElementById('formRemoveAvatar').submit()"
                                        class="text-[11px] font-bold text-rose-600 hover:text-rose-800 inline-flex items-center gap-1">
                                    <i class="fa-regular fa-trash-can"></i>
                                    <span>Hapus Foto Profil Kustom</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="space-y-1.5">
                        <label for="name" class="text-xs font-bold text-ink-black flex items-center gap-1">
                            <span>Nama Lengkap Admin</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-regular fa-user"></i>
                            </span>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="Contoh: Administrator Nucomu"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        @error('name')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-ink-black flex items-center gap-1">
                            <span>Alamat Email Login</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="Contoh: admin@nucomu.com"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Email ini digunakan sebagai akun login ke panel admin kafe.
                        </p>
                        @error('email')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Peran Akun (Read-only) --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-ink-black">Peran &amp; Akses</label>
                        <div class="px-3.5 py-2.5 rounded-xl bg-warm-stone/50 border border-hairline text-xs font-semibold text-charcoal flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                                <span>{{ strtoupper($user->role ?: 'ADMIN') }} — Akses Penuh Panel Nucomu</span>
                            </span>
                            <span class="text-[10px] font-mono text-muted-text">ID #{{ $user->id }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-hairline flex items-center justify-end">
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold uppercase tracking-wider transition-all shadow-xs flex items-center gap-2 btn-press">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>

                {{-- Hidden Form for Avatar Removal --}}
                <form id="formRemoveAvatar" action="{{ route('admin.profile.removeAvatar') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>

        </div>

        {{-- Right Column: Change Password Card (5 Cols) --}}
        <div class="lg:col-span-5 space-y-6">

            <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-5">
                <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-ink-black">Ganti Kata Sandi</h2>
                        <p class="text-[11px] text-muted-text">Perbarui password akun untuk menjaga keamanan akses</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Password Saat Ini --}}
                    <div class="space-y-1.5">
                        <label for="current_password" class="text-xs font-bold text-ink-black flex items-center gap-1">
                            <span>Password Saat Ini</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="current_password" id="current_password" required
                                   placeholder="Masukkan password saat ini"
                                   class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                            <button type="button" onclick="togglePassVisibility('current_password', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-muted-text hover:text-ink-black">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-bold text-ink-black flex items-center gap-1">
                            <span>Password Baru</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            <input type="password" name="password" id="password" required minlength="8"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                            <button type="button" onclick="togglePassVisibility('password', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-muted-text hover:text-ink-black">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="text-xs font-bold text-ink-black flex items-center gap-1">
                            <span>Konfirmasi Password Baru</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-solid fa-check-double"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                                   placeholder="Ulangi password baru persis"
                                   class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                            <button type="button" onclick="togglePassVisibility('password_confirmation', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-muted-text hover:text-ink-black">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200 text-[11px] text-amber-900 leading-relaxed">
                        <i class="fa-solid fa-triangle-exclamation text-amber-700 mr-1"></i>
                        <span>Pastikan password baru minimal <strong>8 karakter</strong> dan mudah diingat sebelum memperbarui.</span>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold uppercase tracking-wider transition-all shadow-xs flex items-center justify-center gap-2 btn-press">
                            <i class="fa-solid fa-lock text-xs"></i>
                            <span>Perbarui Password Akun</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tips Keamanan Akun Card --}}
            <div class="bg-warm-canvas/50 rounded-2xl border border-hairline p-4 space-y-2 text-xs text-charcoal">
                <span class="font-bold text-ink-black flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-cat text-muted-text"></i>
                    <span>Informasi Akun</span>
                </span>
                <p class="text-[11px] text-muted-text leading-relaxed">
                    Terdaftar sejak: <strong>{{ $user->created_at ? $user->created_at->isoFormat('D MMMM Y') : '-' }}</strong>.<br>
                    Terakhir diperbarui: <strong>{{ $user->updated_at ? $user->updated_at->diffForHumans() : '-' }}</strong>.
                </p>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
// Live Preview of selected avatar file
(function() {
    var avatarInput = document.getElementById('avatarInput');
    var avatarPreviewImg = document.getElementById('avatarPreviewImg');

    if (avatarInput && avatarPreviewImg) {
        avatarInput.addEventListener('change', function(e) {
            var file = e.target.files && e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    avatarPreviewImg.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Toggle password visibility helper
    window.togglePassVisibility = function(inputId, btn) {
        var input = document.getElementById(inputId);
        if (!input) return;
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.className = 'fa-regular fa-eye-slash text-xs';
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.className = 'fa-regular fa-eye text-xs';
            }
        }
    };
})();
</script>
@endpush
@endsection
