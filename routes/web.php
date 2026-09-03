<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\Admin\PesertaAktifController;
use App\Http\Controllers\Admin\KuotaController;
use App\Http\Controllers\Mahasiswa\KuotaMahasiswaController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\DivisiController;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest)
|--------------------------------------------------------------------------
*/
// Halaman Beranda Publik (Landing Page)
Route::get('/', [LandingController::class, 'index']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PANEL ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin (Terhubung ke Controller)
        Route::get('/dashboard', [AdminPengajuanController::class, 'dashboard'])->name('dashboard');

        // Kelola Pengajuan Masuk
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::put('/pengajuan/{id}/status', [AdminPengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');
        Route::get('/pengajuan/{id}/cetak-surat', [AdminPengajuanController::class, 'cetakSurat'])->name('pengajuan.cetakSurat');

        // Kelola Peserta Aktif
        Route::get('/peserta-aktif', [PesertaAktifController::class, 'index'])->name('peserta.index');
        Route::get('/peserta-aktif/{pengajuan}', [PesertaAktifController::class, 'show'])->name('peserta.show');

        // Kelola Kuota Periode Magang
        Route::resource('kuota', KuotaController::class)->except(['create', 'show', 'edit']);

        // Kelola Sertifikat PKL (ADMIN)
        Route::get('/sertifikat', [SertifikatController::class, 'adminIndex'])->name('sertifikat.index');
        Route::post('/sertifikat/{id}/upload', [SertifikatController::class, 'uploadSertifikat'])->name('sertifikat.upload');

        // Kelola CMS
        Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
        Route::post('/cms/update', [CmsController::class, 'update'])->name('cms.update');

        // Kelola Manajemen Divisi
        Route::resource('divisi', DivisiController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | PANEL MAHASISWA
    |--------------------------------------------------------------------------
    */
    // Dashboard Mahasiswa
    Route::get('/mahasiswa/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('mahasiswa.dashboard');
    })->name('mahasiswa.dashboard');

    // Pengajuan PKL / Magang
    Route::get('/mahasiswa/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/mahasiswa/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/mahasiswa/status', [PengajuanController::class, 'status'])->name('pengajuan.status');

    // Jurnal Harian Mahasiswa 
    Route::get('/mahasiswa/jurnal', [JurnalController::class, 'index'])->name('mahasiswa.jurnal.index');
    
    // Atau route aslinya tetap dipertahankan:
    Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
    Route::post('/jurnal', [JurnalController::class, 'store'])->name('jurnal.store');
    Route::delete('/jurnal/{jurnal}', [JurnalController::class, 'destroy'])->name('jurnal.destroy');

    // Informasi Kuota
    Route::get('/info-kuota', [KuotaMahasiswaController::class, 'index'])->name('kuota.info');

    // Menu Sertifikat Saya (MAHASISWA)
    Route::get('/sertifikat-saya', [SertifikatController::class, 'mahasiswaIndex'])->name('mahasiswa.sertifikat');
});