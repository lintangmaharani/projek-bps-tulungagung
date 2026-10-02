@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <a href="{{ route('jurnal.index') }}"
           class="text-xs font-bold text-bpsBlue hover:underline flex items-center mb-1">
            &larr; Kembali ke Jurnal Saya
        </a>

        <h1 class="text-2xl font-black text-slate-800 tracking-tight">
            Edit Jurnal Kegiatan
        </h1>

        <p class="text-xs text-slate-500 mt-0.5">
            Perbarui detail aktivitas harian magang atau PKL Anda.
        </p>
    </div>


    {{-- FORM --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">

        <form
            action="{{ route('jurnal.update', $jurnal->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4"
        >

            @csrf
            @method('PUT')


            {{-- ERROR --}}
            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- TANGGAL & JAM --}}
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
                        value="{{ old('tanggal', \Carbon\Carbon::parse($jurnal->tanggal)->format('Y-m-d')) }}"
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
                        value="{{ old('jam_mulai', $jurnal->jam_mulai ? substr($jurnal->jam_mulai, 0, 5) : '') }}"
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
                        value="{{ old('jam_selesai', $jurnal->jam_selesai ? substr($jurnal->jam_selesai, 0, 5) : '') }}"
                        min="07:30"
                        max="16:00"
                        required
                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                    >
                </div>

            </div>


            {{-- JUDUL --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Judul / Ringkasan Kegiatan
                </label>

                <input
                    type="text"
                    name="kegiatan"
                    value="{{ old('kegiatan', $jurnal->kegiatan) }}"
                    required
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
                    rows="4"
                    required
                    class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-bpsBlue"
                >{{ old('deskripsi', $jurnal->deskripsi) }}</textarea>
            </div>


            {{-- DIVISI --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Divisi Tempat Bertugas
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
                            {{ old('divisi_id', $jurnal->divisi_id) == $divisi->id ? 'selected' : '' }}
                        >
                            {{ $divisi->nama_divisi }}
                        </option>

                    @endforeach

                </select>
            </div>


            {{-- DOKUMENTASI --}}
            <div>

                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Ganti Foto / Dokumentasi
                    <span class="font-normal text-slate-400">
                        (Opsional)
                    </span>
                </label>


                @if($jurnal->file_dokumentasi)

                    <div class="mb-2 text-xs text-slate-500">

                        File saat ini:

                        <a
                            href="{{ asset('storage/' . $jurnal->file_dokumentasi) }}"
                            target="_blank"
                            class="text-bpsBlue font-bold hover:underline"
                        >
                            Lihat File
                        </a>

                    </div>

                @endif


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


            {{-- BUTTON --}}
            <div class="flex justify-end space-x-2 pt-2">

                <a
                    href="{{ route('jurnal.index') }}"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="px-6 py-2.5 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl shadow-md transition"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

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


        // Sabtu atau Minggu
        if (hari === 0 || hari === 6) {

            alert('Jurnal kegiatan hanya dapat diisi untuk hari Senin sampai Jumat.');

            tanggalInput.value = '';
            jamMulaiInput.value = '';
            jamSelesaiInput.value = '';

            return;
        }


        const batasMulai = '07:30';

        // Senin - Kamis
        let batasSelesai = '16:00';

        // Jumat
        if (hari === 5) {
            batasSelesai = '16:30';
        }


        jamMulaiInput.min = batasMulai;
        jamMulaiInput.max = batasSelesai;

        jamSelesaiInput.max = batasSelesai;


        // Jam selesai mengikuti jam mulai
        if (jamMulaiInput.value) {
            jamSelesaiInput.min = jamMulaiInput.value;
        } else {
            jamSelesaiInput.min = batasMulai;
        }


        // Cek nilai jam mulai lama
        if (
            jamMulaiInput.value &&
            (
                jamMulaiInput.value < batasMulai ||
                jamMulaiInput.value > batasSelesai
            )
        ) {
            jamMulaiInput.value = '';
        }


        // Cek nilai jam selesai lama
        if (
            jamSelesaiInput.value &&
            (
                jamSelesaiInput.value > batasSelesai ||
                (
                    jamMulaiInput.value &&
                    jamSelesaiInput.value <= jamMulaiInput.value
                )
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