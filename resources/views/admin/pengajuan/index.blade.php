@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kelola Pengajuan PKL / Magang</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar mahasiswa/siswa yang mengajukan pendaftaran magang di BPS Tulungagung.</p>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center space-x-2 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- NOTIFIKASI ERROR VALIDASI -->
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm space-y-1">
            @foreach($errors->all() as $error)
                <p>⚠️ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Form Pencarian dan Filter -->
    <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        
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
                <a href="{{ route('admin.pengajuan.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition shadow-sm">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- TABEL PENGAJUAN -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-4 text-center">No</th>
                        <th class="py-4 px-4">Foto</th>
                        <th class="py-4 px-4">Nama</th>
                        <th class="py-4 px-4">Kampus / Jurusan & Semester</th>
                        <th class="py-4 px-4">Periode Magang</th>
                        <th class="py-4 px-4">Berkas Pemohon</th>
                        <th class="py-4 px-4">Status & Surat Balasan</th>
                        <th class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengajuanList as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Nomor Urut -->
                            <td class="py-4 px-4 text-center font-bold text-slate-500">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Foto Pemohon -->
                            <td class="py-4 px-4">
                                @if(isset($item->foto) && $item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs border border-slate-200">
                                        {{ strtoupper(substr($item->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Nama & Kontak -->
                            <td class="py-4 px-4">
                                <p class="font-bold text-slate-800 text-sm">{{ $item->nama_lengkap }}</p>
                                <p class="text-slate-400">{{ $item->email }}</p>
                                <p class="text-slate-400">{{ $item->no_hp }}</p>
                            </td>

                            <!-- Kampus / Jurusan & Semester -->
                            <td class="py-4 px-4">
                                <p class="font-semibold text-slate-700">{{ $item->nama_instansi }}</p>
                                <p class="text-slate-400">{{ $item->prodi_jurusan }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600">
                                    Semester/Tingkat : {{ $item->tingkat }}
                                </span>
                            </td>

                            <!-- Periode Magang (Kategori & Rentang Tanggal) -->
                            <td class="py-4 px-4">
                                <span class="inline-block px-2 py-0.5 text-[10px] font-black uppercase rounded bg-blue-50 text-blue-700 border border-blue-100 mb-1">
                                    {{ $item->tipe_pendidikan }}
                                </span>
                                <p class="font-medium text-slate-700 text-[11px]">
                                    {{ date('d M Y', strtotime($item->tgl_mulai)) }} <br>
                                    <span class="text-slate-400">s/d</span> <br>
                                    {{ date('d M Y', strtotime($item->tgl_selesai)) }}
                                </p>
                            </td>

                            <!-- Berkas Pemohon (Hanya Surat Pengantar yang Bisa Diklik) -->
                            <td class="py-4 px-4">
                                @if($item->file_surat_pengantar)
                                    <a href="{{ asset('storage/' . $item->file_surat_pengantar) }}" target="_blank" class="inline-flex items-center space-x-1 text-bpsBlue hover:underline font-semibold bg-slate-50 border border-slate-200 px-2.5 py-1.5 rounded-xl transition">
                                        <span>👁️ Lihat Surat Pengantar</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Tidak ada file</span>
                                @endif
                            </td>

                            <!-- Status & Surat Balasan -->
                            <td class="py-4 px-4">
                                @if($item->status == 'diproses')
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider text-amber-700 bg-amber-100 border border-amber-200">
                                        Diproses
                                    </span>
                                @elseif($item->status == 'diterima')
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 border border-emerald-200">
                                        Diterima
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider text-rose-700 bg-rose-100 border border-rose-200">
                                        Ditolak
                                    </span>
                                @endif

                                @if($item->file_surat_balasan)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $item->file_surat_balasan) }}" target="_blank" class="text-[11px] font-bold text-emerald-700 hover:underline flex items-center space-x-1">
                                            <span>📄 Lihat Surat Balasan</span>
                                        </a>
                                    </div>
                                @else
                                    <p class="text-[10px] text-slate-400 mt-1 italic">Belum ada surat balasan</p>
                                @endif
                            </td>

                            <!-- Aksi (Terima / Tolak) -->
                            <td class="py-4 px-4 text-center">
                                <div class="flex justify-center space-x-1">
                                    <!-- Tombol Terima -->
                                    <button type="button" 
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama_lengkap }}"
                                        data-catatan="{{ $item->catatan_revisi }}"
                                        onclick="openModalTerima(this)"
                                        class="px-3 py-1.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-sm transition">
                                        Terima
                                    </button>

                                    <!-- Tombol Tolak -->
                                    <button type="button" 
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama_lengkap }}"
                                        data-catatan="{{ $item->catatan_revisi }}"
                                        onclick="openModalTolak(this)"
                                        class="px-3 py-1.5 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-sm transition">
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 italic">Belum ada data pengajuan yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TERIMA (Upload Surat Balasan + Keterangan Opsional) -->
<div id="modalTerima" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-100">
        <h3 class="text-base font-bold text-slate-800" id="titleTerima">Terima Pengajuan</h3>
        
        <form id="formTerima" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="diterima">

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Upload Surat Balasan / Penerimaan (PDF/DOC)*:</label>
                <input type="file" name="file_surat_balasan" required accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                <p class="text-[10px] text-slate-400 mt-1">Format: *.pdf, *.doc, *.docx (Maks 5MB).</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Catatan (Opsional):</label>
                <textarea name="catatan" id="catatanTerima" rows="3" placeholder="Masukkan keterangan tambahan untuk pemohon..." class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-emerald-500 border-slate-300"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalTerima')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-300">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md hover:bg-emerald-700">
                    Simpan & Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TOLAK (Hanya Keterangan/Alasan Saja Tanpa Upload File) -->
<div id="modalTolak" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-100">
        <h3 class="text-base font-bold text-slate-800" id="titleTolak">Tolak Pengajuan</h3>
        
        <form id="formTolak" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="ditolak">

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Alasan / Keterangan Penolakan*:</label>
                <textarea name="catatan" id="catatanTolak" required rows="4" placeholder="Masukkan alasan penolakan pengajuan..." class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-rose-500 border-slate-300"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalTolak')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-300">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md hover:bg-rose-700">
                    Kirim Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTerima(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const catatan = button.getAttribute('data-catatan');

        const modal = document.getElementById('modalTerima');
        const form = document.getElementById('formTerima');
        
        form.action = `/admin/pengajuan/${id}/status`;
        document.getElementById('titleTerima').innerText = `Terima Pengajuan: ${nama}`;
        document.getElementById('catatanTerima').value = catatan ? catatan : '';

        modal.classList.remove('hidden');
    }

    function openModalTolak(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const catatan = button.getAttribute('data-catatan');

        const modal = document.getElementById('modalTolak');
        const form = document.getElementById('formTolak');
        
        form.action = `/admin/pengajuan/${id}/status`;
        document.getElementById('titleTolak').innerText = `Tolak Pengajuan: ${nama}`;
        document.getElementById('catatanTolak').value = catatan ? catatan : '';

        modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection