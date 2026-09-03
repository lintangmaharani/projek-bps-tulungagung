@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('jurnal.index') }}" class="text-xs font-bold text-bpsBlue hover:underline flex items-center mb-1">
                &larr; Kembali ke Jurnal Saya
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Edit Jurnal Kegiatan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui detail aktivitas harian magang atau PKL Anda.</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
        <form action="{{ route('jurnal.update', $jurnal->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- KOTAK PENAMPIL PESAN ERROR (Agar terlihat jika validasi menolak) --}}
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $jurnal->tanggal) }}" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jurnal->jam_mulai) }}" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jurnal->jam_selesai) }}" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Judul / Ringkasan Kegiatan</label>
                <input type="text" name="kegiatan" value="{{ old('kegiatan', $jurnal->kegiatan) }}" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Rincian / Deskripsi Kegiatan</label>
                <textarea name="deskripsi" rows="4" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">{{ old('deskripsi', $jurnal->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Divisi Tempat Bertugas</label>
                <select name="divisi_id" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300 bg-white">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisis as $divisi)
                        <option value="{{ $divisi->id }}" {{ old('divisi_id', $jurnal->divisi_id) == $divisi->id ? 'selected' : '' }}>
                            {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Ganti Foto / Dokumentasi (Opsional)</label>
                @if($jurnal->file_dokumentasi)
                    <div class="mb-2 text-xs text-slate-500">
                        File saat ini: <a href="{{ asset('storage/' . $jurnal->file_dokumentasi) }}" target="_blank" class="text-bpsBlue font-bold underline">Lihat File</a>
                    </div>
                @endif
                <input type="file" name="file_dokumentasi" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-bpsBlue file:text-white hover:file:bg-bpsDark cursor-pointer">
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <a href="{{ route('jurnal.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection