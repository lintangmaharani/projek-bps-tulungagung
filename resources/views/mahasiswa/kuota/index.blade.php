@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
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

    <!-- Grid List Kuota -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($kuotas as $k)
            @php 
                $terisi = $k->pengajuans_count ?? $k->terisi ?? 0;
                $sisa = $k->jumlah_kuota - $terisi;
                $isFull = $sisa <= 0;
                $isTutup = $k->status == 'tutup';
            @endphp

            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-bold text-bpsBlue bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                            Periode Magang
                        </span>
                        <h2 class="text-base font-extrabold text-slate-800 mt-2">{{ $k->nama_periode }}</h2>
                    </div>

                    <!-- Badge Status Operasional -->
                    @if($isTutup)
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold rounded-full uppercase">Pendaftaran Ditutup</span>
                    @elseif($isFull)
                        <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-bold rounded-full uppercase">Kuota Penuh</span>
                    @else
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full uppercase">Tersedia</span>
                    @endif
                </div>

                <!-- Detail Tanggal & Deskripsi -->
                <div class="text-xs space-y-2">
                    <div class="flex items-center space-x-2 text-slate-600">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>
                            {{ \Carbon\Carbon::parse($k->tgl_mulai)->translatedFormat('d M Y') }} — 
                            {{ \Carbon\Carbon::parse($k->tgl_selesai)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    @if($k->keterangan)
                        <p class="text-slate-500 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100 text-[11px]">
                            {{ $k->keterangan }}
                        </p>
                    @endif
                </div>

                <!-- Progress Bar Kuota -->
                <div class="space-y-2 border-t pt-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500 font-medium">Pengisian Kuota</span>
                        <span class="font-bold text-slate-800">{{ $terisi }} / {{ $k->jumlah_kuota }} Orang</span>
                    </div>

                    <!-- Visual Progress Bar -->
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        @php 
                            $percentage = min(100, ($terisi / max(1, $k->jumlah_kuota)) * 100); 
                        @endphp
                        <div class="h-2.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-bpsBlue' }}" style="width: {{ $percentage }}%"></div>
                    </div>

                    <div class="flex justify-between items-center text-[11px] pt-1">
                        <span class="text-slate-400">Sisa Kuota:</span>
                        <span class="font-bold {{ $sisa > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $sisa > 0 ? $sisa . '  Tersisa' : 'Habis' }}
                        </span>
                    </div>
                </div>

                <!-- CTA Button Per Card -->
                <div>
                    @if($isTutup || $isFull)
                        <button disabled class="w-full py-2.5 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                            Pendaftaran Tidak Tersedia
                        </button>
                    @else
                        <a href="{{ route('pengajuan.index') }}" class="block w-full text-center py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition shadow-md">
                            Pilih Periode Ini
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3">
                <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Kuota Dibuka</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                    Saat ini belum ada periode pendaftaran PKL/Magang yang aktif. Silakan cek kembali secara berkala.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($kuotas, 'links'))
        <div class="pt-4">
            {{ $kuotas->links() }}
        </div>
    @endif
</div>
@endsection