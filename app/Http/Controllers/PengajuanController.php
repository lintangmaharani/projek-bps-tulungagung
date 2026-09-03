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
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Ambil data kuota berdasarkan kuota_id yang dipilih pengguna
        $kuota = Kuota::find($request->kuota_id);

        $request->validate([
            'kuota_id'        => 'required|exists:kuotas,id',
            'nama_lengkap'    => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'tempat_lahir'    => 'required|string|max:255',
            'tanggal_lahir'   => 'required|date',
            'alamat'          => 'required|string',
            'no_hp'           => 'required|string|max:20',
            'tipe_pendidikan' => 'required|string',
            'nama_instansi'   => 'required|string|max:255',
            'prodi_jurusan'   => 'required|string|max:255',
            'tingkat'         => 'required|string|max:50',
            
            // Validasi Tanggal Disesuaikan dengan Periode Kuota
            'tgl_mulai'   => [
                'required',
                'date',
                $kuota ? 'after_or_equal:' . $kuota->tgl_mulai : '',
                $kuota ? 'before_or_equal:' . $kuota->tgl_selesai : '',
            ],
            'tgl_selesai' => [
                'required',
                'date',
                'after_or_equal:tgl_mulai',
                $kuota ? 'before_or_equal:' . $kuota->tgl_selesai : '',
            ],
            
            'foto'                 => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
            'file_surat_pengantar' => 'required|file|mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,application/zip,application/x-zip-compressed,application/x-rar-compressed|max:5120',
        ], [
            // Pesan Error Kustom untuk Tanggal Periode
            'tgl_mulai.after_or_equal'   => 'Tanggal mulai magang tidak boleh sebelum tanggal awal periode (' . ($kuota ? \Carbon\Carbon::parse($kuota->tgl_mulai)->format('d-m-Y') : '') . ').',
            'tgl_mulai.before_or_equal'  => 'Tanggal mulai magang tidak boleh melebihi tanggal akhir periode (' . ($kuota ? \Carbon\Carbon::parse($kuota->tgl_selesai)->format('d-m-Y') : '') . ').',
            'tgl_selesai.before_or_equal' => 'Tanggal selesai magang tidak boleh melebihi tanggal akhir periode (' . ($kuota ? \Carbon\Carbon::parse($kuota->tgl_selesai)->format('d-m-Y') : '') . ').',
            'tgl_selesai.after_or_equal'  => 'Tanggal selesai magang harus sama atau setelah tanggal mulai.',
        ]);

        $fotoPath  = $request->file('foto')->store('foto_pemohon', 'public');
        $suratPath = $request->file('file_surat_pengantar')->store('berkas/surat', 'public');

        Pengajuan::create([
            'user_id'              => Auth::id(),
            'kuota_id'             => $request->kuota_id,
            'nama_lengkap'         => $request->nama_lengkap,
            'email'                => $request->email,
            'tempat_lahir'         => $request->tempat_lahir,
            'tanggal_lahir'        => $request->tanggal_lahir,
            'alamat'               => $request->alamat,
            'no_hp'                => $request->no_hp,
            'tipe_pendidikan'      => $request->tipe_pendidikan,
            'nama_instansi'        => $request->nama_instansi,
            'fakultas'             => $request->fakultas,
            'prodi_jurusan'        => $request->prodi_jurusan,
            'tingkat'              => $request->tingkat,
            'tgl_mulai'            => $request->tgl_mulai,
            'tgl_selesai'          => $request->tgl_selesai,
            'foto'                 => $fotoPath,
            'file_surat_pengantar' => $suratPath,
            'status'               => 'diproses',
        ]);

        return redirect()->route('pengajuan.status')->with('success', 'Pengajuan PKL/Magang Anda berhasil dikirim!');
    }
}