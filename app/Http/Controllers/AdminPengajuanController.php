<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPengajuanController extends Controller
{
    public function dashboard()
    {
        // 1. Data untuk Stat Cards
        $totalPendaftar = Pengajuan::count();
        $totalMenunggu  = Pengajuan::where('status', 'diproses')->count();
        $totalAktif     = Pengajuan::where('status', 'diterima')->count();
        $totalSelesai   = Pengajuan::where('status', 'selesai')->count();

        // 2. Data untuk Diagram Lingkaran (3 Kategori)
        $jumlahMahasiswaMagang = Pengajuan::where('tipe_pendidikan', 'Mahasiswa Magang')->count();
        $jumlahMahasiswaPKL    = Pengajuan::where('tipe_pendidikan', 'Mahasiswa PKL')->count();
        $jumlahSiswa           = Pengajuan::where('tipe_pendidikan', 'Siswa SMK')->count();

        // 3. Data untuk Tabel Terbaru & Lonceng Notifikasi
        $recentPengajuans = Pengajuan::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPendaftar',
            'totalMenunggu',
            'totalAktif',
            'totalSelesai',
            'jumlahMahasiswaMagang',
            'jumlahMahasiswaPKL',
            'jumlahSiswa',
            'recentPengajuans'
        ));
    }
    
    public function index(Request $request)
    {
        $query = Pengajuan::latest();

        // Fitur Pencarian (Nama, Email, atau Instansi)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('nama_instansi', 'LIKE', "%{$search}%");
            });
        }

        // Fitur Filter Berdasarkan Tipe Pendidikan
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe_pendidikan', $request->tipe);
        }

        $pengajuanList = $query->get();

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

        $dataUpdate = [
            'status'         => $request->status,
            'catatan_revisi' => $request->catatan, 
        ];

        // Jika Admin mengunggah file surat balasan
        $file = $request->file('file_surat_balasan');
        if ($file) {
            if ($pengajuan->file_surat_balasan) {
                Storage::delete('public/' . $pengajuan->file_surat_balasan);
            }
            $path = $file->store('surat_balasan', 'public');
            $dataUpdate['file_surat_balasan'] = $path;
        }

        // Update ke database
        $pengajuan->update($dataUpdate);

        return redirect()->back()->with('success', 'Status pengajuan dan surat balasan berhasil diperbarui!');
    }
}