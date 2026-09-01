@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header sambutan -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Dashboard Admin BPS Kab. Tulungagung</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Selamat datang, <span class="font-bold text-slate-700">{{ Auth::user()->name }}</span>. Berikut adalah ringkasan data pendaftaran magang saat ini.</p>
        </div>
        <div class="text-xs bg-sky-50 text-sky-700 font-semibold px-4 py-2 rounded-xl border border-sky-100 shrink-0">
            📅 {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- 1. STAT CARDS (4 Kolom Statistik) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pendaftar</span>
                <h3 class="text-2xl font-black text-slate-900">{{ $totalPendaftar ?? 0 }}</h3>
                <span class="text-[10px] text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded-md">Semua Periode</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl font-bold">👥</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Menunggu Verifikasi</span>
                <h3 class="text-2xl font-black text-amber-600">{{ $totalMenunggu ?? 0 }}</h3>
                <span class="text-[10px] text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-md">Perlu Tindakan</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl font-bold">⏳</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Magang Aktif</span>
                <h3 class="text-2xl font-black text-emerald-600">{{ $totalAktif ?? 0 }}</h3>
                <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md">Sedang Berjalan</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl font-bold">✨</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Selesai Magang</span>
                <h3 class="text-2xl font-black text-purple-600">{{ $totalSelesai ?? 0 }}</h3>
                <span class="text-[10px] text-purple-600 font-semibold bg-purple-50 px-2 py-0.5 rounded-md">Sertifikat Terbit</span>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl font-bold">🎓</div>
        </div>
    </div>

    <!-- 2. SECTION DIAGRAM / GRAFIK STATISTIK -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Batang / Donut (Perbandingan Mahasiswa vs Siswa) -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm lg:col-span-1 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-base text-slate-900">Komposisi Pendaftar</h3>
                <p class="text-xs text-slate-500 mt-0.5">Berdasarkan jenjang pendidikan (Mahasiswa & Siswa).</p>
            </div>
            <div class="my-6 relative w-full flex justify-center" style="height: 220px;">
                <canvas id="pendaftarChart"></canvas>
            </div>
            <div class="text-center text-xs text-slate-400">
                Data real-time dari database BPS Tulungagung
            </div>
        </div>

        <!-- 3. TABEL PENGAJUAN TERBARU -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden lg:col-span-2 flex flex-col">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Pengajuan Pendaftar Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar peserta yang baru saja mengirimkan formulir permohonan.</p>
                </div>
                <a href="{{ route('admin.pengajuan.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Kelola Semua →</a>
            </div>

            <div class="overflow-x-auto flex-grow">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3 px-6 font-semibold">Nama Pendaftar</th>
                            <th class="py-3 px-6 font-semibold">Instansi</th>
                            <th class="py-3 px-6 font-semibold">Jurusan</th>
                            <th class="py-3 px-6 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentPengajuans ?? [] as $item)
                            @php /** @var \App\Models\Pengajuan $item */ @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-6 font-bold text-slate-900">{{ $item->nama_lengkap }}</td>
                                <td class="py-3 px-6">{{ $item->nama_instansi }}</td>
                                <td class="py-3 px-6">{{ $item->prodi_jurusan }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase 
                                        {{ $item->status == 'diproses' ? 'bg-amber-50 text-amber-600 border border-amber-200' : '' }}
                                        {{ $item->status == 'diterima' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : '' }}
                                        {{ $item->status == 'ditolak' ? 'bg-rose-50 text-rose-600 border border-rose-200' : '' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-slate-400 text-xs">
                                    Belum ada data pendaftar masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- SCRIPT CHART.JS UNTUK MENAMPILKAN DIAGRAM -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pendaftarChart').getContext('2d');
    const pendaftarChart = new Chart(ctx, {
        type: 'doughnut', // Bisa diganti 'bar' jika ingin diagram batang
        data: {
            labels: ['Mahasiswa (Perguruan Tinggi)', 'Siswa (SMK/SMA)'],
            datasets: [{
                data: [{{ $jumlahMahasiswa ?? 0 }}, {{ $jumlahSiswa ?? 0 }}],
                backgroundColor: [
                    'rgba(37, 99, 235, 0.8)',  // Warna Biru untuk Mahasiswa
                    'rgba(16, 185, 129, 0.8)'   // Warna Hijau untuk Siswa
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
</script>
@endsection