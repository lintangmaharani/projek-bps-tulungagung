@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">
                Jurnal Kegiatan Harian
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Isi dan catat aktivitas PKL / Magang harian Anda di BPS Tulungagung.
            </p>
        </div>
    </div>


    {{-- NOTIFIKASI SUKSES --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>

            <span>{{ session('success') }}</span>
        </div>
    @endif


    {{-- NOTIFIKASI ERROR --}}
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- FORM INPUT JURNAL --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">

        <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">
            Tambah Jurnal Kegiatan
        </h2>

        <form
            action="{{ route('jurnal.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4"
        >
            @csrf


            {{-- TANGGAL DAN JAM --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Tanggal Kegiatan
                    </label>

                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                        min="{{ now()->subDays(2)->format('Y-m-d') }}"
                        max="{{ now()->format('Y-m-d') }}"
                        required
                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                    >
                </div>


                {{-- Jam Mulai --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        id="jam_mulai"
                        name="jam_mulai"
                        value="{{ old('jam_mulai') }}"
                        min="07:30"
                        max="16:00"
                        required
                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                    >
                </div>


                {{-- Jam Selesai --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        id="jam_selesai"
                        name="jam_selesai"
                        value="{{ old('jam_selesai') }}"
                        min="07:30"
                        max="16:00"
                        required
                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                    >
                </div>

            </div>


            {{-- JUDUL KEGIATAN --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Judul / Ringkasan Kegiatan
                </label>

                <input
                    type="text"
                    name="kegiatan"
                    value="{{ old('kegiatan') }}"
                    required
                    placeholder="Contoh: Input Data Sensus Ekonomi 2026"
                    class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                >
            </div>


            {{-- DESKRIPSI --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Rincian / Deskripsi Kegiatan
                </label>

                <textarea
                    name="deskripsi"
                    rows="3"
                    required
                    placeholder="Jelaskan detail pekerjaan atau aktivitas yang dilakukan..."
                    class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                >{{ old('deskripsi') }}</textarea>
            </div>


            {{-- DIVISI --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Pilih Divisi Tempat Bertugas
                </label>

                <select
                    name="divisi_id"
                    required
                    class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                >
                    <option value="">-- Pilih Divisi --</option>

                    @foreach($divisis as $divisi)
                        <option
                            value="{{ $divisi->id }}"
                            {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}
                        >
                            {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- DOKUMENTASI --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Foto / Dokumentasi
                    <span class="font-normal text-slate-400">
                        (Opsional: JPG, PNG, PDF - Max 3MB)
                    </span>
                </label>

                <input
                    type="file"
                    name="file_dokumentasi"
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full text-xs text-slate-500
                        file:mr-4
                        file:py-2
                        file:px-4
                        file:rounded-xl
                        file:border-0
                        file:text-xs
                        file:font-semibold
                        file:bg-bpsBlue
                        file:text-white
                        hover:file:bg-bpsDark
                        cursor-pointer"
                >
            </div>


            {{-- BUTTON SIMPAN --}}
            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    class="px-6 py-2.5 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl shadow-md transition"
                >
                    Simpan Jurnal
                </button>
            </div>

        </form>
    </div>


    {{-- RIWAYAT JURNAL --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- HEADER + SEARCH --}}
        <div class="p-6 border-b border-slate-100 space-y-4">

            <div class="flex justify-between items-center">
                <h2 class="text-sm font-bold text-slate-800">
                    Riwayat Jurnal Kegiatan
                </h2>

                <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-semibold">
                    Total: <b>{{ $jurnals->total() }}</b> Kegiatan
                </span>
            </div>


            <form
                method="GET"
                action="{{ route('jurnal.index') }}"
                class="flex flex-col md:flex-row md:items-center justify-between gap-4"
            >

                {{-- PENCARIAN --}}
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
                        placeholder="Cari kegiatan, deskripsi, divisi..."
                        class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-500 shadow-sm"
                    >
                </div>


                {{-- FILTER STATUS --}}
                <div class="flex items-center gap-2 w-full md:w-auto">

                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="w-full md:w-48 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-500 shadow-sm"
                    >
                        <option value="">Semua Status</option>

                        <option
                            value="menunggu"
                            {{ request('status') == 'menunggu' ? 'selected' : '' }}
                        >
                            Menunggu
                        </option>

                        <option
                            value="diterima"
                            {{ request('status') == 'diterima' ? 'selected' : '' }}
                        >
                            Diterima
                        </option>
                    </select>


                    @if(request('search') || request('status'))
                        <a
                            href="{{ route('jurnal.index') }}"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition shadow-sm"
                        >
                            Reset
                        </a>
                    @endif

                </div>

            </form>

        </div>


        {{-- TABEL --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Tanggal & Waktu</th>
                        <th class="py-3 px-4">Divisi</th>
                        <th class="py-3 px-4">Kegiatan & Deskripsi</th>
                        <th class="py-3 px-4">Dokumentasi</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Feedback Admin</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-slate-100 text-sm">

                    @forelse($jurnals as $jurnal)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- TANGGAL --}}
                            <td class="py-4 px-4 align-top">
                                <div class="font-bold text-slate-800 text-xs">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}
                                </div>

                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $jurnal->jam_mulai ? substr($jurnal->jam_mulai, 0, 5) : '--:--' }}
                                    -
                                    {{ $jurnal->jam_selesai ? substr($jurnal->jam_selesai, 0, 5) : '--:--' }}
                                    WIB
                                </div>
                            </td>


                            {{-- DIVISI --}}
                            <td class="py-4 px-4 align-top">
                                <span class="inline-block px-2.5 py-1 bg-blue-50 text-bpsBlue font-semibold rounded-lg text-xs">
                                    {{ $jurnal->divisi->nama_divisi ?? 'Tanpa Divisi' }}
                                </span>
                            </td>


                            {{-- KEGIATAN --}}
                            <td class="py-4 px-4 align-top">
                                <div class="font-bold text-slate-800 text-xs mb-0.5">
                                    {{ $jurnal->kegiatan }}
                                </div>

                                <div class="text-xs text-slate-500 line-clamp-2">
                                    {{ $jurnal->deskripsi }}
                                </div>
                            </td>


                            {{-- DOKUMENTASI --}}
                            <td class="py-4 px-4 align-top">
                                @if($jurnal->file_dokumentasi)
                                    <a
                                        href="{{ asset('storage/' . $jurnal->file_dokumentasi) }}"
                                        target="_blank"
                                        class="text-xs font-semibold text-bpsBlue hover:underline"
                                    >
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">
                                        Tidak ada
                                    </span>
                                @endif
                            </td>


                            {{-- STATUS --}}
                            <td class="py-4 px-4 align-top text-center">

                                @if($jurnal->status_verifikasi == 'diterima')

                                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">
                                        Diterima
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        Menunggu
                                    </span>

                                @endif

                            </td>


                            {{-- FEEDBACK --}}
                            <td class="py-4 px-4 align-top max-w-xs">

                                @if($jurnal->feedback_admin)

                                    <div class="text-xs text-slate-600 leading-relaxed">
                                        {{ $jurnal->feedback_admin }}
                                    </div>

                                @else

                                    <span class="text-xs text-slate-400 italic">
                                        Belum ada feedback
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="py-4 px-4 align-top text-center">

                                <div class="flex items-center justify-center space-x-2">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('jurnal.edit', $jurnal->id) }}"
                                        class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition"
                                        title="Edit"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            />
                                        </svg>
                                    </a>


                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route('jurnal.destroy', $jurnal->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus jurnal ini?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition"
                                            title="Hapus"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="py-8 text-center text-xs text-slate-400">
                                Belum ada riwayat jurnal kegiatan yang dicatat.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        <div class="p-4 border-t border-slate-100">
            {{ $jurnals->links() }}
        </div>

    </div>

</div>


<script>
    const tanggalInput = document.getElementById('tanggal');
    const jamMulaiInput = document.getElementById('jam_mulai');
    const jamSelesaiInput = document.getElementById('jam_selesai');


    function aturJamKerja() {

        if (!tanggalInput.value) {
            return;
        }

        const tanggal = new Date(tanggalInput.value + 'T00:00:00');
        const hari = tanggal.getDay();


        // 0 = Minggu, 6 = Sabtu
        if (hari === 0 || hari === 6) {

            alert('Jurnal kegiatan hanya dapat diisi untuk hari Senin sampai Jumat.');

            tanggalInput.value = '';
            jamMulaiInput.value = '';
            jamSelesaiInput.value = '';

            return;
        }


        const batasMulai = '07:30';

        // Senin-Kamis maksimal 16:00
        let batasSelesai = '16:00';


        // Jumat maksimal 16:30
        if (hari === 5) {
            batasSelesai = '16:30';
        }


        jamMulaiInput.min = batasMulai;
        jamMulaiInput.max = batasSelesai;

        jamSelesaiInput.min = batasMulai;
        jamSelesaiInput.max = batasSelesai;


        // Hapus jam mulai apabila tidak sesuai rentang
        if (
            jamMulaiInput.value &&
            (
                jamMulaiInput.value < batasMulai ||
                jamMulaiInput.value > batasSelesai
            )
        ) {
            jamMulaiInput.value = '';
        }


        // Hapus jam selesai apabila tidak sesuai rentang
        if (
            jamSelesaiInput.value &&
            (
                jamSelesaiInput.value < batasMulai ||
                jamSelesaiInput.value > batasSelesai
            )
        ) {
            jamSelesaiInput.value = '';
        }
    }


    tanggalInput.addEventListener('change', function () {

        jamMulaiInput.value = '';
        jamSelesaiInput.value = '';

        aturJamKerja();

    });


    jamMulaiInput.addEventListener('change', function () {

        if (!jamMulaiInput.value) {
            return;
        }

        jamSelesaiInput.min = jamMulaiInput.value;

        if (
            jamSelesaiInput.value &&
            jamSelesaiInput.value <= jamMulaiInput.value
        ) {
            jamSelesaiInput.value = '';
        }

    });


    aturJamKerja();
</script>

@endsection