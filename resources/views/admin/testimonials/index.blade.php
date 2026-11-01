@extends('layouts.admin')
@section('title', 'Kelola Testimoni & Ulasan — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-star text-[10px]"></i>
                <span>Ulasan &amp; Reputasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Kelola Ulasan Pelanggan
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Moderasi ulasan yang dikirim pelanggan melalui formulir ulasan web dan kelola status tayang.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('nucomu.index') }}#reviews" target="_blank" class="px-3.5 py-2 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition btn-press flex items-center gap-2 shadow-xs">
                <span>Lihat di Web</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>

            <form action="{{ route('admin.testimonials.deleteSample') }}" method="POST" onsubmit="return confirm('Hapus semua ulasan data contoh bawaan?')">
                @csrf
                <button type="submit" class="bg-pure-white hover:bg-rose-50 text-rose-700 hover:text-rose-800 border border-hairline font-bold text-xs px-3.5 py-2 rounded-xl transition flex items-center space-x-2 shadow-xs btn-press">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Hapus Data Contoh</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Form Tambah Testimoni Baru --}}
    <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-card reveal-on-scroll anim-fade-up">
        <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black mb-4 border-b border-hairline pb-3 flex items-center gap-2">
            <i class="fa-solid fa-comment-dots text-charcoal"></i>
            <span>Tambah Ulasan Pelanggan Baru</span>
        </h2>

        <form action="{{ route('admin.testimonials.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @csrf

            <div class="sm:col-span-3">
                <label for="name" class="block text-xs font-bold text-charcoal mb-1.5">Nama Pelanggan <span class="text-rose-600">*</span></label>
                <input type="text" name="name" id="name" required placeholder="Contoh: Siti Rahmawati"
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="sm:col-span-3">
                <label for="handle" class="block text-xs font-bold text-charcoal mb-1.5">Handle (@username)</label>
                <input type="text" name="handle" id="handle" placeholder="Contoh: @siti_rahma"
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black font-mono placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="sm:col-span-3">
                <label for="order_tag" class="block text-xs font-bold text-charcoal mb-1.5">Pesanan Favorit</label>
                <input type="text" name="order_tag" id="order_tag" placeholder="Contoh: Iced Palm Sugar Latte"
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="sm:col-span-3">
                <label for="rating" class="block text-xs font-bold text-charcoal mb-1.5">Rating Bintang</label>
                <select name="rating" id="rating" class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition">
                    <option value="5" selected>★★★★★ (5 Bintang Penuh)</option>
                    <option value="4">★★★★☆ (4 Bintang)</option>
                    <option value="3">★★★☆☆ (3 Bintang)</option>
                    <option value="2">★★☆☆☆ (2 Bintang)</option>
                    <option value="1">★☆☆☆☆ (1 Bintang)</option>
                </select>
            </div>

            <div class="sm:col-span-9">
                <label for="content" class="block text-xs font-bold text-charcoal mb-1.5">Isi Ulasan Pengalaman <span class="text-rose-600">*</span></label>
                <input type="text" name="content" id="content" required placeholder="Contoh: Tempatnya nyaman banget buat nugas, pencahayaannya hangat dan kopinya pas..."
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="sm:col-span-3 flex items-end">
                <button type="submit" class="w-full bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase py-3 rounded-xl transition shadow-xs btn-press">
                    Simpan Ulasan
                </button>
            </div>
        </form>
    </div>

    {{-- Testimonial Table Card --}}
    <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl overflow-hidden shadow-card reveal-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal">
                <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                    <tr>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Pesanan Favorit</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Isi Ulasan</th>
                        <th class="px-6 py-4">Status Tampil</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($testimonials as $testi)
                        <tr class="hover:bg-warm-stone/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink-black text-sm block">{{ $testi->name }}</span>
                                <span class="text-muted-text font-mono text-[11px] block">{{ $testi->handle ?? '@pelanggan' }}</span>
                            </td>

                            <td class="px-6 py-4">
                                @if($testi->order_tag)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] bg-warm-stone text-charcoal border border-hairline font-bold">
                                        {{ $testi->order_tag }}
                                    </span>
                                @else
                                    <span class="text-muted-text text-xs">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-mono text-ink-black whitespace-nowrap text-sm">
                                {{ str_repeat('★', $testi->rating) }}
                            </td>

                            <td class="px-6 py-4 text-charcoal italic max-w-xs leading-relaxed">
                                "{{ $testi->content }}"
                            </td>

                            <td class="px-6 py-4">
                                <form action="{{ route('admin.testimonials.toggle', $testi->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition btn-press {{ $testi->is_active ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-warm-stone text-muted-text border-hairline' }}">
                                        {{ $testi->is_active ? '● Aktif Tayang' : '○ Disembunyikan' }}
                                    </button>
                                </form>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.testimonials.destroy', $testi->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan dari {{ $testi->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-warm-stone hover:bg-rose-50 text-muted-text hover:text-rose-700 transition btn-press" title="Hapus Ulasan">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-muted-text text-xs">
                                <div class="w-12 h-12 rounded-2xl bg-warm-stone flex items-center justify-center mx-auto mb-3 text-muted-text">
                                    <i class="fa-solid fa-star text-lg"></i>
                                </div>
                                <p>Belum ada ulasan terdaftar. Ulasan fallback bawaan sedang ditampilkan di web customer.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())
            <div class="p-4 border-t border-hairline bg-warm-ivory">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
