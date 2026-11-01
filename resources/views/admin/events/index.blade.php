@extends('layouts.admin')
@section('title', 'Kelola Event & Promo — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-bullhorn text-[10px]"></i>
                <span>Agenda &amp; Komunitas</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Kelola Event &amp; Promo
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Agenda live acoustic, workshop barista, promo musiman, dan gathering komunitas Nucomu Cafe.
            </p>
        </div>
        <a href="{{ route('events') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition btn-press flex items-center gap-2 shadow-xs">
            <span>Lihat Halaman Event</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
        </a>
    </div>

    {{-- Form Tambah Event Baru --}}
    <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-card reveal-on-scroll anim-fade-up">
        <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black mb-4 border-b border-hairline pb-3 flex items-center gap-2">
            <i class="fa-solid fa-calendar-plus text-charcoal"></i>
            <span>Tambah Agenda Event Baru</span>
        </h2>

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @csrf

            <div class="sm:col-span-4">
                <label for="title" class="block text-xs font-bold text-charcoal mb-1.5">Judul Agenda Event <span class="text-rose-600">*</span></label>
                <input type="text" name="title" id="title" required placeholder="Contoh: Acoustic Night Weekend"
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="sm:col-span-3">
                <label for="date" class="block text-xs font-bold text-charcoal mb-1.5">Tanggal Acara <span class="text-rose-600">*</span></label>
                <input type="date" name="date" id="date" required
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="sm:col-span-5">
                <label for="image" class="block text-xs font-bold text-charcoal mb-1.5">Poster / Foto Banner</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3 py-2 text-xs text-charcoal file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-ink-black file:text-white cursor-pointer">
            </div>

            <div class="sm:col-span-10">
                <label for="description" class="block text-xs font-bold text-charcoal mb-1.5">Deskripsi Lengkap Acara <span class="text-rose-600">*</span></label>
                <input type="text" name="description" id="description" required placeholder="Penjelasan mengenai acara, waktu mulai, guest star, dan kuota..."
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="sm:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase py-3 rounded-xl transition shadow-xs btn-press">
                    Simpan Event
                </button>
            </div>
        </form>
    </div>

    {{-- Events List Table --}}
    <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl overflow-hidden shadow-card reveal-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal">
                <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                    <tr>
                        <th class="px-6 py-4">Judul &amp; Tanggal Acara</th>
                        <th class="px-6 py-4">Deskripsi Event</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($events as $event)
                        <tr class="hover:bg-warm-stone/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink-black text-sm block">{{ $event->title }}</span>
                                <span class="text-[10px] font-mono text-charcoal font-semibold block mt-0.5">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    {{ date('d F Y', strtotime($event->date)) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-charcoal max-w-md leading-relaxed">
                                {{ $event->description }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus agenda event {{ $event->title }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-warm-stone hover:bg-rose-50 text-muted-text hover:text-rose-700 transition btn-press" title="Hapus Event">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-muted-text text-xs">
                                <div class="w-12 h-12 rounded-2xl bg-warm-stone flex items-center justify-center mx-auto mb-3 text-muted-text">
                                    <i class="fa-solid fa-bullhorn text-lg"></i>
                                </div>
                                <p>Belum ada agenda event yang ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="p-4 border-t border-hairline bg-warm-ivory">
                {{ $events->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
