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

    <!-- TABEL PENGAJUAN -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6">Pemohon</th>
                        <th class="py-4 px-6">Instansi / Jurusan</th>
                        <th class="py-4 px-6">Periode Magang</th>
                        <th class="py-4 px-6">Berkas Pemohon</th>
                        <th class="py-4 px-6">Status & Surat Balasan</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengajuanList as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <p class="font-bold text-slate-800 text-sm">{{ $item->nama_lengkap }}</p>
                                <p class="text-slate-400">{{ $item->email }}</p>
                                <p class="text-slate-400">{{ $item->no_hp }}</p>
                            </td>

                            <td class="py-4 px-6">
                                <p class="font-semibold text-slate-700">{{ $item->nama_instansi }}</p>
                                <p class="text-slate-400">{{ $item->prodi_jurusan }} ({{ $item->tingkat }})</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600 uppercase">
                                    {{ $item->tipe_pendidikan }}
                                </span>
                            </td>

                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ date('d M Y', strtotime($item->tgl_mulai)) }} <br>
                                <span class="text-slate-400">s/d</span> <br>
                                {{ date('d M Y', strtotime($item->tgl_selesai)) }}
                            </td>

                            <td class="py-4 px-6 space-y-1">
                                <a href="{{ asset('storage/' . $item->file_cv) }}" target="_blank" class="inline-flex items-center space-x-1 text-bpsBlue hover:underline font-semibold block">
                                    <span>👁️ CV</span>
                                </a>
                                <a href="{{ asset('storage/' . $item->file_surat_pengantar) }}" target="_blank" class="inline-flex items-center space-x-1 text-bpsBlue hover:underline font-semibold block">
                                    <span>👁️ Surat Pengantar</span>
                                </a>
                            </td>

                            <td class="py-4 px-6">
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
                                            <span>📄 Lihat Surat Terkirim</span>
                                        </a>
                                    </div>
                                @else
                                    <p class="text-[10px] text-slate-400 mt-1 italic">Belum ada file surat dikirim</p>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center space-x-1">
                                    <button type="button" 
                                        data-id="{{ $item->id }}"
                                        data-status="diterima"
                                        data-nama="{{ $item->nama_lengkap }}"
                                        data-catatan="{{ $item->catatan_revisi }}"
                                        onclick="handleModal(this)"
                                        class="px-3 py-1.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-sm transition">
                                        Terima
                                    </button>

                                    <button type="button" 
                                        data-id="{{ $item->id }}"
                                        data-status="ditolak"
                                        data-nama="{{ $item->nama_lengkap }}"
                                        data-catatan="{{ $item->catatan_revisi }}"
                                        onclick="handleModal(this)"
                                        class="px-3 py-1.5 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-sm transition">
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada data pengajuan yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL UPLOAD SURAT BALASAN -->
<div id="modalStatus" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-100">
        <h3 class="text-base font-bold text-slate-800" id="modalTitle">Proses Pengajuan</h3>
        
        <form id="formStatus" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" id="inputStatus">

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Upload Surat Penerimaan / Penolakan:</label>
                <input type="file" name="file_surat_balasan" accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                <p class="text-[10px] text-slate-400 mt-1">Upload surat resmi BPS Tulungagung (*.pdf, *.doc, *.docx, maks 5MB).</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Tambahan (Opsional):</label>
                <textarea name="catatan" id="inputCatatan" rows="3" placeholder="Masukkan catatan untuk pemohon..." class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-300">
                    Batal
                </button>
                <button type="submit" id="btnConfirm" class="px-4 py-2 text-white text-xs font-bold rounded-xl shadow-md">
                    Simpan & Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleModal(button) {
        const id = button.getAttribute('data-id');
        const status = button.getAttribute('data-status');
        const nama = button.getAttribute('data-nama');
        const catatan = button.getAttribute('data-catatan');

        const modal = document.getElementById('modalStatus');
        const form = document.getElementById('formStatus');
        const inputStatus = document.getElementById('inputStatus');
        const inputCatatan = document.getElementById('inputCatatan');
        const modalTitle = document.getElementById('modalTitle');
        const btnConfirm = document.getElementById('btnConfirm');

        // Mengatur action form secara dinamis
        form.action = `/admin/pengajuan/${id}/status`;
        inputStatus.value = status;
        inputCatatan.value = catatan ? catatan : '';

        if (status === 'diterima') {
            modalTitle.innerText = `Terima Pengajuan: ${nama}`;
            btnConfirm.className = 'px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md';
        } else {
            modalTitle.innerText = `Tolak Pengajuan: ${nama}`;
            btnConfirm.className = 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md';
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalStatus').classList.add('hidden');
    }
</script>
@endsection