@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Peserta Magang / PKL Aktif</h1>
            <p class="text-xs text-slate-500 mt-1">Monitoring keaktifan dan jurnal kegiatan mahasiswa/siswa yang sedang aktif.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                        <th class="p-4">Peserta & Instansi</th>
                        <th class="p-4">Periode Magang</th>
                        <th class="p-4 text-center">Total Jurnal</th>
                        <th class="p-4">Keaktifan</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pesertaAktif as $p)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4">
                                <p class="font-bold text-slate-800">{{ $p->nama_lengkap }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $p->nama_instansi }} &bull; {{ $p->prodi_jurusan }}</p>
                            </td>
                            <td class="p-4 whitespace-nowrap text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($p->tgl_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($p->tgl_selesai)->format('d M Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 bg-blue-50 text-bpsBlue font-bold rounded-full text-xs">
                                    {{ $p->jurnals->count() }} Jurnal
                                </span>
                            </td>
                            <td class="p-4">
                                @if($p->jurnals->isNotEmpty())
                                    <span class="text-emerald-600 font-semibold">
                                        {{ \Carbon\Carbon::parse($p->jurnals->max('tanggal'))->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-rose-500 font-medium">Belum mengisi</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.peserta.show', $p->id) }}" class="inline-flex items-center space-x-1 px-4 py-2 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span>Lihat Jurnal</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada peserta PKL/Magang yang aktif saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $pesertaAktif->links() }}
        </div>
    </div>
</div>
@endsection