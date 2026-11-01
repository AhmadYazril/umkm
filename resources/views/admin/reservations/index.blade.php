@extends('layouts.admin')
@section('title', 'Daftar Reservasi Meja & Acara — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-calendar-check text-[10px]"></i>
                <span>Reservasi Pelanggan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Permintaan Reservasi Meja &amp; Acara
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kelola booking tempat, pertemuan, dan acara privat dari formulir reservasi web customer.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}#layanan" target="_blank" class="px-3.5 py-2 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition btn-press flex items-center gap-2 shadow-xs">
                <span>Formulir Web</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- Filter Status Tabs --}}
    <div class="flex items-center space-x-2 overflow-x-auto pb-1 border-b border-hairline text-xs font-bold anim-fade-up anim-delay-1">
        <a href="{{ route('admin.reservations.index') }}" 
           class="px-4 py-2 rounded-xl transition-all {{ !request('status') ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Semua Reservasi
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'pending' ? 'bg-amber-50 text-amber-900 border border-amber-200' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Menunggu Konfirmasi
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'confirmed']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'confirmed' ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'cancelled' ? 'bg-rose-50 text-rose-900 border border-rose-200' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Dibatalkan
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl overflow-hidden shadow-card reveal-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal">
                <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                    <tr>
                        <th class="px-6 py-4">Tgl &amp; Jam Kedatangan</th>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">WhatsApp Kontak</th>
                        <th class="px-6 py-4">Jumlah Tamu</th>
                        <th class="px-6 py-4">Catatan / Acara</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi &amp; Konfirmasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($reservations as $res)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $res->phone);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $waMessage = urlencode("Halo Kak " . $res->name . ", terima kasih telah melakukan reservasi di Nucomu Cafe Tulungagung untuk tanggal " . date('d M Y', strtotime($res->date)) . " jam " . date('H:i', strtotime($res->time)) . " WIB (" . $res->guests . " orang). Reservasi meja Anda telah kami konfirmasi!");
                        @endphp
                        <tr class="hover:bg-warm-stone/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink-black font-mono block text-sm">{{ date('d M Y', strtotime($res->date)) }}</span>
                                <span class="text-[10px] text-muted-text font-mono">{{ date('H:i', strtotime($res->time)) }} WIB</span>
                            </td>

                            <td class="px-6 py-4 font-bold text-ink-black text-sm">
                                {{ $res->name }}
                            </td>

                            <td class="px-6 py-4">
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}" target="_blank" 
                                   class="font-mono text-emerald-800 font-bold hover:underline inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200">
                                    <i class="fa-brands fa-whatsapp text-xs"></i>
                                    <span>{{ $res->phone }}</span>
                                    <span class="text-[9px]">↗</span>
                                </a>
                            </td>

                            <td class="px-6 py-4 font-bold text-ink-black font-mono">
                                {{ $res->guests }} orang
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-charcoal text-xs italic">{{ $res->notes ?? '-' }}</span>
                            </td>

                            <td class="px-6 py-4">
                                @if($res->status === 'pending')
                                    <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Pending</span>
                                @elseif($res->status === 'confirmed')
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Disetujui</span>
                                @else
                                    <span class="bg-rose-50 text-rose-800 border border-rose-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Dibatalkan</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.reservations.updateStatus', $res->id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @if($res->status !== 'confirmed')
                                        <button type="submit" name="status" value="confirmed" 
                                                class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-[11px] border border-emerald-200 transition btn-press" title="Setujui Reservasi">
                                            <i class="fa-solid fa-check text-[10px]"></i> Setujui
                                        </button>
                                    @endif

                                    @if($res->status !== 'cancelled')
                                        <button type="submit" name="status" value="cancelled" 
                                                class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-[11px] border border-rose-200 transition btn-press" title="Batalkan Reservasi">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Tolak
                                        </button>
                                    @endif

                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}" target="_blank"
                                       class="p-1.5 rounded-lg bg-warm-stone hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition btn-press" title="Hubungi Pelanggan via WhatsApp">
                                        <i class="fa-brands fa-whatsapp text-xs"></i>
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-muted-text">
                                <div class="w-12 h-12 rounded-2xl bg-warm-stone flex items-center justify-center mx-auto mb-3 text-muted-text">
                                    <i class="fa-solid fa-calendar-check text-lg"></i>
                                </div>
                                <p class="font-medium text-xs">Belum ada data reservasi masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reservations->hasPages())
            <div class="p-4 border-t border-hairline bg-warm-ivory">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
