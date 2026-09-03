@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Peserta Magang / PKL Aktif</h1>
            <p class="text-xs text-slate-500 mt-1">Monitoring keaktifan dan jurnal kegiatan mahasiswa/siswa yang sedang aktif.</p>
        </div>
    </div>

    <!-- Form Pencarian dan Filter -->
    <form method="GET" action="{{ route('admin.peserta.index') }}" class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Input Pencarian -->
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, instansi..." class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-500 shadow-sm">
        </div>

        <!-- Filter Kategori Pendidikan -->
        <div class="flex items-center gap-2 w-full md:w-auto">
            <select name="tipe" onchange="this.form.submit()" class="w-full md:w-48 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-500 shadow-sm">
                <option value="">Semua Kategori</option>
                <option value="Mahasiswa Magang" {{ request('tipe') == 'Mahasiswa Magang' ? 'selected' : '' }}>Mahasiswa Magang</option>
                <option value="Mahasiswa PKL" {{ request('tipe') == 'Mahasiswa PKL' ? 'selected' : '' }}>Mahasiswa PKL</option>
                <option value="Siswa SMK" {{ request('tipe') == 'Siswa SMK' ? 'selected' : '' }}>Siswa SMK</option>
            </select>

            @if(request('search') || request('tipe'))
                <a href="{{ route('admin.peserta.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition shadow-sm">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4 w-16 text-center">Foto</th>
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
                            <!-- Kolom Nomor Urut -->
                            <td class="p-4 text-center font-bold text-slate-500">
                                {{ $loop->iteration + ($pesertaAktif->currentPage() - 1) * $pesertaAktif->perPage() }}
                            </td>

                            <!-- Kolom Foto -->
                            <td class="p-4 text-center">
                                @if(isset($p->foto) && $p->foto)
                                    <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm mx-auto">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs border border-slate-200 mx-auto">
                                        {{ strtoupper(substr($p->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </td>

                            <td class="p-4">
                                <span class="inline-block px-2 py-0.5 text-[9px] font-black uppercase rounded bg-blue-50 text-blue-700 border border-blue-100 mb-1">
                                    {{ $p->tipe_pendidikan }}
                                </span>
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
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada peserta PKL/Magang yang aktif saat ini.</td>
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