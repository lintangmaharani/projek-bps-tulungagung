@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Tombol Kembali & Judul -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('admin.peserta.index') }}" class="text-xs font-bold text-bpsBlue hover:underline flex items-center mb-1">
                &larr; Kembali ke Daftar Peserta
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Detail Jurnal Kegiatan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Monitoring aktivitas harian peserta magang atau PKL.</p>
        </div>
    </div>

    <!-- KARTU IDENTITAS SINGKAT PESERTA -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row items-center gap-6">
        <!-- Foto Profil -->
        <div class="flex-shrink-0">
            @if(isset($pengajuan->foto) && $pengajuan->foto)
                <img src="{{ asset('storage/' . $pengajuan->foto) }}" alt="Foto" class="w-24 h-24 rounded-2xl object-cover border-2 border-slate-200 shadow-sm">
            @else
                <div class="w-24 h-24 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xl border-2 border-slate-200 shadow-sm">
                    {{ strtoupper(substr($pengajuan->nama_lengkap, 0, 2)) }}
                </div>
            @endif
        </div>

        <!-- Detail Identitas -->
        <div class="flex-grow w-full space-y-3">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                <div>
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-black uppercase rounded bg-blue-50 text-blue-700 border border-blue-100 mb-1">
                        {{ $pengajuan->tipe_pendidikan }}
                    </span>
                    <h2 class="text-lg font-black text-slate-800">{{ $pengajuan->nama_lengkap }}</h2>
                </div>
                <div class="text-xs text-slate-500 font-medium">
                    Periode: <b class="text-slate-700">{{ \Carbon\Carbon::parse($pengajuan->tgl_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->tgl_selesai)->format('d M Y') }}</b>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-slate-600 border-t border-slate-100 pt-3">
                <div>
                    <p><span class="font-bold text-slate-700">Sekolah / Kampus:</span> {{ $pengajuan->nama_instansi }}</p>
                    <p class="mt-1"><span class="font-bold text-slate-700">Jurusan :</span> {{ $pengajuan->prodi_jurusan }}</p>
                </div>
                <div>
                    <p><span class="font-bold text-slate-700">Email:</span> {{ $pengajuan->email ?? '-' }}</p>
                    <p class="mt-1"><span class="font-bold text-slate-700">No. HP :</span> {{ $pengajuan->no_hp ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL RIWAYAT JURNAL HARIAN -->
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
                        <th class="p-4">Divisi</th> <!-- Kolom Divisi Baru -->
                        <th class="p-4">Judul Kegiatan</th>
                        <th class="p-4">Deskripsi / Detail Pekerjaan</th>
                        <th class="p-4 text-center">Dokumentasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($jurnals as $j)
                        <tr class="hover:bg-slate-50/50">
                            <!-- Kolom Tanggal & Waktu -->
                            <td class="p-4 whitespace-nowrap align-top">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $j->jam_mulai ? \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') : '--:--' }} - 
                                    {{ $j->jam_selesai ? \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') : '--:--' }} WIB
                                </div>
                            </td>

                            <!-- Kolom Divisi -->
                            <td class="p-4 align-top">
                                <span class="inline-block px-2.5 py-1 bg-blue-50 text-bpsBlue font-semibold rounded-lg text-xs">
                                    {{ $j->divisi->nama_divisi ?? 'Tanpa Divisi' }}
                                </span>
                            </td>

                            <!-- Kolom Judul Kegiatan -->
                            <td class="p-4 font-bold text-slate-800 align-top">
                                {{ $j->kegiatan }}
                            </td>

                            <!-- Kolom Deskripsi -->
                            <td class="p-4 text-slate-600 leading-relaxed max-w-xs align-top">
                                {{ $j->deskripsi }}
                            </td>

                            <!-- Kolom Dokumentasi -->
                            <td class="p-4 text-center align-top">
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
                            <td colspan="5" class="p-8 text-center text-slate-400">Mahasiswa ini belum pernah mengisi jurnal kegiatan.</td>
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