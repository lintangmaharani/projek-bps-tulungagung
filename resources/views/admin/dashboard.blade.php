@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header Sambutan & Lonceng Notifikasi -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Dashboard Admin BPS Kab. Tulungagung</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Selamat datang, <span class="font-bold text-slate-700">{{ Auth::user()->name }}</span>. Berikut adalah ringkasan data pendaftaran magang saat ini[cite: 3].</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <!-- Tanggal Hari Ini -->
            <div class="hidden md:flex text-xs bg-sky-50 text-sky-700 font-semibold px-4 py-2.5 rounded-2xl border border-sky-100 items-center gap-2">
                <span>📅 {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>

            <!-- FITUR LONCENG NOTIFIKASI -->
            <div class="relative" x-data="{ openNotif: false }">
                <button @click="openNotif = !openNotif" class="relative p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-2xl text-slate-600 transition focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    
                    @if(isset($recentPengajuans) && $recentPengajuans->where('status', 'diproses')->count() > 0)
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                    @endif
                </button>

                <!-- Dropdown Box Notifikasi -->
                <div x-show="openNotif" @click.away="openNotif = false" class="absolute right-0 mt-3 w-80 bg-white border border-slate-200 rounded-3xl shadow-xl overflow-hidden z-50 p-4 space-y-3" style="display: none;">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                        <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Notifikasi Pengajuan Baru</h3>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">
                            {{ isset($recentPengajuans) ? $recentPengajuans->where('status', 'diproses')->count() : 0 }} Pending
                        </span>
                    </div>

                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @forelse($recentPengajuans->where('status', 'diproses') ?? [] as $notif)
                            <a href="{{ route('admin.pengajuan.index') }}" class="block p-3 bg-slate-50 hover:bg-blue-50/50 border border-slate-100 rounded-2xl transition">
                                <p class="text-xs font-bold text-slate-800">{{ $notif->nama_lengkap }}</p>
                                <p class="text-[11px] text-slate-500">Mengajukan dari <span class="font-semibold text-slate-700">{{ $notif->nama_instansi }}</span></p>
                                <span class="text-[9px] text-slate-400 mt-1 block">{{ $notif->created_at ? $notif->created_at->diffForHumans() : '' }}</span>
                            </a>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-4">Tidak ada pengajuan baru yang menunggu.</p>
                        @endforelse
                    </div>

                    <div class="pt-1 text-center border-t border-slate-100">
                        <a href="{{ route('admin.pengajuan.index') }}" class="text-[11px] font-bold text-blue-600 hover:underline">Kelola Semua Pengajuan &rarr;</a>
                    </div>
                </div>
            </div>
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
        <!-- Grafik Donut (Komposisi 3 Kategori) -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm lg:col-span-1 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-base text-slate-900">Komposisi Pendaftar</h3>
                <p class="text-xs text-slate-500 mt-0.5">Berdasarkan kategori (Magang, PKL, Siswa).</p>
            </div>
            <div class="my-6 relative w-full flex justify-center" style="height: 220px;">
                <canvas id="pendaftarChart"></canvas>
            </div>
            <div class="text-center text-xs text-slate-400">
                Data real-time dari database BPS Tulungagung[cite: 3]
            </div>
        </div>

        <!-- 3. TABEL PENGAJUAN TERBARU -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden lg:col-span-2 flex flex-col">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Pengajuan Pendaftar Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar peserta yang baru saja mengirimkan formulir permohonan[cite: 3].</p>
                </div>
                <a href="{{ route('admin.pengajuan.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Kelola Semua &rarr;</a>
            </div>

            <div class="overflow-x-auto flex-grow">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3 px-6 font-semibold">Nama Pendaftar</th>
                            <th class="py-3 px-6 font-semibold">Instansi</th>
                            <th class="py-3 px-6 font-semibold">Jurusan</th>
                            <th class="py-3 px-6 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentPengajuans ?? [] as $item)
                            @php /** @var \App\Models\Pengajuan $item */ @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-6 font-bold text-slate-900">{{ $item->nama_lengkap }}</td>
                                <td class="py-3 px-6">{{ $item->nama_instansi }}</td>
                                <td class="py-3 px-6">{{ $item->prodi_jurusan }}</td>
                                <td class="py-3 px-6 text-center">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase 
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

<!-- SCRIPT CHART.JS UNTUK MENAMPILKAN DIAGRAM 3 KATEGORI -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pendaftarChart').getContext('2d');
    const pendaftarChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Mahasiswa Magang', 'Mahasiswa PKL', 'Siswa (SMK/SMA)'],
            datasets: [{
                data: [
                    {{ $jumlahMahasiswaMagang ?? 0 }}, 
                    {{ $jumlahMahasiswaPKL ?? 0 }}, 
                    {{ $jumlahSiswa ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(37, 99, 235, 0.8)',   // Biru (Mahasiswa Magang)
                    'rgba(147, 51, 234, 0.8)',  // Ungu (Mahasiswa PKL)
                    'rgba(16, 185, 129, 0.8)'   // Hijau (Siswa SMK/SMA)
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