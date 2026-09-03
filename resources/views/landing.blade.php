@php
    $cms = \App\Models\CmsContent::all()->pluck('value', 'key');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal PKL & Magang BPS Tulungagung</title>
    
    <!-- Favicon Logo BPS -->
    <link rel="icon" type="image/png" href="{{ asset('image/bps-logo.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        bpsNavy: '#0B2545',
                        bpsBlue: '#00428C',
                        bpsSky: '#0066CC',
                        bpsLightSky: '#E6F0FA',
                        bpsOrange: '#F38118',
                        bpsOrangeHover: '#E0700B',
                        bpsBg: '#F8FAFC',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bpsBg font-sans text-slate-800 antialiased selection:bg-bpsBlue selection:text-white">

    <!-- 1. NAVBAR -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/80 px-6 lg:px-12 py-3.5 transition-all">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="p-1.5 rounded-xl bg-slate-50 border border-slate-200/80 group-hover:scale-105 transition duration-300 shadow-sm">
                    <img src="{{ asset('image/bps-logo.png') }}" alt="Logo BPS" class="h-9 w-auto object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-base tracking-tight text-slate-900 leading-none group-hover:text-bpsBlue transition">PKL & Magang</span>
                    <span class="text-[11px] text-slate-500 font-medium tracking-wide mt-0.5">BPS Tulungagung</span>
                </div>
            </a>

            <!-- Menu Navbar -->
            <div class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="{{ url('/') }}" class="hover:text-bpsBlue transition-colors">Beranda</a>
                <a href="#tentang" class="hover:text-bpsBlue transition-colors">Tentang</a>
                <a href="#persyaratan" class="hover:text-bpsBlue transition-colors">Persyaratan</a>
                <a href="#alur" class="hover:text-bpsBlue transition-colors">Alur</a>
                <a href="#divisi" class="hover:text-bpsBlue transition-colors">Divisi</a>
                <a href="#faq" class="hover:text-bpsBlue transition-colors">FAQ</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-bpsBlue text-white hover:bg-bpsNavy transition duration-300 shadow-md shadow-bpsBlue/20 flex items-center gap-2">
                            <span>Dashboard Admin</span>
                            <span>→</span>
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-bpsBlue text-white hover:bg-bpsNavy transition duration-300 shadow-md shadow-bpsBlue/20 flex items-center gap-2">
                            <span>Dashboard Saya</span>
                            <span>→</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 border border-slate-300 hover:bg-slate-100 hover:border-slate-400 hover:text-bpsBlue transition duration-300 shadow-sm">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-bpsOrange text-white hover:bg-bpsOrangeHover transition duration-300 shadow-md shadow-bpsOrange/20 hover:-translate-y-0.5">
                        Daftar Magang
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION (Dinamis CMS) -->
    <section class="relative bg-gradient-to-br from-bpsNavy via-bpsBlue to-slate-900 text-white py-20 px-6 lg:px-12 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-bpsSky/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-bpsOrange/20 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto grid lg:grid-cols-12 gap-12 items-center relative z-10">
            <!-- Kolom Kiri: Teks Hero -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-md text-xs text-sky-200 font-medium">
                    ✨ Portal Resmi PKL & Magang BPS Kab. Tulungagung
                </div>

                <h1 class="text-3xl sm:text-5xl font-extrabold leading-tight tracking-tight">
                    {{ $cms['hero_title'] ?? 'Program PKL & Magang' }}
                    <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-amber-300">BPS Kab. Tulungagung</span>
                </h1>

                <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl">
                    {{ $cms['hero_desc'] ?? '' }}
                </p>

                <!-- Info Jam Kerja -->
                <div class="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-4 py-2.5 rounded-2xl text-xs text-slate-300 backdrop-blur-md">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Jam Operasional Kantor:</span>
                    </div>
                    <span class="font-bold text-white">Senin - Jumat (08:00 - 15:00 WIB)</span>
                </div>

                <div class="flex flex-wrap gap-4 pt-2">
                    @auth
                        <a href="{{ route('pengajuan.index') }}" class="px-6 py-3.5 rounded-xl text-xs font-bold bg-bpsOrange hover:bg-bpsOrangeHover text-white transition duration-300 shadow-lg shadow-bpsOrange/30">
                            Buat Pengajuan PKL →
                        </a>
                    @else
                        <a href="{{ $cms['hero_btn_url'] ?: route('register') }}" class="px-6 py-3.5 rounded-xl text-xs font-bold bg-bpsOrange hover:bg-bpsOrangeHover text-white transition duration-300 shadow-lg shadow-bpsOrange/30">
                            {{ $cms['hero_btn_text'] ?? 'Daftar Sekarang →' }}
                        </a>
                    @endauth
                    <a href="#tentang" class="px-6 py-3.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 border border-white/20 text-white backdrop-blur-md transition duration-300">
                        Pelajari Informasi
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: KUOTA BERJALAN WIDGET -->
            <div class="lg:col-span-5">
                <div class="bg-slate-900/60 backdrop-blur-xl border border-white/15 p-6 rounded-3xl shadow-2xl space-y-4">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-200">KUOTA BERJALAN</span>
                        <span class="text-[10px] bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2.5 py-0.5 rounded-full font-semibold">Real-time</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($kuotas as $k)
                            @php
                                $terisi = $k->terisi; 
                                $sisa = $k->sisa;     
                                $total = $k->jumlah_kuota;
                                $persentase = $total > 0 ? min(round(($terisi / $total) * 100), 100) : 0;
                            @endphp

                            <div class="bg-white/5 border border-white/10 p-4 rounded-2xl space-y-2.5 hover:bg-white/10 transition">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-sm text-white">{{ $k->nama_periode }}</span>
                                    <span class="text-xs font-semibold {{ $sisa > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $sisa > 0 ? $sisa . ' tersisa' : 'Penuh' }}
                                    </span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden border border-white/5">
                                    <div class="bg-gradient-to-r from-bpsSky to-emerald-400 h-full rounded-full transition-all duration-500" style="width: {{ $persentase }}%;"></div>
                                </div>
                                <div class="flex justify-between items-center text-[11px] text-slate-300">
                                    <span>{{ $terisi }}/{{ $total }} peserta</span>
                                    <span>Ditutup {{ \Carbon\Carbon::parse($k->tgl_selesai)->translatedFormat('d M') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 text-xs">
                                Belum ada periode magang yang dibuka saat ini.
                            </div>
                        @endforelse
                    </div>

                    <div class="pt-1">
                        @auth
                            <a href="{{ route('kuota.info') }}" class="block text-center w-full py-2.5 bg-bpsSky/30 hover:bg-bpsSky/50 text-white rounded-xl text-xs font-bold border border-bpsSky/40 transition">
                                Cek Rincian Kuota Lengkap →
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block text-center w-full py-2.5 bg-bpsSky/30 hover:bg-bpsSky/50 text-white rounded-xl text-xs font-bold border border-bpsSky/40 transition">
                                Login untuk Informasi Kuota Detail →
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. TENTANG MAGANG (Dinamis CMS) -->
    <section id="tentang" class="py-20 px-6 lg:px-12 max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-slate-200 group">
                    <img src="{{ asset('image/gedung-bps.png') }}" 
                         alt="Gedung BPS Kabupaten Tulungagung" 
                         class="w-full h-80 object-cover object-center transform group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-bpsNavy/90 via-bpsNavy/30 to-transparent flex flex-col justify-end p-6 text-white">
                        <span class="text-[11px] bg-white/20 border border-white/30 backdrop-blur-md px-3 py-1 rounded-full w-max mb-1 font-semibold tracking-wide">
                            Kantor BPS Kabupaten Tulungagung
                        </span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-5">
                <span class="text-xs font-bold tracking-wider text-bpsBlue uppercase bg-bpsLightSky px-3 py-1 rounded-lg border border-blue-100">Tentang Magang</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ $cms['about_title'] ?? 'Program PKL & Magang' }}
                </h2>
                <p class="text-slate-600 text-sm leading-relaxed">
                    {{ $cms['about_desc_1'] ?? '' }}
                </p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    {{ $cms['about_desc_2'] ?? '' }}
                </p>
                
                <div class="space-y-2 pt-1">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Yang akan didapatkan:</h4>
                    <div class="grid sm:grid-cols-2 gap-3 text-xs font-semibold text-slate-700">
                        @for ($i = 1; $i <= 4; $i++)
                            @if(!empty($cms['about_benefit_' . $i]))
                                <div class="flex items-center gap-2 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                    <span class="text-emerald-500 font-bold">✓</span> {{ $cms['about_benefit_' . $i] }}
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. PERSYARATAN BERKAS (Statis) -->
    <section id="persyaratan" class="py-24 bg-bpsNavy text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-bpsSky/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <span class="text-xs font-bold tracking-wider text-bpsOrange uppercase bg-white/10 px-3 py-1 rounded-lg border border-white/15">Persyaratan Berkas</span>
                <h2 class="text-3xl font-extrabold tracking-tight">Syarat Dokumen Pendaftaran</h2>
                <p class="text-slate-300 text-sm">Berikut adalah dokumen persyaratan lengkap yang harus disiapkan berdasarkan jenjang pendidikan Anda.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-stretch">
                <!-- Siswa -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-8 shadow-2xl flex flex-col justify-between">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-white">Siswa (SMA/SMK)</h3>
                                <p class="text-xs text-slate-400">Praktik Kerja Lapangan (PKL)</p>
                            </div>
                        </div>
                        <ul class="space-y-4 text-slate-300 text-sm">
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Surat Pengantar Resmi Permohonan Magang dari pihak Sekolah.</li>
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Fotokopi Kartu Pelajar (KTS) atau identitas diri.</li>
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Pas foto formal berwarna ukuran standar.</li>
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Mengisi formulir data diri lengkap pada portal.</li>
                        </ul>
                    </div>
                </div>

                <!-- Mahasiswa -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-8 shadow-2xl flex flex-col justify-between">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-white">Mahasiswa (D3 / D4 / S1)</h3>
                                <p class="text-xs text-slate-400">Program Magang / Riset / PKL</p>
                            </div>
                        </div>
                        <ul class="space-y-4 text-slate-300 text-sm">
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Surat Pengantar Resmi dari Dekan/Fakultas/Kampus.</li>
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Proposal kegiatan PKL atau magang.</li>
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Transkrip nilai akademik terakhir yang sah.</li>
                            <li class="flex items-start gap-3"><span class="text-emerald-400 mt-0.5 font-bold">✔</span>Kartu Tanda Mahasiswa (KTM).</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4.1. ALUR PENDAFTARAN (Statis) -->
    <section id="alur" class="py-20 px-6 lg:px-12 max-w-7xl mx-auto">
        <div class="text-center max-w-xl mx-auto mb-12 space-y-3">
            <span class="text-[11px] font-bold tracking-wider text-bpsBlue uppercase bg-bpsLightSky px-3 py-1 rounded-lg border border-blue-100">Tahapan Pendaftaran</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Alur PKL & Magang</h2>
            <p class="text-slate-500 text-xs sm:text-sm">Ikuti langkah-langkah berikut untuk mendaftarkan diri sebagai peserta magang di BPS Kabupaten Tulungagung.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
            <div class="space-y-6 flex flex-col justify-between">
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-[11px] font-black text-bpsBlue tracking-wider block mb-2">01</span>
                    <h3 class="text-sm font-bold text-slate-900 text-center mb-1">Buat Akun</h3>
                    <p class="text-[11px] text-slate-600 text-center">Registrasi menggunakan email aktif melalui halaman Daftar Akun.</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-[11px] font-black text-emerald-600 tracking-wider block mb-2">02</span>
                    <h3 class="text-sm font-bold text-slate-900 text-center mb-1">Lengkapi Form</h3>
                    <p class="text-[11px] text-slate-600 text-center">Isi data diri dan unggah CV serta Surat Pengantar.</p>
                </div>
            </div>
            <div class="space-y-6 flex flex-col justify-between">
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-[11px] font-black text-bpsOrange tracking-wider block mb-2">03</span>
                    <h3 class="text-sm font-bold text-slate-900 text-center mb-1">Tunggu Verifikasi</h3>
                    <p class="text-[11px] text-slate-600 text-center">Tim BPS mereview berkas pengajuan Anda.</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-[11px] font-black text-bpsBlue tracking-wider block mb-2">04</span>
                    <h3 class="text-sm font-bold text-slate-900 text-center mb-1">Dinyatakan Diterima</h3>
                    <p class="text-[11px] text-slate-600 text-center">Cek status diterima melalui dashboard Anda.</p>
                </div>
            </div>
            <div class="space-y-6 flex flex-col justify-between">
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-[11px] font-black text-emerald-600 tracking-wider block mb-2">05</span>
                    <h3 class="text-sm font-bold text-slate-900 text-center mb-1">Konfirmasi Magang</h3>
                    <p class="text-[11px] text-slate-600 text-center">Klik tombol konfirmasi mulai magang.</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-[11px] font-black text-bpsOrange tracking-wider block mb-2">06</span>
                    <h3 class="text-sm font-bold text-slate-900 text-center mb-1">Pemagang Aktif</h3>
                    <p class="text-[11px] text-slate-600 text-center">Mulai absensi harian dan pengisian logbook.</p>
                </div>
            </div>
        </div>
    </section>

