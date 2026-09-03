<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Jurnal;
use Illuminate\Http\Request; 

class PesertaAktifController extends Controller
{
    // Menampilkan seluruh peserta PKL/Magang aktif dengan fitur pencarian & filter
    public function index(Request $request)
    {
        $query = Pengajuan::with(['user', 'kuota', 'jurnals'])
            ->where('status', 'diterima');

        // Fitur Pencarian (Nama, Email, atau Instansi)
        if ($request->has('search') &&$request->search != '') {
            $search =$request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "\%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nama_instansi', 'LIKE', "%{$search}%");
            });
        }

        // Fitur Filter Berdasarkan Tipe Pendidikan (Mahasiswa Magang, PKL, SMK)
        if ($request->has('tipe') &&$request->tipe != '') {
            $query->where('tipe_pendidikan',$request->tipe);
        }

        $pesertaAktif =$query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.peserta.index', compact('pesertaAktif'));
    }

    // Menampilkan detail jurnal harian dari peserta tertentu
    public function show(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diterima') {
            abort(404);
        }

        $jurnals = Jurnal::where('pengajuan_id',$pengajuan->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('admin.peserta.show', compact('pengajuan', 'jurnals'));
    }
}