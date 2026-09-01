<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal PKL & Magang BPS Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bpsBlue: '#00428C',
                        bpsOrange: '#F38118',
                        bpsDark: '#002C61',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 flex h-screen overflow-hidden font-sans">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-bpsBlue text-white flex flex-col justify-between shrink-0 shadow-2xl z-20">
        <div>
            <!-- Header Sidebar -->
            <div class="p-5 flex items-center space-x-3 border-b border-white/10 bg-bpsDark/40">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1 shadow shrink-0">
                    <img src="{{ asset('image/bps-logo.png') }}" alt="Logo BPS" class="w-full h-full object-contain" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/2/28/National_Statistical_Agency_of_Indonesia.svg'">
                </div>
                <div>
                    <h1 class="text-sm font-extrabold tracking-wide text-white leading-tight">BPS TULUNGAGUNG</h1>
                    <p class="text-[10px] text-bpsOrange font-semibold tracking-wider uppercase">Portal PKL & Magang</p>
                </div>
            </div>

            <!-- Menu Navigasi Berdasarkan Role -->
            <nav class="p-4 space-y-2">
                @if((Auth::user()->role ?? '') === 'admin')
                    <!-- MENU ADMIN -->
                    <!-- 1. Dashboard Admin -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard Admin</span>
                    </a>

                    <!-- 2. Kuota & Periode Magang -->
                    <a href="{{ route('admin.kuota.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('admin.kuota.*') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Kuota & Periode Magang</span>
                    </a>

                    <!-- 3. Kelola Pengajuan -->
                    <a href="{{ route('admin.pengajuan.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('admin.pengajuan.*') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Kelola Pengajuan</span>
                    </a>

                    <!-- 4. Peserta Aktif -->
                    <a href="{{ route('admin.peserta.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('admin.peserta.*') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Peserta Aktif</span>
                    </a>

                    <!-- 5. Kelola Sertifikat -->
                    <a href="{{ route('admin.sertifikat.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('admin.sertifikat.*') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        <span>Kelola Sertifikat</span>
                    </a>

                    <!-- Manajemen Divisi -->
                    <a href="{{ route('admin.divisi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('admin.divisi.*') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>Manajemen Divisi</span>
                    </a>

                    <!-- 6. Kelola CMS Website -->
                    <a href="{{ route('admin.cms.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('admin.cms.*') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Kelola CMS Website</span>
                    </a>
                @else
                    <!-- MENU MAHASISWA -->
                    <!-- 1. Dashboard -->
                    <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- 2. Informasi Kuota Magang -->
                    <a href="{{ route('kuota.info') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('kuota.info') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Informasi Kuota</span>
                    </a>

                    <!-- 3. Form Pengajuan -->
                    <a href="{{ route('pengajuan.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('pengajuan.index') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Form Pengajuan</span>
                    </a>

                    <!-- 4. Status Pengajuan -->
                    <a href="{{ route('pengajuan.status') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('pengajuan.status') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Status Pengajuan</span>
                    </a>

                    <!-- 5. Jurnal Kegiatan -->
                    <a href="{{ route('jurnal.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('jurnal.*') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>Jurnal Kegiatan</span>
                    </a>

                    <!-- 6. Sertifikat Saya (MENU BARU MAHASISWA) -->
                    <a href="{{ route('mahasiswa.sertifikat') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition duration-200 text-xs font-medium {{ request()->routeIs('mahasiswa.sertifikat') ? 'bg-bpsOrange font-bold text-white shadow-lg shadow-orange-500/30' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        <span>Sertifikat Saya</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Profil & Logout -->
        <div class="p-4 border-t border-white/10 bg-bpsDark/40">
            <div class="flex items-center justify-between">
                <div class="truncate pr-2">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-blue-200 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-red-300 hover:text-white hover:bg-red-500 rounded-xl transition shadow" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- CONTENT AREA -->
    <main class="flex-1 overflow-y-auto p-8">
        @yield('content')
    </main>

</body>
</html>