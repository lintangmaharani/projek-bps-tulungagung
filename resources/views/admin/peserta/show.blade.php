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
        <div class="p-6 border-b border-slate-100 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-sm font-bold text-slate-800">
                    Riwayat Jurnal Harian
                </h2>

                <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-semibold">
                    Total: <b>{{ $jurnals->total() }}</b> Kegiatan
                </span>
            </div>


            <form method="GET" class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">

            <!-- Input Pencarian -->
            <div class="relative w-full md:w-1/3">

                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>

                <input 
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kegiatan, deskripsi, divisi..."
                    class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-500 shadow-sm"
                >
            </div>

            <!-- Filter Status -->
            <div class="flex items-center gap-2 w-full md:w-auto">
                <select 
                    name="status"
                    onchange="this.form.submit()"
                    class="w-full md:w-48 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-500 shadow-sm"
                >

                    <option value="">
                        Semua Status
                    </option>
                    <option value="menunggu"{{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diterima"{{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                </select>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.peserta.show', $pengajuan->id) }}"
                    class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition shadow-sm">
                        Reset
                    </a>
                @endif

            </div>

        </form>

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
                        <th class="p-4 text-center">Verifikasi</th> 
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($jurnals as $j)
                        <tr class="{{ $j->sudah_dilihat ? 'bg-blue-100' : 'bg-white' }} hover:bg-slate-100 transition">
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
                                    <a href="{{ route('admin.peserta.dokumentasi', $j->id) }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-blue-50 text-bpsBlue font-bold rounded-xl text-[11px] hover:bg-bpsBlue hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>

                                        <span>Lihat File</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 font-medium text-[11px]">Tanpa File</span>
                                @endif
                            </td>
                            
                            <!-- Kolom Verifikasi -->
                            <td class="p-4 text-center align-top">

                                <!-- Status -->
                                @if($j->status_verifikasi == 'diterima')
                                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold
                                        bg-green-50 text-green-700 border border-green-200">
                                        Diterima
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold
                                        bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        Menunggu
                                    </span>
                                @endif

                                <div class="mt-2">
                                    <button
                                        type="button"
                                        onclick="document.getElementById('modal-{{ $j->id }}').classList.remove('hidden')"
                                        class="px-3 py-1.5 bg-bpsBlue text-white rounded-lg text-[11px] font-bold hover:opacity-90 transition">
                                        {{ $j->status_verifikasi == 'diterima' ? 'Lihat / Edit' : 'Verifikasi' }}
                                    </button>
                                </div>


                                <!-- MODAL VERIFIKASI -->
                                <div
                                    id="modal-{{ $j->id }}"
                                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
                                >

                                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md text-left">

                                        <!-- Header Modal -->
                                        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                                            <div>
                                                <h3 class="text-sm font-black text-slate-800">
                                                    Verifikasi Jurnal
                                                </h3>

                                                <p class="text-[11px] text-slate-500 mt-0.5">
                                                    {{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}
                                                    • {{ $j->kegiatan }}
                                                </p>
                                            </div>

                                            <button
                                                type="button"
                                                onclick="document.getElementById('modal-{{ $j->id }}').classList.add('hidden')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                                                ✕
                                            </button>
                                        </div>


                                        <!-- Form -->
                                        <form
                                            action="{{ route('admin.peserta.verifikasi', $j->id) }}"
                                            method="POST"
                                            class="p-5"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <label class="block text-xs font-bold text-slate-700 mb-2">
                                                Feedback Admin
                                            </label>

                                            <textarea
                                                name="feedback_admin"
                                                rows="4"
                                                placeholder="Tuliskan feedback untuk jurnal peserta..."
                                                class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-500 resize-none"
                                            >{{ $j->feedback_admin }}</textarea>


                                            <div class="flex justify-end gap-2 mt-5">

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('modal-{{ $j->id }}').classList.add('hidden')"
                                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                                                    Batal
                                                </button>

                                                <button
                                                    type="submit"
                                                    name="status_verifikasi"
                                                    value="diterima"
                                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold transition">
                                                    Terima
                                                </button>

                                            </div>

                                        </form>

                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Mahasiswa ini belum pernah mengisi jurnal kegiatan.</td>
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