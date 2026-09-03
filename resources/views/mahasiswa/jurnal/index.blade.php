@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Jurnal Kegiatan Harian</h1>
            <p class="text-xs text-slate-500 mt-1">Isi dan catat aktivitas PKL / Magang harian Anda di BPS Tulungagung.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- FORM INPUT JURNAL -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 space-y-4">
        <h2 class="text-sm font-bold text-slate-800 border-b pb-3">Tambah Jurnal Kegiatan</h2>
        <form action="{{ route('jurnal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Judul / Ringkasan Kegiatan</label>
                <input type="text" name="kegiatan" required placeholder="Contoh: Input Data Sensus Ekonomi 2026" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Rincian / Deskripsi Kegiatan</label>
                <textarea name="deskripsi" rows="3" required placeholder="Jelaskan detail pekerjaan atau aktivitas yang dilakukan..." class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Divisi Tempat Bertugas</label>
                <select name="divisi_id" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300 bg-white">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisis as $divisi)
                        <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Foto / Dokumentasi (Opsional: JPG, PNG, PDF - Max 3MB)</label>
                <input type="file" name="file_dokumentasi" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-bpsBlue file:text-white hover:file:bg-bpsDark cursor-pointer">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl shadow-md transition">
                    Simpan Jurnal
                </button>
            </div>
        </form>
    </div>

    <!-- RIWAYAT JURNAL -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-800">Riwayat Jurnal Kegiatan</h2>
        </div>
        
        <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-3 px-4">Tanggal & Waktu</th>
                    <th class="py-3 px-4">Divisi</th> <!-- Kolom Divisi Baru -->
                    <th class="py-3 px-4">Kegiatan & Deskripsi</th>
                    <th class="py-3 px-4">Dokumentasi</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($jurnals as $jurnal)
                    <tr>
                        {{-- Kolom Tanggal & Waktu --}}
                        <td class="py-4 px-4 align-top">
                            <div class="font-bold text-slate-800 text-xs">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                {{ $jurnal->jam_mulai ? substr($jurnal->jam_mulai, 0, 5) : '--:--' }} - 
                                {{ $jurnal->jam_selesai ? substr($jurnal->jam_selesai, 0, 5) : '--:--' }} WIB
                            </div>
                        </td>

                        {{-- Kolom Divisi (Dipisah Sendiri) --}}
                        <td class="py-4 px-4 align-top">
                            <span class="inline-block px-2.5 py-1 bg-blue-50 text-bpsBlue font-semibold rounded-lg text-xs">
                                {{ $jurnal->divisi->nama_divisi ?? 'Tanpa Divisi' }}
                            </span>
                        </td>

                        {{-- Kolom Kegiatan & Deskripsi --}}
                        <td class="py-4 px-4 align-top">
                            <div class="font-bold text-slate-800 text-xs mb-0.5">{{ $jurnal->kegiatan }}</div>
                            <div class="text-xs text-slate-500 line-clamp-2">{{ $jurnal->deskripsi }}</div>
                        </td>

                        {{-- Kolom Dokumentasi --}}
                        <td class="py-4 px-4 align-top">
                            @if($jurnal->file_dokumentasi)
                                <a href="{{ asset('storage/' . $jurnal->file_dokumentasi) }}" target="_blank" class="text-xs font-semibold text-bpsBlue hover:underline flex items-center space-x-1">
                                    <span>Lihat File</span>
                                </a>
                            @else
                                <span class="text-xs text-slate-400">Tidak ada</span>
                            @endif
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="py-4 px-4 align-top text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('jurnal.edit', $jurnal->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition">
                                    <!-- Ikon Edit -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('jurnal.destroy', $jurnal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurnal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition">
                                        <!-- Ikon Hapus -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-xs text-slate-400">Belum ada riwayat jurnal kegiatan yang dicatat.</td>
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