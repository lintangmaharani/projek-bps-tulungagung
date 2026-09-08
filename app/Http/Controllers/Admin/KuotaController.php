<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuota;
use Illuminate\Http\Request;

class KuotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kuota::query();

        if ($request->filled('search')) {
            $query->where('kategori', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $kuotas = $query->latest()->paginate(10);
        return view('admin.kuota.index', compact('kuotas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori'     => 'required|in:Siswa SMK,Mahasiswa Magang,Mahasiswa PKL',
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_kuota' => 'required|integer|min:1',
            'status'       => 'required|in:buka,tutup',
        ]);

        Kuota::create($validated);

        return redirect()->route('admin.kuota.index')->with('success', 'Periode kuota magang berhasil ditambahkan.');
    }

    public function update(Request $request, Kuota $kuotum)
    {
        $validated = $request->validate([
            'kategori'     => 'required|in:Siswa SMK,Mahasiswa Magang,Mahasiswa PKL',
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_kuota' => 'required|integer|min:1',
            'status'       => 'required|in:buka,tutup',
        ]);

        $kuotum->update($validated);

        return redirect()->route('admin.kuota.index')->with('success', 'Data kuota magang berhasil diperbarui.');
    }

    public function destroy(Kuota $kuota)
    {
        $kuota->delete();
        return redirect()->route('admin.kuota.index')->with('success', 'Data kuota berhasil dihapus.');
    }
}