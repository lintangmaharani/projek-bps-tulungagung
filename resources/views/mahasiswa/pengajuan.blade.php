@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Form Pengajuan PKL / Magang</h1>
        <p class="text-xs text-slate-500 mt-1">
            {{ $pengajuan ? 'Informasi batas pengisian formulir pendaftaran.' : 'Lengkapi seluruh informasi pendaftaran Anda melalui 4 tahapan di bawah ini.' }}
        </p>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold space-y-1 shadow-sm">
            <p class="font-bold">⚠️ Ada kesalahan pengisian form:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center space-x-2 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($pengajuan)
        <!-- BANNER PEMBERITAHUAN JIKA SUDAH PERNAH DAFTAR -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 text-center space-y-5">
            <div class="w-16 h-16 bg-blue-50 text-bpsBlue rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <div class="space-y-1.5 max-w-md mx-auto">
                <h2 class="text-lg font-bold text-slate-800">Formulir Pengajuan Sudah Dikirim</h2>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda sudah berhasil mengirimkan berkas pengajuan PKL/Magang. Anda tidak perlu mengunggah ulang formulir pendaftaran.
                </p>
            </div>

            <div class="pt-2">
                <a href="{{ route('pengajuan.status') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-bpsBlue hover:bg-bpsDark text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-900/20 transition duration-200">
                    <span>Lihat Status Pengajuan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    @else
        <!-- Multi-Step Form Container -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
            
            <!-- Step Progress Indicator -->
            <div class="bg-slate-50 p-6 border-b border-slate-200 grid grid-cols-4 gap-2 text-center">
                <div id="step-indicator-1" class="step-badge text-bpsBlue font-bold border-b-4 border-bpsBlue pb-2 text-xs transition-all">
                    1. Data Diri
                </div>
                <div id="step-indicator-2" class="step-badge text-slate-400 font-semibold border-b-4 border-transparent pb-2 text-xs transition-all">
                    2. Pendidikan
                </div>
                <div id="step-indicator-3" class="step-badge text-slate-400 font-semibold border-b-4 border-transparent pb-2 text-xs transition-all">
                    3. Berkas & Periode
                </div>
                <div id="step-indicator-4" class="step-badge text-slate-400 font-semibold border-b-4 border-transparent pb-2 text-xs transition-all">
                    4. Konfirmasi
                </div>
            </div>

            <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <!-- TAHAP 1: DATA DIRI -->
                <div id="step-1" class="step-content space-y-4">
                    <div class="border-b pb-3">
                        <h3 class="text-sm font-bold text-slate-800">Tahap 1 — Data Diri Pemohon</h3>
                        <p class="text-xs text-slate-400">Pastikan data identitas diri Anda sesuai dengan dokumen resmi.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                            <input type="text" id="in_nama" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name) }}" required placeholder="Masukkan nama lengkap sesuai KTP/KTM" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Email (Sesuai login)</label>
                            <input type="email" id="in_email" name="email" value="{{ Auth::user()->email }}" readonly class="w-full px-3.5 py-2.5 text-xs border rounded-xl bg-slate-100 text-slate-500 font-medium border-slate-200 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tempat Lahir</label>
                            <input type="text" id="in_tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Contoh : Tulungagung" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Lahir</label>
                            <input type="date" id="in_tgl_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat Domisili Lengkap</label>
                        <textarea id="in_alamat" name="alamat" rows="2" required placeholder="Alamat lengkap tempat tinggal" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">{{ old('alamat') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" id="in_nohp" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                    </div>
                </div>

                <!-- TAHAP 2: RIWAYAT PENDIDIKAN -->
                <div id="step-2" class="step-content space-y-4 hidden">
                    <div class="border-b pb-3">
                        <h3 class="text-sm font-bold text-slate-800">Tahap 2 — Riwayat Pendidikan</h3>
                        <p class="text-xs text-slate-400">Pilih kualifikasi instansi pendidikan Anda saat ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori Pendidikan</label>
                        <select id="in_tipe" name="tipe_pendidikan" onchange="togglePendidikan()" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300 bg-white">
                            <option value="Perguruan Tinggi" {{ old('tipe_pendidikan') == 'Perguruan Tinggi' ? 'selected' : '' }}>Mahasiswa (Perguruan Tinggi)</option>
                            <option value="SMK/SMA" {{ old('tipe_pendidikan') == 'SMK/SMA' ? 'selected' : '' }}>Siswa SMK / Sederajat</option>
                        </select>
                    </div>

                    <div>
                        <label id="lbl_instansi" class="block text-xs font-semibold text-slate-600 mb-1">Nama Universitas / Perguruan Tinggi</label>
                        <input type="text" id="in_instansi" name="nama_instansi" value="{{ old('nama_instansi') }}" required placeholder="Contoh : Universitas Brawijaya" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                    </div>

                    <div id="box_fakultas">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Fakultas</label>
                        <input type="text" id="in_fakultas" name="fakultas" value="{{ old('fakultas') }}" placeholder="Contoh : Fakultas Ilmu Komputer" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label id="lbl_prodi" class="block text-xs font-semibold text-slate-600 mb-1">Program Studi</label>
                            <input type="text" id="in_prodi" name="prodi_jurusan" value="{{ old('prodi_jurusan') }}" required placeholder="Contoh : Teknik Informatika" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                        </div>
                        <div>
                            <label id="lbl_tingkat" class="block text-xs font-semibold text-slate-600 mb-1">Semester Aktif</label>
                            <input type="text" id="in_tingkat" name="tingkat" value="{{ old('tingkat') }}" required placeholder="Contoh : Semester 6 / Kelas 11" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- TAHAP 3: PERIODE, KUOTA & BERKAS -->
                <div id="step-3" class="step-content space-y-4 hidden">
                    <div class="border-b pb-3">
                        <h3 class="text-sm font-bold text-slate-800">Tahap 3 — Kuota, Periode & Upload Berkas</h3>
                        <p class="text-xs text-slate-400">Pilih kuota periode magang yang dibuka dan unggah dokumen persyaratan.</p>
                    </div>

                    <!-- DROPDOWN PILIHAN KUOTA PERIODE -->
                    <div class="bg-blue-50/60 p-4 rounded-2xl border border-blue-100 space-y-1.5">
                        <label class="block text-xs font-bold text-bpsBlue">
                            Pilih Kuota / Periode <span class="text-rose-500">*</span>
                        </label>
                        <select id="in_kuota" name="kuota_id" required onchange="updateTanggalBounds()" class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300 bg-white">
                            <option value="" data-mulai="" data-selesai="">-- Pilih Periode Kuota yang Tersedia --</option>
                            @forelse($kuotas as $k)
                                @php $sisa = $k->jumlah_kuota - $k->terisi; @endphp
                                <option value="{{ $k->id }}" 
                                        data-nama="{{ $k->nama_periode }}" 
                                        data-mulai="{{ $k->tgl_mulai }}" 
                                        data-selesai="{{ $k->tgl_selesai }}" 
                                        {{ old('kuota_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_periode }} ({{ \Carbon\Carbon::parse($k->tgl_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($k->tgl_selesai)->format('d/m/Y') }}) — Sisa: {{ $sisa }} Slot
                                </option>
                            @empty
                                <option value="" disabled>Mohon maaf, tidak ada kuota magang yang tersedia saat ini.</option>
                            @endforelse
                        </select>
                        <p class="text-[11px] text-slate-500">Tanggal pengajuan PKL Anda akan otomatis dikunci berdasarkan periode yang dipilih.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
                            <input type="date" id="in_tgl_mulai" name="tgl_mulai" value="{{ old('tgl_mulai') }}" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Selesai</label>
                            <input type="date" id="in_tgl_selesai" name="tgl_selesai" value="{{ old('tgl_selesai') }}" required class="w-full px-3.5 py-2.5 text-xs border rounded-xl focus:ring-2 focus:ring-bpsBlue border-slate-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Upload CV Terbaru (PDF, DOC, ZIP, Gambar — Max 5MB)</label>
                        <input type="file" id="in_cv" name="file_cv" accept=".pdf,.doc,.docx,.zip,.rar,.png,.jpg,.jpeg" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-bpsBlue file:text-white hover:file:bg-bpsDark cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Upload Surat Pengantar Resmi (PDF, DOC, ZIP, Gambar — Max 5MB)</label>
                        <input type="file" id="in_surat" name="file_surat_pengantar" accept=".pdf,.doc,.docx,.zip,.rar,.png,.jpg,.jpeg" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-bpsBlue file:text-white hover:file:bg-bpsDark cursor-pointer">
                    </div>
                </div>

                <!-- TAHAP 4: KONFIRMASI -->
                <div id="step-4" class="step-content space-y-6 hidden">
                    <div class="border-b pb-3">
                        <h3 class="text-sm font-bold text-slate-800">Tahap 4 — Konfirmasi & Review Pendaftaran</h3>
                        <p class="text-xs text-slate-400">Silakan periksa kembali seluruh data Anda dari Tahap 1 sampai 3 sebelum dikirim.</p>
                    </div>

                    <!-- RINGKASAN DATA -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                            <h4 class="font-bold text-bpsBlue border-b border-slate-200 pb-1">1. Data Diri</h4>
                            <p class="text-slate-500">Nama: <span id="rev_nama" class="font-bold text-slate-800 block"></span></p>
                            <p class="text-slate-500">Email: <span id="rev_email" class="font-semibold text-slate-800 block"></span></p>
                            <p class="text-slate-500">TTL: <span id="rev_ttl" class="font-medium text-slate-800 block"></span></p>
                            <p class="text-slate-500">Alamat: <span id="rev_alamat" class="font-medium text-slate-800 block"></span></p>
                            <p class="text-slate-500">No. HP: <span id="rev_nohp" class="font-medium text-slate-800 block"></span></p>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                            <h4 class="font-bold text-bpsBlue border-b border-slate-200 pb-1">2. Pendidikan</h4>
                            <p class="text-slate-500">Kategori: <span id="rev_tipe" class="font-bold text-slate-800 block uppercase"></span></p>
                            <p class="text-slate-500">Instansi: <span id="rev_instansi" class="font-semibold text-slate-800 block"></span></p>
                            <p id="box_rev_fakultas" class="text-slate-500">Fakultas: <span id="rev_fakultas" class="font-medium text-slate-800 block"></span></p>
                            <p class="text-slate-500">Prodi/Jurusan: <span id="rev_prodi" class="font-medium text-slate-800 block"></span></p>
                            <p class="text-slate-500">Tingkat: <span id="rev_tingkat" class="font-medium text-slate-800 block"></span></p>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                            <h4 class="font-bold text-bpsBlue border-b border-slate-200 pb-1">3. Kuota & Berkas</h4>
                            <p class="text-slate-500">Periode Kuota: <span id="rev_kuota" class="font-bold text-bpsBlue block"></span></p>
                            <p class="text-slate-500">Tgl Mulai: <span id="rev_tgl_mulai" class="font-semibold text-slate-800 block"></span></p>
                            <p class="text-slate-500">Tgl Selesai: <span id="rev_tgl_selesai" class="font-semibold text-slate-800 block"></span></p>
                            <p class="text-slate-500">File CV: <span id="rev_cv_name" class="font-medium text-emerald-600 block truncate"></span></p>
                            <p class="text-slate-500">File Surat: <span id="rev_surat_name" class="font-medium text-emerald-600 block truncate"></span></p>
                        </div>
                    </div>

                    <!-- Checkbox Pernyataan -->
                    <div class="p-4 bg-orange-50 border border-orange-200 rounded-2xl flex items-start space-x-3">
                        <input type="checkbox" id="setuju" required class="mt-0.5 rounded text-bpsOrange focus:ring-bpsOrange border-slate-300 cursor-pointer">
                        <label for="setuju" class="text-xs text-slate-700 leading-relaxed cursor-pointer select-none">
                            Saya menyatakan bahwa seluruh data yang diisi dari Tahap 1 hingga Tahap 3 adalah benar, sah, dan dapat dipertanggungjawabkan untuk pengajuan PKL/Magang di BPS Tulungagung.
                        </label>
                    </div>
                </div>

                <!-- NAVIGATION BUTTONS -->
                <div class="mt-8 flex justify-between items-center border-t pt-6">
                    <button type="button" id="btn-back" onclick="changeStep(-1)" class="px-6 py-2.5 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-300 transition hidden">
                        &larr; Kembali
                    </button>
                    
                    <div class="ml-auto">
                        <button type="button" id="btn-next" onclick="changeStep(1)" class="px-6 py-2.5 bg-bpsBlue text-white text-xs font-bold rounded-xl hover:bg-bpsDark shadow-lg shadow-blue-900/20 transition">
                            Lanjut &rarr;
                        </button>
                        
                        <button type="submit" id="btn-submit" class="px-6 py-2.5 bg-bpsOrange text-white text-xs font-bold rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition hidden">
                            Kirim Pendaftaran
                        </button>
                    </div>
                </div>

            </form>
        </div>
    @endif
</div>

<!-- SCRIPT JAVASCRIPT NAVIGASI & KONTROL TANGGAL -->
<script>
    let currentStep = 1;

    function changeStep(step) {
        if (step > 0) {
            const currentInputs = document.querySelectorAll(`#step-${currentStep} input[required], #step-${currentStep} select[required], #step-${currentStep} textarea[required]`);
            for (let input of currentInputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return;
                }
            }
        }

        document.getElementById(`step-${currentStep}`).classList.add('hidden');
        document.getElementById(`step-indicator-${currentStep}`).classList.remove('text-bpsBlue', 'font-bold', 'border-bpsBlue');
        document.getElementById(`step-indicator-${currentStep}`).classList.add('text-slate-400', 'border-transparent');

        currentStep += step;

        document.getElementById(`step-${currentStep}`).classList.remove('hidden');
        document.getElementById(`step-indicator-${currentStep}`).classList.remove('text-slate-400', 'border-transparent');
        document.getElementById(`step-indicator-${currentStep}`).classList.add('text-bpsBlue', 'font-bold', 'border-bpsBlue');

        document.getElementById('btn-back').classList.toggle('hidden', currentStep === 1);
        document.getElementById('btn-next').classList.toggle('hidden', currentStep === 4);
        document.getElementById('btn-submit').classList.toggle('hidden', currentStep !== 4);

        if (currentStep === 4) {
            document.getElementById('rev_nama').innerText = document.getElementById('in_nama').value || '-';
            document.getElementById('rev_email').innerText = document.getElementById('in_email').value || '-';
            document.getElementById('rev_ttl').innerText = (document.getElementById('in_tempat_lahir').value || '-') + ', ' + (document.getElementById('in_tgl_lahir').value || '-');
            document.getElementById('rev_alamat').innerText = document.getElementById('in_alamat').value || '-';
            document.getElementById('rev_nohp').innerText = document.getElementById('in_nohp').value || '-';

            const tipe = document.getElementById('in_tipe').value;
            document.getElementById('rev_tipe').innerText = tipe;
            document.getElementById('rev_instansi').innerText = document.getElementById('in_instansi').value || '-';
            document.getElementById('rev_fakultas').innerText = document.getElementById('in_fakultas').value || '-';
            document.getElementById('box_rev_fakultas').style.display = (tipe === 'Perguruan Tinggi') ? 'block' : 'none';
            document.getElementById('rev_prodi').innerText = document.getElementById('in_prodi').value || '-';
            document.getElementById('rev_tingkat').innerText = document.getElementById('in_tingkat').value || '-';

            const kuotaSelect = document.getElementById('in_kuota');
            const selectedOption = kuotaSelect.options[kuotaSelect.selectedIndex];
            const selectedKuotaText = selectedOption ? selectedOption.getAttribute('data-nama') : '-';
            document.getElementById('rev_kuota').innerText = selectedKuotaText || '-';

            document.getElementById('rev_tgl_mulai').innerText = document.getElementById('in_tgl_mulai').value || '-';
            document.getElementById('rev_tgl_selesai').innerText = document.getElementById('in_tgl_selesai').value || '-';
            
            const cvInput = document.getElementById('in_cv');
            document.getElementById('rev_cv_name').innerText = cvInput.files[0] ? cvInput.files[0].name : 'Belum diunggah';

            const suratInput = document.getElementById('in_surat');
            document.getElementById('rev_surat_name').innerText = suratInput.files[0] ? suratInput.files[0].name : 'Belum diunggah';
        }
    }

    function togglePendidikan() {
        const tipe = document.getElementById('in_tipe').value;
        const isMahasiswa = (tipe === 'Perguruan Tinggi');
        document.getElementById('lbl_instansi').innerText = isMahasiswa ? 'Nama Universitas / Perguruan Tinggi' : 'Nama Sekolah (SMK/Sederajat)';
        document.getElementById('lbl_prodi').innerText = isMahasiswa ? 'Program Studi' : 'Jurusan';
        document.getElementById('lbl_tingkat').innerText = isMahasiswa ? 'Semester Aktif' : 'Kelas';
        document.getElementById('box_fakultas').style.display = isMahasiswa ? 'block' : 'none';
    }

    function updateTanggalBounds() {
        const selectKuota = document.getElementById('in_kuota');
        const selectedOption = selectKuota.options[selectKuota.selectedIndex];

        const tglMulaiPeriode = selectedOption ? selectedOption.getAttribute('data-mulai') : null;
        const tglSelesaiPeriode = selectedOption ? selectedOption.getAttribute('data-selesai') : null;

        const inputMulai = document.getElementById('in_tgl_mulai');
        const inputSelesai = document.getElementById('in_tgl_selesai');

        if (tglMulaiPeriode && tglSelesaiPeriode) {
            inputMulai.min = tglMulaiPeriode;
            inputMulai.max = tglSelesaiPeriode;

            inputSelesai.min = tglMulaiPeriode;
            inputSelesai.max = tglSelesaiPeriode;

            if (inputMulai.value && (inputMulai.value < tglMulaiPeriode || inputMulai.value > tglSelesaiPeriode)) {
                inputMulai.value = '';
            }
            if (inputSelesai.value && (inputSelesai.value < tglMulaiPeriode || inputSelesai.value > tglSelesaiPeriode)) {
                inputSelesai.value = '';
            }
        } else {
            inputMulai.removeAttribute('min');
            inputMulai.removeAttribute('max');
            inputSelesai.removeAttribute('min');
            inputSelesai.removeAttribute('max');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateTanggalBounds();
        togglePendidikan();
    });
</script>
@endsection