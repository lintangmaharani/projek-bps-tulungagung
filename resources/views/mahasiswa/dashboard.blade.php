@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header Sambutan & Lonceng Notifikasi Mahasiswa -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Gunakan menu di samping atau pintasan di bawah untuk mengakses fitur aplikasi.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Tombol Buat Pengajuan Baru -->
            <a href="{{ route('pengajuan.index') }}" class="px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-2xl transition shadow-sm shadow-orange-200">
                + Buat Pengajuan Baru
            </a>

            <!-- FITUR LONCENG NOTIFIKASI MAHASISWA -->
            <div class="relative" x-data="{ openNotif: false }">
                <button @click="openNotif = !openNotif" class="relative p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-2xl text-slate-600 transition focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    
                    @php
                        $pendingCount = isset($pengajuans) ? $pengajuans->where('status', 'diproses')->count() : 0;
                    @endphp
                    @if($pendingCount > 0)
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                    @endif
                </button>

                <!-- Dropdown Box Notifikasi -->
                <div x-show="openNotif" @click.away="openNotif = false" class="absolute right-0 mt-3 w-80 bg-white border border-slate-200 rounded-3xl shadow-xl overflow-hidden z-50 p-4 space-y-3" style="display: none;">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                        <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Notifikasi Aktivitas</h3>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">
                            Pemberitahuan
                        </span>
                    </div>

                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        <!-- Notifikasi Status Pengajuan -->
                        @forelse($pengajuans ?? [] as $item)
                            <a href="{{ route('pengajuan.status') }}" class="block p-3 bg-slate-50 hover:bg-blue-50/50 border border-slate-100 rounded-2xl transition">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-800">Status Pengajuan</span>
                                    <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded-full 
                                        {{ $item->status == 'diproses' ? 'bg-amber-50 text-amber-600' : '' }}
                                        {{ $item->status == 'diterima' ? 'bg-emerald-50 text-emerald-600' : '' }}
                                        {{ $item->status == 'ditolak' ? 'bg-rose-50 text-rose-600' : '' }}">
                                        {{ $item->status }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Instansi: <span class="font-semibold text-slate-700">{{ $item->nama_instansi }}</span></p>
                                <span class="text-[9px] text-slate-400 mt-1 block">{{ $item->updated_at ? $item->updated_at->diffForHumans() : '' }}</span>
                            </a>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-4">Belum ada riwayat pengajuan.</p>
                        @endforelse

                        <!-- Notifikasi Sertifikat (Jika Ada) -->
                        @foreach($sertifikats ?? [] as $cert)
                            <a href="{{ route('mahasiswa.sertifikat') }}" class="block p-3 bg-purple-50/50 hover:bg-purple-50 border border-purple-100 rounded-2xl transition">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-purple-900">Sertifikat Terbit! 🎉</span>
                                </div>
                                <p class="text-[11px] text-purple-600 mt-1">Sertifikat magang Anda sudah dapat diunduh.</p>
                            </a>
                        @endforeach
                    </div>

                    <div class="pt-1 text-center border-t border-slate-100">
                        <a href="{{ route('pengajuan.status') }}" class="text-[11px] font-bold text-blue-600 hover:underline">Cek Status Selengkapnya &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 Kartu Menu Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Kartu 1: Status Pengajuan -->
        <a href="{{ route('pengajuan.status') }}" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2 hover:border-blue-500 hover:shadow-md transition block group">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider group-hover:text-blue-600 transition">Status Pengajuan</div>
            <div class="text-lg font-bold text-slate-800 flex items-center justify-between">
                <span>Cek Status</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <p class="text-xs text-slate-400">Pantau progres pengajuan PKL/Magang Anda.</p>
        </a>

        <!-- Kartu 2: Jurnal Kegiatan -->
        <a href="{{ route('mahasiswa.jurnal.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2 hover:border-blue-500 hover:shadow-md transition block group">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider group-hover:text-blue-600 transition">Jurnal Kegiatan</div>
            <div class="text-lg font-bold text-slate-800 flex items-center justify-between">
                <span>Catatan Harian</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <p class="text-xs text-slate-400">Isi laporan kegiatan magang secara rutin.</p>
        </a>

        <!-- Kartu 3: Sertifikat -->
        <a href="{{ route('mahasiswa.sertifikat') }}" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2 hover:border-blue-500 hover:shadow-md transition block group">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider group-hover:text-blue-600 transition">Sertifikat</div>
            <div class="text-lg font-bold text-slate-800 flex items-center justify-between">
                <span>Unduh Dokumen</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <p class="text-xs text-slate-400">Tersedia setelah program magang selesai.</p>
        </a>
    </div>

    <!-- Alur Program Card -->
    <div class="bg-blue-900 text-white rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
        <h3 class="font-extrabold text-base flex items-center gap-2">
            <span>📌 Alur Program PKL & Magang BPS Tulungagung</span>
        </h3>
        <ol class="list-decimal list-inside space-y-2 text-xs sm:text-sm text-blue-100">
            <li>Memeriksa ketersediaan kuota pada menu <span class="font-bold text-white">Informasi Kuota</span>.</li>
            <li>Mengisi formulir pengajuan lengkap beserta dokumen pendukung pada menu <span class="font-bold text-white">Form Pengajuan</span>.</li>
            <li>Memantau status persetujuan admin pada menu <span class="font-bold text-white">Status Pengajuan</span> atau melalui lonceng notifikasi di atas.</li>
            <li>Jika diterima, aktif mengisi <span class="font-bold text-white">Jurnal Kegiatan</span> harian hingga program selesai.</li>
        </ol>
    </div>

</div>
@endsection