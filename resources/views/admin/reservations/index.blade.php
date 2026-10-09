@extends('layouts.admin')
@section('title', 'Kelola Reservasi — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Daftar Reservasi</h1>
            <p class="text-xs text-zinc-400 mt-1">Permintaan reservasi meja dan event dari pelanggan.</p>
        </div>
    </div>

    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-mono tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-6 py-4">Tgl & Jam Kedatangan</th>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">WhatsApp</th>
                        <th class="px-6 py-4">Tamu</th>
                        <th class="px-6 py-4">Catatan / Acara</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    @forelse($reservations as $res)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-white font-mono block text-sm">{{ date('d M Y', strtotime($res->date)) }}</span>
                                <span class="text-[10px] text-zinc-500 font-mono">{{ date('H:i', strtotime($res->time)) }} WIB</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-white text-sm">
                                {{ $res->name }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $res->phone) }}" target="_blank" class="font-mono text-emerald-400 font-bold hover:underline">
                                    {{ $res->phone }} ↗
                                </a>
                            </td>
                            <td class="px-6 py-4 font-bold text-white font-mono">
                                {{ $res->guests }} orang
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-zinc-400 text-xs italic">{{ $res->notes ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($res->status === 'pending')
                                    <span class="bg-yellow-950 text-yellow-400 border border-yellow-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Pending</span>
                                @elseif($res->status === 'confirmed')
                                    <span class="bg-emerald-950 text-emerald-400 border border-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Disetujui</span>
                                @else
                                    <span class="bg-red-950 text-red-400 border border-red-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.reservations.updateStatus', $res->id) }}" method="POST" class="inline-flex space-x-1">
                                    @csrf
                                    @if($res->status !== 'confirmed')
                                        <button type="submit" name="status" value="confirmed" class="bg-emerald-950 hover:bg-emerald-900 text-emerald-400 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-emerald-800">
                                            Setujui
                                        </button>
                                    @endif
                                    @if($res->status !== 'cancelled')
                                        <button type="submit" name="status" value="cancelled" class="bg-red-950 hover:bg-red-900 text-red-400 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-red-800">
                                            Tolak
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500 text-xs">
                                Belum ada data reservasi masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
