@extends('layouts.app')

@section('content')

<div
    class="space-y-6"
    x-data="{
        showModal: false,
        editMode: false,
        form: {
            id: '',
            nama_divisi: '',
            deskripsi: '',
            status: 'Aktif'
        }
    }"
>

    <!-- HEADER HALAMAN -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">

        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800">
                Manajemen Divisi
            </h1>

            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelola daftar divisi penempatan pemagang di BPS Kabupaten Tulungagung.
            </p>
        </div>


        <button
            type="button"
            @click="
                editMode = false;
                form = {
                    id: '',
                    nama_divisi: '',
                    deskripsi: '',
                    status: 'Aktif'
                };
                showModal = true
            "
            class="px-5 py-2.5 rounded-xl text-xs font-bold bg-bpsOrange hover:bg-orange-600 text-white transition shadow-md shadow-orange-500/20 flex items-center gap-2"
        >

            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                ></path>
            </svg>

            <span>Tambah Divisi</span>

        </button>

    </div>


    <!-- NOTIFIKASI SUKSES CRUD -->
    @if(session('success'))

        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center gap-2">

            <svg
                class="w-5 h-5 text-emerald-500 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
            </svg>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    <!-- NOTIFIKASI ERROR -->
    @if($errors->any())

        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-xs">

            <ul class="list-disc pl-4 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- STATISTIK RINGKAS -->
    <div class="grid sm:grid-cols-3 gap-5">

        <!-- Total Divisi -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-1">

            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">
                Total Divisi
            </div>

            <div class="text-2xl font-black text-slate-800">
                {{ $totalDivisi }}
            </div>

        </div>


        <!-- Aktif -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-1">

            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">
                Aktif
            </div>

            <div class="text-2xl font-black text-emerald-600">
                {{ $aktifDivisi }}
            </div>

        </div>


        <!-- Nonaktif -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm space-y-1">

            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">
                Nonaktif
            </div>

            <div class="text-2xl font-black text-slate-400">
                {{ $nonaktifDivisi }}
            </div>

        </div>

    </div>


    <!-- PENCARIAN & FILTER -->
    <form
        method="GET"
        action="{{ route('admin.divisi.index') }}"
        class="flex flex-col md:flex-row md:items-center justify-between gap-4"
    >

        <!-- Input Pencarian -->
        <div class="relative w-full md:w-1/3">

            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">

                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    />
                </svg>

            </span>


            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau deskripsi divisi..."
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

                <option
                    value="Aktif"
                    {{ request('status') == 'Aktif' ? 'selected' : '' }}
                >
                    Aktif
                </option>

                <option
                    value="Nonaktif"
                    {{ request('status') == 'Nonaktif' ? 'selected' : '' }}
                >
                    Nonaktif
                </option>

            </select>


            @if(request('search') || request('status'))

                <a
                    href="{{ route('admin.divisi.index') }}"
                    class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition shadow-sm"
                >
                    Reset
                </a>

            @endif

        </div>

    </form>


    <!-- GRID KARTU DIVISI -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($divisis as $divisi)

            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-bpsSky/40 transition duration-300 relative group">

                <!-- Informasi Divisi -->
                <div class="space-y-3">

                    <div class="flex justify-between items-start gap-3">

                        <h3 class="text-base font-bold text-slate-900">
                            {{ $divisi->nama_divisi }}
                        </h3>


                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-bold shrink-0
                            {{ $divisi->status == 'Aktif'
                                ? 'bg-emerald-50 text-emerald-600 border border-emerald-200'
                                : 'bg-slate-100 text-slate-500 border border-slate-200'
                            }}"
                        >
                            {{ $divisi->status }}
                        </span>

                    </div>


                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $divisi->deskripsi ?? 'Belum ada deskripsi untuk divisi ini.' }}
                    </p>

                </div>


                <!-- Tombol CRUD -->
                <div class="flex items-center justify-end gap-2 pt-5 mt-4 border-t border-slate-100">

                    <!-- Edit -->
                    <button
                        type="button"
                        @click="
                            editMode = true;
                            form = {
                                id: '{{ $divisi->id }}',
                                nama_divisi: @js($divisi->nama_divisi),
                                deskripsi: @js($divisi->deskripsi ?? ''),
                                status: @js($divisi->status)
                            };
                            showModal = true
                        "
                        class="px-3 py-1.5 bg-slate-100 hover:bg-bpsBlue hover:text-white rounded-xl transition text-xs font-bold text-slate-600 flex items-center gap-1.5"
                        title="Edit"
                    >

                        <svg
                            class="w-3.5 h-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            ></path>
                        </svg>

                        <span>Edit</span>

                    </button>


                    <!-- Hapus -->
                    <form
                        action="{{ route('admin.divisi.destroy', $divisi->id) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus divisi ini?')"
                    >

                        @csrf
                        @method('DELETE')


                        <button
                            type="submit"
                            class="px-3 py-1.5 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl transition text-xs font-bold text-red-500 flex items-center gap-1.5"
                            title="Hapus"
                        >

                            <svg
                                class="w-3.5 h-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                ></path>
                            </svg>

                            <span>Hapus</span>

                        </button>

                    </form>

                </div>

            </div>


        @empty

            <div class="col-span-full bg-white border border-slate-200 rounded-3xl p-12 text-center text-slate-400 text-xs">

                @if(request('search') || request('status'))

                    Tidak ada divisi yang sesuai dengan pencarian atau filter.

                @else

                    Belum ada data divisi yang ditambahkan.
                    Silakan klik tombol "Tambah Divisi" di atas.

                @endif

            </div>

        @endforelse

    </div>


    <!-- MODAL TAMBAH / EDIT DIVISI -->
    <div
        x-show="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
        style="display: none;"
    >

        <div
            @click.outside="showModal = false"
            class="bg-white rounded-3xl p-6 w-full max-w-md space-y-5 shadow-2xl border border-slate-100"
        >

            <!-- Header Modal -->
            <div class="flex justify-between items-center">

                <h3
                    class="text-base font-extrabold text-slate-800"
                    x-text="editMode ? 'Edit Divisi' : 'Tambah Divisi Baru'"
                ></h3>


                <button
                    type="button"
                    @click="showModal = false"
                    class="text-slate-400 hover:text-slate-600"
                >
                    ✕
                </button>

            </div>


            <!-- Form Modal -->
            <form
                :action="editMode
                    ? '/admin/divisi/' + form.id
                    : '{{ route('admin.divisi.store') }}'"
                method="POST"
                class="space-y-4"
            >

                @csrf


                <template x-if="editMode">

                    <input
                        type="hidden"
                        name="_method"
                        value="PUT"
                    >

                </template>


                <!-- Nama Divisi -->
                <div>

                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Nama Divisi
                    </label>

                    <input
                        type="text"
                        name="nama_divisi"
                        x-model="form.nama_divisi"
                        required
                        placeholder="Contoh: Tim Umum / Sosial / Nerwils"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bpsBlue focus:outline-none"
                    >

                </div>


                <!-- Deskripsi -->
                <div>

                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        x-model="form.deskripsi"
                        rows="3"
                        placeholder="Deskripsi ringkas tugas/ruang lingkup divisi..."
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bpsBlue focus:outline-none"
                    ></textarea>

                </div>


                <!-- Status -->
                <div>

                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Status
                    </label>

                    <select
                        name="status"
                        x-model="form.status"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bpsBlue focus:outline-none bg-white">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <!-- Button -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-bpsOrange text-white hover:bg-orange-600 transition shadow-md shadow-orange-500/20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection