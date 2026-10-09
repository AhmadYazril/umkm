@extends('layouts.app')
@section('title', $menu->name . ' — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-xs text-zinc-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('menu') }}" class="hover:text-white transition-colors">Katalog Menu</a>
            <span>/</span>
            <span class="text-zinc-300 font-medium">{{ $menu->name }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6 sm:p-8">
            
            {{-- Image & Badges --}}
            <div class="space-y-4">
                <div class="relative rounded-2xl overflow-hidden bg-zinc-950 aspect-square border border-zinc-800/80 flex items-center justify-center">
                    @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-zinc-600 p-6 text-center">
                            <svg class="w-16 h-16 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="text-xs uppercase tracking-widest font-mono text-zinc-500">Nucomu Signature Menu</span>
                        </div>
                    @endif

                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                        @if($menu->is_featured)
                            <span class="bg-white text-zinc-950 text-xs font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-md">
                                Unggulan
                            </span>
                        @endif
                        @if($menu->is_best_seller)
                            <span class="bg-zinc-950 text-white border border-zinc-700 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                                Best Seller
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Cafe Info Banner --}}
                <div class="bg-zinc-950/80 border border-zinc-800 rounded-2xl p-4 flex items-center space-x-3 text-xs text-zinc-400">
                    <div class="p-2 bg-zinc-900 rounded-xl text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-white block">Fresh & Made to Order</span>
                        Disajikan segar saat pesanan Anda dibuat di meja atau takeaway.
                    </div>
                </div>
            </div>

            {{-- Product Form & Details --}}
            <div class="flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="bg-zinc-800 text-zinc-300 text-xs px-3 py-1 rounded-full border border-zinc-700 font-medium">
                            {{ $menu->category->name ?? 'Menu' }}
                        </span>
                        @if(!$menu->is_available)
                            <span class="bg-red-950 text-red-400 border border-red-800 text-xs px-3 py-1 rounded-full font-bold">
                                Habis / Tidak Tersedia
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-3">
                        {{ $menu->name }}
                    </h1>

                    <div class="text-2xl font-extrabold text-white mb-6">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </div>

                    <div class="prose prose-invert max-w-none text-zinc-400 text-sm leading-relaxed mb-8">
                        <p>{{ $menu->description ?? 'Nikmati racikan kelezatan istimewa khas Nucomu Cafe. Cocok menemani waktu santai maupun produktif Anda.' }}</p>
                    </div>

                    {{-- Form Tambah ke Keranjang --}}
                    <form action="{{ route('cart.add', $menu->slug) }}" method="POST" id="addCartForm" class="space-y-6">
                        @csrf

                        {{-- Options / Variants & Addons --}}
                        @if($menu->options->count() > 0)
                            <div class="border-t border-b border-zinc-800 py-5 space-y-4">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-300">
                                    Pilih Opsi & Varian Tambahan
                                </h3>

                                <div class="space-y-3">
                                    @foreach($menu->options as $option)
                                        <label class="flex items-center justify-between p-3 rounded-xl bg-zinc-950/80 border border-zinc-800 hover:border-zinc-700 cursor-pointer transition-all">
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="options[]" value="{{ $option->id }}" 
                                                    class="w-4 h-4 text-white bg-zinc-900 border-zinc-700 rounded focus:ring-zinc-500 focus:ring-offset-zinc-900">
                                                <span class="text-sm font-medium text-white">{{ $option->name }}</span>
                                            </div>
                                            <span class="text-xs font-mono text-zinc-400">
                                                +Rp {{ number_format($option->price, 0, ',', '.') }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Catatan Khusus --}}
                        <div>
                            <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                                Catatan Khusus (Opsional)
                            </label>
                            <input type="text" name="notes" id="notes" placeholder="Contoh: Less sugar, extra ice, sambal dipisah..." 
                                class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors">
                        </div>

                        {{-- Jumlah / Qty --}}
                        <div class="flex items-center space-x-4 pt-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Jumlah:</span>
                            <div class="flex items-center bg-zinc-950 border border-zinc-800 rounded-full p-1">
                                <button type="button" onclick="decrementQty()" class="w-8 h-8 rounded-full bg-zinc-900 text-white font-bold hover:bg-zinc-800 flex items-center justify-center transition-colors">
                                    -
                                </button>
                                <input type="number" name="qty" id="qtyInput" value="1" min="1" max="99" 
                                    class="w-12 text-center bg-transparent text-sm font-bold text-white focus:outline-none border-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="incrementQty()" class="w-8 h-8 rounded-full bg-zinc-900 text-white font-bold hover:bg-zinc-800 flex items-center justify-center transition-colors">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Button Submit --}}
                        <div class="pt-4">
                            @if($menu->is_available)
                                <button type="submit" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-black text-sm uppercase tracking-wider py-4 rounded-2xl transition-all shadow-xl hover:shadow-white/10 flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>Tambah ke Pesanan</span>
                                </button>
                            @else
                                <button type="button" disabled class="w-full bg-zinc-800 text-zinc-500 font-bold text-sm uppercase tracking-wider py-4 rounded-2xl cursor-not-allowed">
                                    Stok Habis / Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
function incrementQty() {
    const input = document.getElementById('qtyInput');
    input.value = parseInt(input.value) + 1;
}
function decrementQty() {
    const input = document.getElementById('qtyInput');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endsection
