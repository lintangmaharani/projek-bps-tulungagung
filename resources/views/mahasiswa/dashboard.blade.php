@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Banner Sambutan -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800">
                Selamat Datang, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Gunakan sidebar di sebelah kiri atau tombol di samping untuk mengakses fitur aplikasi.
            </p>
        </div>
        <a href="{{ route('pengajuan.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-bpsOrange hover:bg-orange-600 text-white transition shadow-md shadow-orange-500/20">
            + Buat Pengajuan Baru
        </a>
    </div>

    <!-- Grid Informasi / Statistik Singkat (Bisa Diklik) -->
    <div class="grid sm:grid-cols-3 gap-5">
        <!-- Kartu 1: Status Pengajuan -->
        <a href="{{ route('pengajuan.status') }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-2 hover:border-bpsBlue hover:shadow-md transition block group">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider group-hover:text-bpsBlue transition">Status Pengajuan</div>
            <div class="text-lg font-bold text-slate-800 flex items-center justify-between">
                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-bold border border-amber-200">Cek Status</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <p class="text-[11px] text-slate-400">Pantau progres pengajuan PKL/Magang Anda.</p>
        </a>

        <!-- Kartu 2: Jurnal Kegiatan -->
        <a href="{{ route('mahasiswa.jurnal.index') ?? '#' }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-2 hover:border-bpsBlue hover:shadow-md transition block group">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider group-hover:text-bpsBlue transition">Jurnal Kegiatan</div>
            <div class="text-lg font-bold text-slate-800 flex items-center justify-between">
                <span>Catatan Harian</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <p class="text-[11px] text-slate-400">Isi laporan kegiatan magang secara rutin.</p>
        </a>

        <!-- Kartu 3: Sertifikat -->
        <a href="{{ route('mahasiswa.sertifikat') }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-2 hover:border-bpsBlue hover:shadow-md transition block group">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider group-hover:text-bpsBlue transition">Sertifikat</div>
            <div class="text-lg font-bold text-slate-800 flex items-center justify-between">
                <span>Unduh Dokumen</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <p class="text-[11px] text-slate-400">Tersedia setelah program magang selesai.</p>
        </a>
    </div>

    <!-- Panduan / Informasi Singkat -->
    <div class="bg-gradient-to-r from-blue-900 to-bpsBlue rounded-3xl p-6 text-white shadow-lg space-y-3">
        <h3 class="font-bold text-base flex items-center gap-2">
            📌 Alur Program PKL & Magang BPS Tulungagung
        </h3>
        <ol class="list-decimal list-inside text-xs space-y-1.5 text-slate-200 leading-relaxed">
            <li>Memeriksa ketersediaan kuota pada menu <span class="font-semibold text-white">Informasi Kuota</span>.</li>
            <li>Mengisi formulir pengajuan lengkap beserta dokumen pendukung pada menu <span class="font-semibold text-white">Form Pengajuan</span>.</li>
            <li>Memantau status persetujuan admin pada menu <span class="font-semibold text-white">Status Pengajuan</span>.</li>
            <li>Jika diterima, aktif mengisi <span class="font-semibold text-white">Jurnal Kegiatan</span> harian hingga program selesai.</li>
        </ol>
    </div>
</div>
@endsection