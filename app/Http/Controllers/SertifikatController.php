<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
{
    // --- SISI ADMIN ---
    
    // Tampilkan Halaman Kelola Sertifikat
    public function adminIndex()
    {
        // Ambil pengajuan yang statusnya disetujui / diterima
        $pengajuans = Pengajuan::whereIn('status', ['Diterima', 'Disetujui'])
            ->latest()
            ->get();

        return view('admin.sertifikat.index', compact('pengajuans'));
    }

    // Process Upload / Update File Sertifikat PDF
    public function uploadSertifikat(Request $request, $id)
    {
        $request->validate([
            'file_sertifikat' => 'required|mimes:pdf|max:5120', // Max 5MB PDF
        ], [
            'file_sertifikat.required' => 'File sertifikat wajib dipilih.',
            'file_sertifikat.mimes' => 'Format sertifikat harus berupa PDF.',
            'file_sertifikat.max' => 'Ukuran file sertifikat maksimal 5MB.',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Hapus file lama jika ada
        if ($pengajuan->file_sertifikat && Storage::disk('public')->exists($pengajuan->file_sertifikat)) {
            Storage::disk('public')->delete($pengajuan->file_sertifikat);
        }

        // Upload file baru tanpa merubah kolom status
        $path = $request->file('file_sertifikat')->store('sertifikat', 'public');
        $pengajuan->update([
            'file_sertifikat' => $path,
        ]);

        return back()->with('success', 'File sertifikat PDF berhasil diunggah!');
    }

    // --- SISI MAHASISWA ---

    // Tampilkan Halaman Sertifikat Mahasiswa
    public function mahasiswaIndex()
    {
        $pengajuan = Pengajuan::where('user_id', Auth::id())->latest()->first();

        return view('mahasiswa.sertifikat', compact('pengajuan'));
    }
}