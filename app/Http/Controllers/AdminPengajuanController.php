<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPengajuanController extends Controller
{
    // Tambahkan method dashboard ini untuk mengatur halaman utama admin
    public function dashboard()
    {
        // 1. Statistik Utama (Stat Cards)
        $totalPendaftar = Pengajuan::count();
        $totalMenunggu  = Pengajuan::where('status', 'diproses')->count();
        $totalAktif     = Pengajuan::where('status', 'diterima')->count();
        $totalSelesai   = Pengajuan::where('status', 'selesai')->count();

        // 2. Data untuk Diagram (Berdasarkan tipe_pendidikan atau tingkat)
        // Asumsi di migrasi Anda ada kolom 'tipe_pendidikan' atau 'tingkat'
        $jumlahMahasiswa = Pengajuan::where('tipe_pendidikan', 'LIKE', '%Perguruan Tinggi%')->orWhere('tipe_pendidikan', 'LIKE', '%Mahasiswa%')->count();
        $jumlahSiswa     = Pengajuan::where('tipe_pendidikan', 'LIKE', '%SMK%')->orWhere('tipe_pendidikan', 'LIKE', '%SMA%')->orWhere('tipe_pendidikan', 'LIKE', '%Siswa%')->count();

        // 3. 5 Data terbaru
        $recentPengajuans = Pengajuan::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPendaftar', 
            'totalMenunggu', 
            'totalAktif', 
            'totalSelesai', 
            'jumlahMahasiswa',
            'jumlahSiswa',
            'recentPengajuans'
        ));
    }
    
    public function index()
    {
        $pengajuanList = Pengajuan::latest()->get();
        return view('admin.pengajuan.index', compact('pengajuanList'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input: mendukung PDF, DOC, dan DOCX
        $request->validate([
            'status'             => 'required|in:diterima,ditolak',
            'catatan'            => 'nullable|string',
            'file_surat_balasan' => 'nullable|mimes:pdf,doc,docx|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120', 
        ], [
            'file_surat_balasan.mimes'     => 'File surat balasan harus berformat PDF, DOC, atau DOCX!',
            'file_surat_balasan.mimetypes' => 'Format file tidak terbaca dengan benar, pastikan file asli berupa PDF atau Word!',
            'file_surat_balasan.max'       => 'Ukuran file surat balasan maksimal 5MB!',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Jika di database MySQL nama kolom kamu adalah 'catatan_revisi':
        $dataUpdate = [
            'status'         => $request->status,
            'catatan_revisi' => $request->catatan, 
        ];

        // Jika Admin mengunggah file surat balasan
        if ($request->hasFile('file_surat_balasan')) {
            if ($pengajuan->file_surat_balasan) {
                Storage::delete('public/' . $pengajuan->file_surat_balasan);
            }
            $path = $request->file('file_surat_balasan')->store('surat_balasan', 'public');
            $dataUpdate['file_surat_balasan'] = $path;
        }

        // Update ke database
        $pengajuan->update($dataUpdate);

        return redirect()->back()->with('success', 'Status pengajuan dan surat balasan berhasil diperbarui!');
    }
}