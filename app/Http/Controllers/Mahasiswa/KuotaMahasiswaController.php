<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kuota;
use Illuminate\Http\Request;

class KuotaMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kuota::withCount(['pengajuans as terisi' => function ($q) {
            $q->whereIn('status', ['diterima', 'diproses']);
        }])
        ->where('status', 'buka');

        // Filter pencarian berdasarkan nama periode atau keterangan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_periode', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori pendidikan
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $kuotas = $query->orderBy('tgl_mulai', 'asc')
            ->get()
            ->filter(function ($item) {
                // Hanya tampilkan jika jumlah terisi masih kurang dari jumlah kuota
                return $item->terisi < $item->jumlah_kuota;
            });

        return view('mahasiswa.kuota.index', compact('kuotas'));
    }
}