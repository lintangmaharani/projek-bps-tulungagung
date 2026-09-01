@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: '', nama_divisi: '', deskripsi: '', status: 'Aktif' } }">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800">Manajemen Divisi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola daftar divisi penempatan pemagang di BPS Kabupaten Tulungagung.</p>
        </div>
        <button @click="editMode = false; form = { id: '', nama_divisi: '', deskripsi: '', status: 'Aktif' }; showModal = true" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-bpsOrange hover:bg-orange-600 text-white transition shadow-md shadow-orange-500/20 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Divisi</span>
        </button>
    </div>

    <!-- Kotak Statistik Ringkas -->
    <div class="grid sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-1">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Divisi</div>
            <div class="text-2xl font-black text-slate-800">{{ $totalDivisi }}</div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-1">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Aktif</div>
            <div class="text-2xl font-black text-emerald-600">{{ $aktifDivisi }}</div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-1">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Nonaktif</div>
            <div class="text-2xl font-black text-slate-400">{{ $nonaktifDivisi }}</div>
        </div>
    </div>

    <!-- Grid Kartu Divisi (Menyesuaikan Gaya Landing Page) -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($divisis as $divisi)
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-bpsSky/40 transition duration-300 relative group">
            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <h3 class="text-base font-bold text-slate-900">{{ $divisi->nama_divisi }}</h3>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $divisi->status == 'Aktif' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                        {{ $divisi->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $divisi->deskripsi ?? 'Belum ada deskripsi untuk divisi ini.' }}</p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-5 mt-4 border-t border-slate-100">
                <!-- Tombol Edit -->
                <button @click="editMode = true; form = { id: '{{ $divisi->id }}', nama_divisi: '{{ $divisi->nama_divisi }}', deskripsi: '{{ addslashes($divisi->deskripsi) }}', status: '{{ $divisi->status }}' }; showModal = true" class="px-3 py-1.5 bg-slate-100 hover:bg-bpsBlue hover:text-white rounded-xl transition text-xs font-bold text-slate-600 flex items-center gap-1.5" title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit</span>
                </button>
                <!-- Tombol Hapus -->
                <form action="{{ route('admin.divisi.destroy', $divisi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus divisi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl transition text-xs font-bold text-red-500 flex items-center gap-1.5" title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white border border-slate-200 rounded-3xl p-12 text-center text-slate-400 text-xs">
            Belum ada data divisi yang ditambahkan. Silakan klik tombol "Tambah Divisi" di atas.
        </div>
        @endforelse
    </div>

    <!-- Modal Tambah/Edit Divisi -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" style="display: none;">
        <div @click.outside="showModal = false" class="bg-white rounded-3xl p-6 w-full max-w-md space-y-5 shadow-2xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-extrabold text-slate-800" x-text="editMode ? 'Edit Divisi' : 'Tambah Divisi Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form :action="editMode ? '/admin/divisi/' + form.id : '{{ route('admin.divisi.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Divisi</label>
                    <input type="text" name="nama_divisi" x-model="form.nama_divisi" required placeholder="Contoh: Tim Umum / Sosial / Nerwils" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bpsBlue focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" x-model="form.deskripsi" rows="3" placeholder="Deskripsi ringkas tugas/ruang lingkup divisi..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bpsBlue focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" x-model="form.status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bpsBlue focus:outline-none">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-bpsOrange text-white hover:bg-orange-600 transition shadow-md shadow-orange-500/20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection