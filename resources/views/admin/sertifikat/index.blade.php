@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kelola Sertifikat Magang / PKL</h1>
        <p class="text-xs text-slate-500 mt-1">Upload berkas sertifikat untuk mahasiswa/siswa yang telah selesai magang/pkl.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase">
                        <th class="p-4">Nama & Email</th>
                        <th class="p-4">Instansi</th>
                        <th class="p-4">Status Sertifikat</th>
                        <th class="p-4 text-center">Aksi Upload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($pengajuans as $p)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4">
                                <div class="font-bold text-slate-800">{{ $p->nama_lengkap }}</div>
                                <div class="text-[11px] text-slate-400">{{ $p->email }}</div>
                            </td>
                            <td class="p-4 font-medium">{{ $p->nama_instansi }}</td>
                            <td class="p-4">
                                @if($p->file_sertifikat)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        ✓ Sudah Terbit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <form action="{{ route('admin.sertifikat.upload', $p->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center justify-center space-x-2">
                                    @csrf
                                    <input type="file" name="file_sertifikat" accept=".pdf" required class="text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                                    <button type="submit" class="px-3 py-1.5 bg-bpsBlue hover:bg-bpsDark text-white font-bold rounded-lg text-[11px] shadow-sm transition shrink-0">
                                        {{ $p->file_sertifikat ? 'Ganti PDF' : 'Upload' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400">Belum ada mahasiswa yang disetujui untuk penerbitan sertifikat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection