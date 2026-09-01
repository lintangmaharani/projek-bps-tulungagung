@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-black text-slate-800 tracking-tight">Status Pengajuan PKL / Magang</h1>

    @if(!$pengajuan)
        <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center shadow-sm">
            <p class="text-sm text-slate-500">Anda belum pernah mengirimkan berkas pengajuan PKL/Magang.</p>
        </div>
    @else
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="text-center space-y-2">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Berkas Saat Ini</p>
                
                @if($pengajuan->status == 'diproses')
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-amber-700 bg-amber-100 border border-amber-200">
                        Diproses
                    </span>
                    <p class="text-xs text-slate-500 mt-2">Pengajuan Anda sedang ditinjau oleh tim Administrasi BPS Kabupaten Tulungagung.</p>
                @elseif($pengajuan->status == 'diterima')
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-emerald-700 bg-emerald-100 border border-emerald-200">
                        Diterima
                    </span>
                    <p class="text-xs text-slate-500 mt-2">Selamat! Pengajuan PKL / Magang Anda telah disetujui.</p>
                @else
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-rose-700 bg-rose-100 border border-rose-200">
                        Ditolak
                    </span>
                    <p class="text-xs text-slate-500 mt-2">Mohon maaf, pengajuan PKL / Magang Anda belum disetujui.</p>
                @endif
            </div>

            @if($pengajuan->catatan)
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Catatan dari Admin:</p>
                    <p class="text-xs font-semibold text-slate-700">{{ $pengajuan->catatan }}</p>
                </div>
            @endif

            <!-- FITUR MAHASISWA DOWNLOAD SURAT BALASAN RESMI -->
            @if($pengajuan->file_surat_balasan)
                <div class="pt-4 text-center border-t border-slate-100">
                    <a href="{{ asset('storage/' . $pengajuan->file_surat_balasan) }}" target="_blank" download class="inline-flex items-center space-x-2 px-6 py-3 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Download Surat Balasan Resmi BPS (PDF)</span>
                    </a>
                </div>
            @elseif($pengajuan->status != 'diproses')
                <p class="text-center text-xs text-slate-400 italic">Surat balasan resmi dalam proses unggah oleh pihak BPS.</p>
            @endif
        </div>
    @endif
</div>
@endsection