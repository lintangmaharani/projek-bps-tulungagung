<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    // Menampilkan halaman daftar divisi beserta statistik ringkasnya
    public function index(Request $request)
    {
        $query = Divisi::query();

        // Pencarian berdasarkan nama divisi atau deskripsi
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_divisi', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $divisis = $query
            ->latest()
            ->get();

        // Statistik tetap menghitung seluruh data, bukan hasil filter
        $totalDivisi = Divisi::count();
        $aktifDivisi = Divisi::where('status', 'Aktif')->count();
        $nonaktifDivisi = Divisi::where('status', 'Nonaktif')->count();

        return view('admin.divisi.index', compact(
            'divisis',
            'totalDivisi',
            'aktifDivisi',
            'nonaktifDivisi'
        ));
    }

    // Menyimpan data divisi baru dari modal tambah
    public function store(Request $request)
    {
        // 1. Validasi data
        $validated = $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // 2. Simpan menggunakan variabel $validated
        Divisi::create($validated);

        return redirect()->route('admin.divisi.index')->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function update(Request $request, Divisi $divisi)
    {
        // 1. Validasi data
        $validated = $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // 2. Update menggunakan variabel $validated
        $divisi->update($validated);

        return redirect()->route('admin.divisi.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    // Menghapus data divisi
    public function destroy(Divisi $divisi)
    {
        $divisi->delete();

        return redirect()->route('admin.divisi.index')->with('success', 'Divisi berhasil dihapus!');
    }
}