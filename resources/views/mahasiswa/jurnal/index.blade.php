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
                    <tr class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                        <th class="p-4">Tanggal & Waktu</th>
                        <th class="p-4">Kegiatan & Deskripsi</th>
                        <th class="p-4">Dokumentasi</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($jurnals as $jurnal)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $jurnal->jam_mulai ? \Carbon\Carbon::parse($jurnal->jam_mulai)->format('H:i') : '-' }} - 
                                    {{ $jurnal->jam_selesai ? \Carbon\Carbon::parse($jurnal->jam_selesai)->format('H:i') : '-' }} WIB
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-slate-800">{{ $jurnal->kegiatan }}</p>
                                <p class="text-slate-500 text-[11px] mt-1 leading-relaxed">{{ $jurnal->deskripsi }}</p>
                            </td>
                            <td class="p-4">
                                @if($jurnal->file_dokumentasi)
                                    <a href="{{ asset('storage/' . $jurnal->file_dokumentasi) }}" target="_blank" class="inline-flex items-center space-x-1 text-bpsBlue font-semibold hover:underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>Lihat File</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('jurnal.destroy', $jurnal->id) }}" method="POST" onsubmit="return confirm('Hapus jurnal kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">Belum ada jurnal kegiatan yang dibuat.</td>
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