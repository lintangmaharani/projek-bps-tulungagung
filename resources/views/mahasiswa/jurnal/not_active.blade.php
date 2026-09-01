@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 text-center space-y-4">
    <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
    </div>
    <h1 class="text-xl font-black text-slate-800">Fitur Jurnal Belum Aktif</h1>
    <p class="text-xs text-slate-500 leading-relaxed max-w-md mx-auto">
        Fitur pengisian jurnal harian hanya terbuka untuk mahasiswa yang status pengajuan PKL/Magangnya sudah **Diterima** oleh BPS Tulungagung.
    </p>
    <div class="pt-2">
        <a href="{{ route('pengajuan.status') }}" class="inline-flex items-center px-5 py-2.5 bg-bpsBlue text-white text-xs font-bold rounded-xl hover:bg-bpsDark transition">
            Cek Status Pengajuan Anda
        </a>
    </div>
</div>
@endsection