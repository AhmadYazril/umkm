<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel — Nucomu Cafe')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    {{-- Sidebar Navigation --}}
    <aside class="w-full md:w-64 bg-zinc-900 border-r border-zinc-800 shrink-0 flex flex-col justify-between p-4 sm:p-6 min-h-screen">
        <div class="space-y-8">
            {{-- Brand Logo Monogram --}}
            <div class="flex items-center space-x-3 px-2">
                <div class="w-10 h-10 bg-white text-zinc-950 font-black rounded-xl flex items-center justify-center text-lg tracking-tighter shadow-md">
                    NU
                </div>
                <div>
                    <span class="font-extrabold text-sm text-white tracking-wider block">NUCOMU CAFE</span>
                    <span class="text-[10px] text-zinc-400 uppercase tracking-widest block font-mono">Admin Control</span>
                </div>
            </div>

            {{-- Nav Links --}}
            <nav class="space-y-1 text-xs">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.menus.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.menus.*') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Kelola Menu</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Pesanan</span>
                </a>

                <a href="{{ route('admin.reservations.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.reservations.*') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Reservasi</span>
                </a>

                <div class="pt-4 pb-1">
                    <span class="px-3 text-[10px] font-mono uppercase tracking-widest text-zinc-500 font-bold">Konten & Galeri</span>
                </div>

                <a href="{{ route('admin.gallery.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.gallery.*') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Galeri Foto</span>
                </a>

                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.testimonials.*') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span>Testimoni</span>
                </a>

                <a href="{{ route('admin.events.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.events.*') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>Event & Promo</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan Cafe</span>
                </a>
            </nav>
        </div>

        {{-- Footer User & Logout --}}
        <div class="border-t border-zinc-800 pt-4 mt-6">
            <div class="flex items-center justify-between">
                <div class="text-xs">
                    <span class="font-bold text-white block">{{ auth()->user()->name ?? 'Admin Nucomu' }}</span>
                    <span class="text-zinc-500 font-mono text-[11px] block">{{ auth()->user()->email ?? 'admin@nucomu.com' }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar Admin" class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="mt-3 w-full inline-flex items-center justify-center space-x-1 py-2 text-[11px] font-bold text-zinc-400 hover:text-white bg-zinc-950 rounded-xl border border-zinc-800 transition-colors">
                <span>Lihat Website Publik</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </aside>

    {{-- Main Content Container --}}
    <main class="flex-1 overflow-y-auto p-6 sm:p-10">
        
        {{-- Alert Notification --}}
        @if(session('success'))
            <div class="bg-emerald-950/80 border border-emerald-800 text-emerald-300 p-4 rounded-2xl mb-8 flex items-center justify-between text-sm font-semibold">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-950/80 border border-red-800 text-red-300 p-4 rounded-2xl mb-8 flex items-center justify-between text-sm font-semibold">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
