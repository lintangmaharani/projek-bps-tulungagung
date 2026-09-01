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
        }])->orderBy('tgl_mulai', 'asc')->get();

        return view('mahasiswa.kuota.index', compact('kuotas'));
    }
}