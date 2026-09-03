@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kelola Sertifikat Magang / PKL</h1>
            <p class="text-xs text-slate-500 mt-1">Upload dan kelola berkas sertifikat untuk mahasiswa/siswa yang telah selesai magang atau PKL.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Pencarian dan Filter Kategori -->
    <form method="GET" action="{{ route('admin.sertifikat.index') }}" class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        
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
                <a href="{{ route('admin.sertifikat.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition shadow-sm">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Tabel Daftar Sertifikat -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4">Peserta </th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Dokumen Sertifikat</th>
                        <th class="p-4 text-center">Aksi </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($sertifikats as $item)
                        <tr class="hover:bg-slate-50/50">
                            <!-- Nomor Urut -->
                            <td class="p-4 text-center font-bold text-slate-500 align-middle">
                                {{ $loop->iteration + ($sertifikats->currentPage() - 1) * $sertifikats->perPage() }}
                            </td>

                            <!-- Informasi Peserta -->
                            <td class="p-4 align-middle">
                                <span class="inline-block px-2 py-0.5 text-[9px] font-black uppercase rounded bg-blue-50 text-blue-700 border border-blue-100 mb-1">
                                    {{ $item->tipe_pendidikan }}
                                </span>
                                <p class="font-bold text-slate-800">{{ $item->nama_lengkap }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $item->email }} &bull; {{ $item->nama_instansi }}</p>
                            </td>

                            <!-- Status Sertifikat -->
                            <td class="p-4 text-center align-middle">
                                @if($item->file_sertifikat)
                                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-full text-[11px] border border-emerald-200">
                                        Sudah Ada
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 font-bold rounded-full text-[11px] border border-amber-200">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>

                            <!-- Lihat Dokumen -->
                            <td class="p-4 text-center align-middle">
                                @if($item->file_sertifikat)
                                    <a href="{{ asset('storage/' . $item->file_sertifikat) }}" target="_blank" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 text-bpsBlue font-bold rounded-xl text-[11px] hover:bg-bpsBlue hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Dokumen</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px] font-medium">-</span>
                                @endif
                            </td>

                            <!-- Form Upload / Update / Hapus (CRUD) -->
                            <td class="p-4 text-center align-middle">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.sertifikat.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png" required class="text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer w-40">
                                        
                                        <button type="submit" class="px-3 py-2 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl shadow-md transition shrink-0">
                                            {{ $item->file_sertifikat ? 'Update' : 'Upload' }}
                                        </button>
                                    </form>

                                    @if($item->file_sertifikat)
                                        <form action="{{ route('admin.sertifikat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sertifikat ini?');" class="shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <!-- Tombol Hapus dengan teks tulisan -->
                                            <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition shadow-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada data peserta atau sertifikat ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $sertifikats->links() }}
        </div>
    </div>
</div>
@endsection