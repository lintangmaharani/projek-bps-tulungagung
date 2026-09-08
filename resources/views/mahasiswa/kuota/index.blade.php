@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Informasi Kuota Magang / PKL</h1>
            <p class="text-xs text-slate-500 mt-1">
                Cek ketersediaan kuota penerimaan mahasiswa PKL/Magang di BPS Tulungagung.
            </p>
        </div>

        <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-900/20 transition">
            <span>Daftar Sekarang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>

    <!-- Filter & Search Bar Terpisah (Sejajar Kiri-Kanan seperti Kelola Sertifikat) -->
    <form method="GET" action="{{ route('kuota.info') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Input Search di Kiri -->
        <div class="w-full md:w-96 relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari periode atau keterangan..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-bpsBlue/20 focus:border-bpsBlue shadow-sm transition">
        </div>

        <!-- Dropdown Kategori di Kanan -->
        <div class="w-full md:w-auto flex items-center gap-2">
            <select name="kategori" onchange="this.form.submit()" class="w-full md:w-56 px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-bpsBlue/20 focus:border-bpsBlue shadow-sm transition cursor-pointer">
                <option value="">Semua Kategori</option>
                <option value="Siswa SMK" {{ request('kategori') == 'Siswa SMK' ? 'selected' : '' }}>Siswa SMK</option>
                <option value="Mahasiswa Magang" {{ request('kategori') == 'Mahasiswa Magang' ? 'selected' : '' }}>Mahasiswa Magang</option>
                <option value="Mahasiswa PKL" {{ request('kategori') == 'Mahasiswa PKL' ? 'selected' : '' }}>Mahasiswa PKL</option>
            </select>

            @if(request('search') || request('kategori'))
                <a href="{{ route('kuota.info') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-2xl transition shrink-0 shadow-sm">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Content Card Utama (Membungkus Grid Kuota) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($kuotas as $k)
                @php 
                    $terisi = $k->pengajuans_count ?? $k->terisi ?? 0;
                    $sisa = $k->jumlah_kuota - $terisi;
                    $isFull = $sisa <= 0;
                    $isTutup = $k->status == 'tutup';
                @endphp

                <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-200/80 space-y-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider font-bold text-bpsBlue bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                {{ $k->kategori }}
                            </span>
                            <h2 class="text-sm font-extrabold text-slate-800 mt-2">{{ $k->nama_periode }}</h2>
                        </div>

                        <!-- Badge Status -->
                        @if($isTutup)
                            <span class="px-3 py-1 bg-slate-200 text-slate-600 text-[10px] font-bold rounded-full uppercase">Ditutup</span>
                        @elseif($isFull)
                            <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-bold rounded-full uppercase">Penuh</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full uppercase">Tersedia</span>
                        @endif
                    </div>

                    <!-- Tanggal & Deskripsi -->
                    <div class="text-xs space-y-1.5">
                        <div class="flex items-center space-x-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>
                                {{ \Carbon\Carbon::parse($k->tgl_mulai)->translatedFormat('d M Y') }} — 
                                {{ \Carbon\Carbon::parse($k->tgl_selesai)->translatedFormat('d M Y') }}
                            </span>
                        </div>

                        @if($k->keterangan)
                            <p class="text-slate-500 bg-white p-2.5 rounded-xl border border-slate-100 text-[11px]">
                                {{ $k->keterangan }}
                            </p>
                        @endif
                    </div>

                    <!-- Kuota Progress -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-200/60">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500 font-medium">Pengisian Kuota</span>
                            <span class="font-bold text-slate-800">{{ $terisi }} / {{ $k->jumlah_kuota }} Orang</span>
                        </div>

                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            @php 
                                $percentage = min(100, ($terisi / max(1, $k->jumlah_kuota)) * 100); 
                            @endphp
                            <div class="h-2 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-bpsBlue' }}" style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-400">Sisa Kuota:</span>
                            <span class="font-bold {{ $sisa > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $sisa > 0 ? $sisa . ' Slot Tersisa' : 'Habis' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Pilih -->
                    <div>
                        @if($isTutup || $isFull)
                            <button disabled class="w-full py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                Pendaftaran Tidak Tersedia
                            </button>
                        @else
                            <a href="{{ route('pengajuan.index') }}" class="block w-full text-center py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                Pilih Periode Ini
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-12 text-center space-y-3">
                    <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Tidak Ada Kuota Ditemukan</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Tidak ada periode kuota magang yang cocok dengan pencarian atau filter Anda.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection