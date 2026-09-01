<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JurnalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari pengajuan mahasiswa yang statusnya DITERIMA
        $pengajuan = Pengajuan::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->first();

        if (!$pengajuan) {
            return view('mahasiswa.jurnal.not_active');
        }

        $jurnals = Jurnal::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('mahasiswa.jurnal.index', compact('pengajuan', 'jurnals'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pengajuan = Pengajuan::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->firstOrFail();

        $request->validate([
            'tanggal'          => 'required|date',
            'jam_mulai'        => 'nullable',
            'jam_selesai'      => 'nullable',
            'kegiatan'         => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'file_dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumentasi')) {
            $filePath = $request->file('file_dokumentasi')->store('dokumentasi_jurnal', 'public');
        }

        Jurnal::create([
            'user_id'          => $user->id,
            'pengajuan_id'     => $pengajuan->id,
            'tanggal'          => $request->tanggal,
            'jam_mulai'        => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'kegiatan'         => $request->kegiatan,
            'deskripsi'        => $request->deskripsi,
            'file_dokumentasi' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Jurnal harian berhasil dicatat.');
    }

    public function destroy(Jurnal $jurnal)
    {
        if ($jurnal->user_id !== Auth::id()) {
            abort(403);
        }

        if ($jurnal->file_dokumentasi && Storage::disk('public')->exists($jurnal->file_dokumentasi)) {
            Storage::disk('public')->delete($jurnal->file_dokumentasi);
        }

        $jurnal->delete();

        return redirect()->back()->with('success', 'Jurnal berhasil dihapus.');
    }
}