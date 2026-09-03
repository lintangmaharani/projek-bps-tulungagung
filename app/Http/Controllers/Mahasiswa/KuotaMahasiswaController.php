<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kuota;

class KuotaMahasiswaController extends Controller
{
    public function index()
    {
        $kuotas = Kuota::withCount(['pengajuans as terisi' => function ($query) {
            $query->whereIn('status', ['diterima', 'diproses']);
        }])
        ->where('status', 'buka')
        ->orderBy('tgl_mulai', 'asc')
        ->get()
        ->filter(function ($item) {
            // Hanya tampilkan jika jumlah terisi masih kurang dari jumlah kuota
            return $item->terisi < $item->jumlah_kuota;
        });

        return view('mahasiswa.kuota.index', compact('kuotas'));
    }
}