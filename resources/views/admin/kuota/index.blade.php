@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kelola Kuota Magang & PKL</h1>
            <p class="text-xs text-slate-500 mt-1">Atur kapasitas dan batas kuota berdasarkan kategori pendidikan.</p>
        </div>
        
        <!-- Button Modal Tambah -->
        <button onclick="toggleModal('modal-tambah')" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-bpsBlue text-white text-xs font-bold rounded-xl hover:bg-bpsDark shadow-lg shadow-blue-900/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Kuota Kategori</span>
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center space-x-2 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- TABEL KUOTA -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Kategori Pendidikan</th>
                        <th class="py-3.5 px-4">Durasi Pelaksanaan</th>
                        <th class="py-3.5 px-4 text-center">Batas Kuota</th>
                        <th class="py-3.5 px-4 text-center">Terisi</th>
                        <th class="py-3.5 px-4 text-center">Sisa Slot</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kuotas as $k)
                        @php 
                            $terisi = $k->terisi ?? 0;
                            $sisa = $k->jumlah_kuota - $terisi;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-blue-50 text-bpsBlue font-extrabold rounded-lg text-xs uppercase tracking-wider inline-block">
                                    {{ $k->kategori }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                {{ \Carbon\Carbon::parse($k->tgl_mulai)->translatedFormat('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($k->tgl_selesai)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700">
                                {{ $k->jumlah_kuota }} Orang
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-blue-600">
                                {{ $terisi }} Orang
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[11px] {{ $sisa > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $sisa > 0 ? $sisa . ' Slot' : 'Penuh' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($k->status == 'buka')
                                    <span class="px-2.5 py-1 bg-blue-50 text-bpsBlue border border-blue-200 rounded-full font-bold text-[10px] uppercase">Dibuka</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-full font-bold text-[10px] uppercase">Ditutup</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center space-x-1">
                                <!-- Tombol Edit -->
                                <button onclick="editModal({{ json_encode($k) }})" class="px-3 py-1.5 bg-amber-50 text-amber-600 font-semibold rounded-lg hover:bg-amber-100 transition inline-block">
                                    Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.kuota.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuota ini?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 font-semibold rounded-lg hover:bg-rose-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                Belum ada kuota kategori yang diatur. Klik tombol di atas untuk menambah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100">
            {{ $kuotas->links() }}
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KUOTA -->
<div id="modal-tambah" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="font-bold text-slate-800 text-sm">Atur Kuota Kategori Baru</h3>
            <button onclick="toggleModal('modal-tambah')" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>

        <form action="{{ route('admin.kuota.store') }}" method="POST" class="space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Kategori Pendidikan</label>
                <select name="kategori" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 bg-white focus:ring-2 focus:ring-bpsBlue">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Siswa SMK">Siswa SMK</option>
                    <option value="Mahasiswa Magang">Mahasiswa Magang</option>
                    <option value="Mahasiswa PKL">Mahasiswa PKL</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 focus:ring-2 focus:ring-bpsBlue">
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 focus:ring-2 focus:ring-bpsBlue">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Jumlah Kuota</label>
                    <input type="number" name="jumlah_kuota" min="1" placeholder="Misal: 5" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 focus:ring-2 focus:ring-bpsBlue">
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 bg-white focus:ring-2 focus:ring-bpsBlue">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                    </select>
                </div>
            </div>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="toggleModal('modal-tambah')" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 bg-bpsBlue text-white font-bold rounded-xl hover:bg-bpsDark">Simpan Kuota</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT KUOTA -->
<div id="modal-edit" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="font-bold text-slate-800 text-sm">Edit Kuota Kategori</h3>
            <button onclick="toggleModal('modal-edit')" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>

        <form id="form-edit" method="POST" class="space-y-3 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Kategori Pendidikan</label>
                <select name="kategori" id="edit-kategori" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 bg-white focus:ring-2 focus:ring-bpsBlue">
                    <option value="Siswa SMK">Siswa SMK (Januari - Juni)</option>
                    <option value="Mahasiswa Magang">Mahasiswa Magang (Juli - Desember)</option>
                    <option value="Mahasiswa PKL">Mahasiswa PKL (Juli - Agustus)</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" id="edit-mulai" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 focus:ring-2 focus:ring-bpsBlue">
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" id="edit-selesai" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 focus:ring-2 focus:ring-bpsBlue">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Jumlah Kuota</label>
                    <input type="number" name="jumlah_kuota" id="edit-jumlah" min="1" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 focus:ring-2 focus:ring-bpsBlue">
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" id="edit-status" required class="w-full px-3.5 py-2 border rounded-xl border-slate-300 bg-white focus:ring-2 focus:ring-bpsBlue">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                    </select>
                </div>
            </div>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="toggleModal('modal-edit')" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        document.getElementById(modalId).classList.toggle('hidden');
    }

    function editModal(data) {
        document.getElementById('form-edit').action = "/admin/kuota/" + data.id;
        
        document.getElementById('edit-kategori').value = data.kategori;
        document.getElementById('edit-mulai').value = data.tgl_mulai;
        document.getElementById('edit-selesai').value = data.tgl_selesai;
        document.getElementById('edit-jumlah').value = data.jumlah_kuota;
        document.getElementById('edit-status').value = data.status;

        toggleModal('modal-edit');
    }
</script>
@endsection