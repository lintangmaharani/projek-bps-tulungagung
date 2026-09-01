<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuota;
use Illuminate\Http\Request;

class KuotaController extends Controller
{
    public function index()
    {
        $kuotas = Kuota::withCount(['pengajuans as terisi' => function ($query) {
            $query->whereIn('status', ['diterima', 'diproses']);
        }])->orderBy('tgl_mulai', 'asc')->paginate(10);

        return view('admin.kuota.index', compact('kuotas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_kuota' => 'required|integer|min:1',
            'status'       => 'required|in:buka,tutup',
            'keterangan'   => 'nullable|string',
        ]);

        Kuota::create($validated);

        return redirect()->route('admin.kuota.index')->with('success', 'Periode kuota magang berhasil ditambahkan.');
    }

    public function update(Request $request, Kuota $kuota)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_kuota' => 'required|integer|min:1',
            'status'       => 'required|in:buka,tutup',
            'keterangan'   => 'nullable|string',
        ]);

        $kuota->update($validated);

        return redirect()->route('admin.kuota.index')->with('success', 'Data kuota magang berhasil diperbarui.');
    }

    public function destroy(Kuota $kuota)
    {
        $kuota->delete();
        return redirect()->route('admin.kuota.index')->with('success', 'Data kuota berhasil dihapus.');
    }
}