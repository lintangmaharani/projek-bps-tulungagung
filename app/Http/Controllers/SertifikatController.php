<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
{
    // --- SISI ADMIN ---
    
    // Tampilkan Halaman Kelola Sertifikat (Dengan Fitur Pencarian & Filter)
    public function adminIndex(Request $request)
    {
        // Ambil pengajuan yang statusnya disetujui / diterima
        $query = Pengajuan::whereIn('status', ['Diterima', 'Disetujui']);

        // Fitur Pencarian (Nama, Email, Instansi)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nama_instansi', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Kategori Pendidikan (Mahasiswa Magang, PKL, SMK)
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe_pendidikan', $request->tipe);
        }

        // Ubah dari get() menjadi paginate() agar rapi dan mendukung links()
        $sertifikats = $query->latest()->paginate(10)->withQueryString();

        // PERBAIKAN: Menggunakan $sertifikats agar sinkron dengan file Blade
        return view('admin.sertifikat.index', compact('sertifikats'));
    }

    // Proses Upload / Update File Sertifikat
    public function uploadSertifikat(Request $request, $id)
    {
        $request->validate([
            'file_sertifikat' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', // Mendukung PDF & Gambar (Max 5MB)
        ], [
            'file_sertifikat.required' => 'File sertifikat wajib dipilih.',
            'file_sertifikat.mimes' => 'Format sertifikat harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat.max' => 'Ukuran file sertifikat maksimal 5MB.',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Hapus file lama jika ada
        if ($pengajuan->file_sertifikat && Storage::disk('public')->exists($pengajuan->file_sertifikat)) {
            Storage::disk('public')->delete($pengajuan->file_sertifikat);
        }

        // Upload file baru
        $path = $request->file('file_sertifikat')->store('sertifikat', 'public');
        $pengajuan->update([
            'file_sertifikat' => $path,
        ]);

        return back()->with('success', 'File sertifikat berhasil diunggah!');
    }

    // Tambahan Aksi Hapus (Delete) Sertifikat untuk melengkapi CRUD
    public function deleteSertifikat($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->file_sertifikat && Storage::disk('public')->exists($pengajuan->file_sertifikat)) {
            Storage::disk('public')->delete($pengajuan->file_sertifikat);
        }

        $pengajuan->update([
            'file_sertifikat' => null,
        ]);

        return back()->with('success', 'Berkas sertifikat berhasil dihapus!');
    }

    // --- SISI MAHASISWA ---

    // Tampilkan Halaman Sertifikat Mahasiswa
    public function mahasiswaIndex()
    {
        $pengajuan = Pengajuan::where('user_id', Auth::id())->latest()->first();

        return view('mahasiswa.sertifikat', compact('pengajuan'));
    }
}