@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('admin.peserta.index') }}" class="text-xs font-bold text-bpsBlue hover:underline flex items-center mb-1">
                &larr; Kembali ke Daftar Peserta
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Jurnal Kegiatan : {{ $pengajuan->nama_lengkap }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ $pengajuan->nama_instansi }} &bull; {{ $pengajuan->prodi_jurusan }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-sm font-bold text-slate-800">Riwayat Jurnal Harian</h2>
            <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-semibold">Total: <b>{{ $jurnals->total() }}</b> Kegiatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                        <th class="p-4">Tanggal & Waktu</th>
                        <th class="p-4">Judul Kegiatan</th>
                        <th class="p-4">Deskripsi / Detail Pekerjaan</th>
                        <th class="p-4 text-center">Dokumentasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($jurnals as $j)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $j->jam_mulai ? \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') : '-' }} - 
                                    {{ $j->jam_selesai ? \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') : '-' }} WIB
                                </div>
                            </td>
                            <td class="p-4 font-bold text-slate-800">
                                {{ $j->kegiatan }}
                            </td>
                            <td class="p-4 text-slate-600 leading-relaxed max-w-xs">
                                {{ $j->deskripsi }}
                            </td>
                            <td class="p-4 text-center">
                                @if($j->file_dokumentasi)
                                    <a href="{{ asset('storage/' . $j->file_dokumentasi) }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-blue-50 text-bpsBlue font-bold rounded-xl text-[11px] hover:bg-bpsBlue hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>Lihat File</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 font-medium text-[11px]">Tanpa File</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">Mahasiswa ini belum pernah mengisi jurnal kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $jurnals->links() }}
        </div>
    </div>
</div>
@endsection