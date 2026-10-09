<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Nucomu Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
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
<body class="bg-zinc-950 text-white font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-zinc-900 border border-zinc-800 rounded-3xl p-8 sm:p-10 shadow-2xl">
        
        {{-- Brand Logo Monogram --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-white text-zinc-950 font-black rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 tracking-tighter shadow-xl shadow-white/10">
                NU
            </div>
            <h1 class="text-2xl font-black uppercase tracking-tight">Admin Login</h1>
            <p class="text-zinc-400 text-xs mt-1">Masuk ke Panel Pengelolaan Nucomu Cafe</p>
        </div>

        @if($errors->any())
            <div class="bg-red-950/80 border border-red-800 text-red-300 p-3.5 rounded-2xl text-xs mb-6 font-semibold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                    Email Administrator
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', 'admin@nucomu.com') }}" required autofocus placeholder="admin@nucomu.com"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                    Kata Sandi
                </label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors">
            </div>

            <button type="submit" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all shadow-xl shadow-white/10">
                Masuk ke Admin Panel
            </button>
        </form>

        <div class="mt-8 text-center pt-6 border-t border-zinc-800">
            <a href="{{ route('home') }}" class="text-xs text-zinc-500 hover:text-white transition-colors">
                ← Kembali ke Website Publik
            </a>
        </div>

    </div>

</body>
</html>
