<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Jurnal;

class PesertaAktifController extends Controller
{
    // Menampilkan seluruh peserta PKL/Magang aktif (pengajuan diterima)
    public function index()
    {
        $pesertaAktif = Pengajuan::with(['user', 'kuota', 'jurnals'])
            ->where('status', 'diterima')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.peserta.index', compact('pesertaAktif'));
    }

    // Menampilkan detail jurnal harian dari peserta tertentu
    public function show(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diterima') {
            abort(404);
        }

        $jurnals = Jurnal::where('pengajuan_id', $pengajuan->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('admin.peserta.show', compact('pengajuan', 'jurnals'));
    }
}