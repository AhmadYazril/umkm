<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel — Nucomu Cafe')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        obsidian: '#0c0c0f',
                        charcoal: '#15151c',
                        goldAccent: '#f59e0b',
                        violetAccent: '#8b5cf6',
                        cyanAccent: '#06b6d4',
                        roseAccent: '#f43f5e'
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0c0c0f; }
        ::-webkit-scrollbar-thumb { background: #232330; border-radius: 9999px; }
        .glass-sidebar {
            background: rgba(18, 18, 24, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="bg-obsidian text-zinc-100 font-sans antialiased min-h-screen flex flex-col md:flex-row relative selection:bg-goldAccent selection:text-black">

    {{-- Ambient Ambient Glows --}}
    <div class="fixed top-10 left-64 w-96 h-96 rounded-full bg-goldAccent/5 pointer-events-none blur-3xl z-0"></div>
    <div class="fixed bottom-10 right-10 w-[28rem] h-[28rem] rounded-full bg-violetAccent/5 pointer-events-none blur-3xl z-0"></div>

    {{-- Sidebar Navigation --}}
    <aside class="w-full md:w-64 glass-sidebar shrink-0 flex flex-col justify-between p-4 sm:p-6 min-h-screen relative z-10">
        <div class="space-y-8">
            {{-- Brand Logo Monogram Matching Customer Header --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-2 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-goldAccent via-amber-500 to-rose-500 p-0.5 shadow-lg shadow-goldAccent/20 group-hover:scale-105 transition-transform duration-300 shrink-0">
                    <div class="w-full h-full bg-obsidian rounded-[14px] flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-transparent bg-clip-text bg-gradient-to-r from-goldAccent to-rose-400 text-lg"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-tight text-white">NUCOMU</span>
                    <span class="text-[9px] uppercase tracking-widest text-goldAccent font-semibold -mt-1">Control Panel</span>
                </div>
            </a>

            {{-- Nav Links --}}
            <nav class="space-y-1.5 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-pie w-4"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.menus.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.menus.*') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-utensils w-4"></i>
                    <span>Kelola Menu</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.orders.index', 'admin.orders.show') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-receipt w-4"></i>
                    <span>Pesanan Masuk</span>
                </a>

                <a href="{{ route('admin.orders.scan') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.orders.scan*') ? 'bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-goldAccent hover:text-amber-300 hover:bg-goldAccent/10 border border-goldAccent/20' }}">
                    <i class="fa-solid fa-qrcode w-4"></i>
                    <span>Scan QR Kasir</span>
                </a>

                <a href="{{ route('admin.reservations.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reservations.*') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-calendar-check w-4"></i>
                    <span>Reservasi</span>
                </a>

                <div class="pt-4 pb-1">
                    <span class="px-3 text-[10px] font-mono uppercase tracking-widest text-zinc-500 font-bold">Konten Web</span>
                </div>

                <a href="{{ route('admin.gallery.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.gallery.*') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-camera-retro w-4"></i>
                    <span>Galeri Story</span>
                </a>

                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.testimonials.*') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-star w-4"></i>
                    <span>Ulasan Pelanggan</span>
                </a>

                <a href="{{ route('admin.events.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.events.*') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-bullhorn w-4"></i>
                    <span>Event & Promo</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-goldAccent text-black font-extrabold shadow-lg shadow-goldAccent/20' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-gear w-4"></i>
                    <span>Pengaturan Cafe</span>
                </a>
            </nav>
        </div>

        {{-- Footer User & Logout --}}
        <div class="border-t border-white/10 pt-4 mt-6">
            <div class="flex items-center justify-between">
                <div class="text-xs">
                    <span class="font-bold text-white block">{{ auth()->user()->name ?? 'Admin Nucomu' }}</span>
                    <span class="text-goldAccent font-mono text-[10px] block">{{ auth()->user()->email ?? 'admin@nucomu.com' }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar Admin" class="p-2 text-zinc-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="mt-3 w-full inline-flex items-center justify-center space-x-1.5 py-2 text-[11px] font-bold text-zinc-400 hover:text-goldAccent bg-black/40 rounded-xl border border-white/10 hover:border-goldAccent/30 transition-all">
                <span>Lihat Web Customer</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </aside>

    {{-- Main Content Container --}}
    <main class="flex-1 overflow-y-auto p-6 sm:p-10 relative z-10">
        
        {{-- Alert Notification --}}
        @if(session('success'))
            <div class="bg-emerald-950/80 border border-emerald-800 text-emerald-300 p-4 rounded-2xl mb-8 flex items-center justify-between text-sm font-semibold shadow-lg backdrop-blur">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-950/80 border border-rose-800 text-rose-300 p-4 rounded-2xl mb-8 flex items-center justify-between text-sm font-semibold shadow-lg backdrop-blur">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