<!-- 5. DIVISI / BIDANG PENEMPATAN BPS (Dinamis dari Database) -->
    <section id="divisi" class="py-24 px-6 lg:px-12 max-w-7xl mx-auto bg-slate-50/50 rounded-3xl my-12">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <span class="text-xs font-bold tracking-wider text-bpsBlue uppercase bg-bpsLightSky px-3 py-1 rounded-lg border border-blue-100">Fokus Bidang</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Divisi Penempatan Magang</h2>
            <p class="text-slate-500 text-sm">Peserta akan ditempatkan di unit kerja yang sesuai dengan ruang lingkup dan kompetensi jurusannya.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                // Mengambil data divisi yang statusnya Aktif dari database
                $divisiList = \App\Models\Divisi::where('status', 'Aktif')->latest()->get();
            @endphp

            @forelse($divisiList as $divisi)
            <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col justify-between hover:border-bpsSky/50 hover:shadow-md transition duration-300">
                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">{{ $divisi->nama_divisi }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $divisi->deskripsi ?? 'Belum ada deskripsi.' }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-slate-400 text-xs">
                Belum ada divisi aktif yang ditampilkan saat ini.
            </div>
            @endforelse
        </div>
    </section>

    <!-- 6. FAQ (Dinamis CMS) -->
    <section id="faq" class="py-24 bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-12 space-y-4">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $cms['faq_title'] ?? 'Pertanyaan Umum (FAQ)' }}</h2>
                <p class="text-slate-500 text-sm">{{ $cms['faq_subtitle'] ?? '' }}</p>
            </div>

            <div class="space-y-4">
                @for ($i = 1; $i <= 5; $i++)
                    @if(!empty($cms['faq_q_' . $i]))
                        <div class="border border-slate-200 rounded-2xl overflow-hidden hover:border-bpsSky/50 transition-colors">
                            <button class="faq-btn w-full text-left px-6 py-5 flex justify-between items-center bg-slate-50 hover:bg-slate-100/50 transition">
                                <span class="font-bold text-slate-800 text-sm">{{ $cms['faq_q_' . $i] }}</span>
                                <span class="transform transition-transform duration-300 text-slate-400 text-xs font-bold">▲</span>
                            </button>
                            <div class="faq-content hidden px-6 py-5 bg-white text-sm text-slate-600 border-t border-slate-100">
                                {{ $cms['faq_a_' . $i] }}
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
    </section>

    <!-- 7. FOOTER & KONTAK -->
    <footer id="kontak" class="bg-bpsNavy text-white pt-20 pb-10 border-t-4 border-bpsOrange relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16 relative z-10">
            <div class="lg:col-span-5 space-y-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/bps-logo.png') }}" alt="Logo BPS" class="h-11 w-auto bg-white p-1.5 rounded-xl shadow-md">
                    <div>
                        <h3 class="font-extrabold text-base tracking-wide leading-tight">PKL & Magang</h3>
                        <p class="text-xs font-medium text-sky-300">BPS Kab. Tulungagung</p>
                    </div>
                </div>
                <p class="text-slate-300 text-xs leading-relaxed max-w-sm">
                    Portal terpadu pendaftaran PKL bagi pelajar dan mahasiswa untuk belajar langsung pengelolaan data statistik resmi negara.
                </p>
            </div>

            <div class="lg:col-span-3 space-y-4">
                <h4 class="font-bold text-xs uppercase tracking-widest text-bpsOrange">Navigasi Utama</h4>
                <ul class="space-y-2.5 text-xs text-slate-300">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-all">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-white transition-all">Tentang Program</a></li>
                    <li><a href="#persyaratan" class="hover:text-white transition-all">Persyaratan</a></li>
                    <li><a href="#alur" class="hover:text-white transition-all">Alur Pendaftaran</a></li>
                    <li><a href="#divisi" class="hover:text-white transition-all">Divisi</a></li>
                    <li><a href="#faq" class="hover:text-white transition-all">FAQ</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4 space-y-4">
                <h4 class="font-bold text-xs uppercase tracking-widest text-bpsOrange">Kontak Instansi</h4>
                <ul class="space-y-3 text-xs text-slate-300 leading-relaxed">
                    <li class="flex items-start gap-2.5">
                        <span class="text-sm shrink-0 mt-0.5">📍</span> 
                        <span>Jl. Ir. Soekarno Hatta, Balerejo, Kutoanyar,Tulungagung, Jawa Timur 66215</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-sm shrink-0">📞</span> 
                        <span>(0355) 7629854</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-sm shrink-0">✉️</span> 
                        <span>bps3504@bps.go.id</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-sm shrink-0">🌐</span> 
                        <a href="https://tulungagungkab.bps.go.id" target="_blank" class="hover:text-bpsOrange transition-colors underline underline-offset-2">tulungagungkab.bps.go.id</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 pt-8 border-t border-white/10 text-center text-[11px] text-slate-400 relative z-10">
            &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Tulungagung. All rights reserved.
        </div>
    </footer>

    <!-- 8. SCRIPTS INTERAKTIF -->
    <script>
        document.querySelectorAll('.faq-btn').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const arrow = button.querySelector('span:last-child');
                content.classList.toggle('hidden');
                arrow.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });
    </script>
</body>
</html>