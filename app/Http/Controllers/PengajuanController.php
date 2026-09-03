<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Kuota;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    // 1. Halaman Form Pengajuan
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Ambil data pengajuan mahasiswa login untuk pengecekan status di view
        $pengajuan = Pengajuan::where('user_id', Auth::id())->latest()->first();

        // Ambil kuota yang statusnya BUKA dan masih memiliki sisa kuota
        $kuotas = Kuota::withCount(['pengajuans as terisi' => function ($q) {
            $q->whereIn('status', ['diterima', 'diproses']);
        }])
        ->where('status', 'buka')
        ->get()
        ->filter(function ($kuota) {
            return ($kuota->jumlah_kuota - $kuota->terisi) > 0;
        });

        return view('mahasiswa.pengajuan', compact('pengajuan', 'kuotas'));
    }

    // 2. Halaman Status Pengajuan
    public function status()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $pengajuan = Pengajuan::where('user_id', Auth::id())->latest()->first();

        return view('mahasiswa.status', compact('pengajuan'));
    }

    // 3. Simpan Pengajuan Baru
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tipe_pendidikan' => 'required|string',
            'nama_instansi' => 'required|string|max:255',
            'fakultas' => 'nullable|string|max:255', // Buat nullable jika siswa SMK tidak ada fakultas
            'prodi_jurusan' => 'required|string|max:255',
            'tingkat' => 'required|string|max:100',
            'kuota_id' => 'required|exists:kuotas,id',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'file_surat_pengantar' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5048',
        ]);

        // 2. Proses upload file FOTO
        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('foto_pemohon', 'public');
        }

        // 3. Proses upload file SURAT PENGANTAR
        $pathSurat = null;
        if ($request->hasFile('file_surat_pengantar')) {
            $pathSurat = $request->file('file_surat_pengantar')->store('surat_pengantar', 'public');
        }

        // 4. Simpan SEMUA data ke Database secara lengkap
        Pengajuan::create([
            'user_id' => Auth::id(),
            'kuota_id' => $request->kuota_id,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => Auth::user()->email, // Mengambil email dari akun yang sedang login
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'tipe_pendidikan' => $request->tipe_pendidikan,
            'nama_instansi' => $request->nama_instansi,
            'fakultas' => $request->fakultas,
            'prodi_jurusan' => $request->prodi_jurusan,
            'tingkat' => $request->tingkat,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'foto' => $pathFoto, // Tersimpan dengan aman dan tidak null lagi!
            'file_surat_pengantar' => $pathSurat,
            'status' => 'diproses',
        ]);

        return redirect()->route('pengajuan.status')->with('success', 'Pengajuan berhasil dikirim!');
    }
}