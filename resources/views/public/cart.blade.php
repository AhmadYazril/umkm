@extends('layouts.app')
@section('title', 'Keranjang Pesanan — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white uppercase mb-2">
                Keranjang <span class="text-zinc-500">Pesanan</span>
            </h1>
            <p class="text-zinc-400 text-sm">
                Periksa daftar menu pilihan Anda sebelum melakukan konfirmasi pemesanan.
            </p>
        </div>

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Daftar Item Keranjang (Col 7) --}}
                <div class="lg:col-span-7 space-y-4">
                    <div class="flex items-center justify-between bg-zinc-900/60 border border-zinc-800 p-4 rounded-2xl text-xs">
                        <span class="text-zinc-400 font-bold uppercase tracking-wider">Item Dipilih ({{ count($cart) }})</span>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Kosongkan semua keranjang?')" class="text-red-400 hover:text-red-300 font-semibold underline">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    @foreach($cart as $key => $item)
                        <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="font-bold text-base text-white">
                                    {{ $item['name'] }}
                                </h3>
                                <div class="text-xs font-mono text-zinc-400 mt-1">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['qty'] }} pcs
                                </div>

                                @if(!empty($item['options']))
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($item['options'] as $opt)
                                            <span class="text-[11px] bg-zinc-800 text-zinc-300 border border-zinc-700 px-2 py-0.5 rounded-full">
                                                + {{ $opt['name'] }} (Rp {{ number_format($opt['price'], 0, ',', '.') }})
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if(!empty($item['notes']))
                                    <p class="text-xs text-zinc-400 italic mt-2">
                                        Note: "{{ $item['notes'] }}"
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-4 border-t sm:border-t-0 border-zinc-800 pt-3 sm:pt-0">
                                <div class="text-right">
                                    <span class="text-xs text-zinc-500 block">Subtotal</span>
                                    <span class="text-base font-extrabold text-white">
                                        Rp {{ number_format($item['line_total'], 0, ',', '.') }}
                                    </span>
                                </div>

                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $key }}">
                                    <button type="submit" class="p-2 bg-zinc-800 hover:bg-red-950 text-zinc-400 hover:text-red-400 border border-zinc-700 hover:border-red-800 rounded-xl transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Form Checkout (Col 5) --}}
                <div class="lg:col-span-5">
                    <div class="bg-zinc-900/80 border border-zinc-800 rounded-3xl p-6 sm:p-8 sticky top-24">
                        <h2 class="text-lg font-bold text-white uppercase tracking-wider mb-6 border-b border-zinc-800 pb-4">
                            Informasi Pemesanan
                        </h2>

                        <form action="{{ route('order.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                                    Nama Pemesan <span class="text-white">*</span>
                                </label>
                                <input type="text" name="customer_name" id="customer_name" required placeholder="Masukkan nama Anda"
                                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors">
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                                    Nomor WhatsApp <span class="text-white">*</span>
                                </label>
                                <input type="tel" name="customer_phone" id="customer_phone" required placeholder="08xxxxxxxxxx"
                                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors">
                            </div>

                            <div>
                                <label for="order_type" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                                    Tipe Pesanan <span class="text-white">*</span>
                                </label>
                                <select name="order_type" id="order_type" required onchange="toggleTableInput(this.value)"
                                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-white transition-colors">
                                    <option value="dine_in">Dine-In (Makan di Tempat)</option>
                                    <option value="take_away">Take-Away (Bawa Pulang)</option>
                                    <option value="pre_order">Pre-Order (Ambil Nanti)</option>
                                </select>
                            </div>

                            <div id="tableSection">
                                <label for="table_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                                    Pilih Meja (Dine-In)
                                </label>
                                <select name="table_id" id="table_id"
                                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-white transition-colors">
                                    <option value="">-- Pilih Meja --</option>
                                    @foreach($tables as $table)
                                        <option value="{{ $table->id }}">
                                            Meja {{ $table->number }} (Kapasitas {{ $table->capacity }} orang)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="payment_method" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                                    Metode Pembayaran <span class="text-white">*</span>
                                </label>
                                <select name="payment_method" id="payment_method" required
                                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-white transition-colors">
                                    <option value="cash">Tunai (Kasir)</option>
                                    <option value="qris">QRIS (Scan di Kasir/WA)</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>

                            <div>
                                <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1.5">
                                    Catatan Pesanan Total
                                </label>
                                <textarea name="notes" id="notes" rows="2" placeholder="Catatan tambahan..."
                                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors"></textarea>
                            </div>

                            {{-- Total Calculation --}}
                            <div class="border-t border-zinc-800 pt-4 mt-6 space-y-2">
                                <div class="flex justify-between text-xs text-zinc-400">
                                    <span>Subtotal Menu</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-zinc-400">
                                    <span>Pajak & Servis</span>
                                    <span class="text-emerald-400 font-semibold">Termasuk</span>
                                </div>
                                <div class="flex justify-between text-base font-black text-white pt-2 border-t border-zinc-800">
                                    <span>Total Akhir</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 bg-white hover:bg-zinc-200 text-zinc-950 font-black text-sm uppercase tracking-wider py-4 rounded-2xl transition-all shadow-xl hover:shadow-white/10 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Buat Pesanan Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @else
            <div class="text-center py-20 bg-zinc-900/40 border border-zinc-800/80 rounded-3xl p-8 max-w-xl mx-auto">
                <svg class="w-16 h-16 text-zinc-600 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="text-lg font-bold text-white mb-2">Keranjang Pesanan Kosong</h3>
                <p class="text-zinc-400 text-sm max-w-md mx-auto mb-6">
                    Anda belum memilih menu apa pun. Silakan lihat katalog menu kami untuk mulai memesan.
                </p>
                <a href="{{ route('menu') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-white text-zinc-950 font-bold text-xs uppercase tracking-wider hover:bg-zinc-200 transition-colors">
                    Jelajahi Menu Nucomu
                </a>
            </div>
        @endif

    </div>
</section>

<script>
function toggleTableInput(val) {
    const section = document.getElementById('tableSection');
    if (val === 'dine_in') {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
}
</script>
@endsection
