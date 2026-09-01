@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Sertifikat Kelulusan PKL / Magang</h1>
        <p class="text-xs text-slate-500 mt-1">Unduh dokumen sertifikat resmi setelah Anda menyelesaikan masa magang/pkl.</p>
    </div>

    @if(!$pengajuan)
        <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center space-y-3 shadow-sm">
            <p class="text-xs font-semibold text-slate-500">Anda belum mengajukan pendaftaran PKL/Magang.</p>
        </div>
    @elseif($pengajuan->file_sertifikat)
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-center space-y-5">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <div class="space-y-1 max-w-md mx-auto">
                <h2 class="text-lg font-bold text-slate-800">Selamat! Sertifikat Anda Telah Terbit</h2>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Sertifikat resmi kelulusan PKL/Magang Anda di BPS Tulungagung sudah siap dan dapat diunduh di bawah ini.
                </p>
            </div>

            <div class="pt-2">
                <a href="{{ asset('storage/' . $pengajuan->file_sertifikat) }}" target="_blank" download class="inline-flex items-center space-x-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Download Sertifikat (PDF)</span>
                </a>
            </div>
        </div>
    @else
        <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center space-y-3 shadow-sm">
            <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-sm font-bold text-slate-800">Sertifikat Belum Tersedia</h2>
            <p class="text-xs text-slate-400 max-w-sm mx-auto">
                Sertifikat akan diunggah oleh Admin setelah masa magang/pkl Anda selesai dilaksanakan.
            </p>
        </div>
    @endif
</div>
@endsection