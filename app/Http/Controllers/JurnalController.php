<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Pengajuan;
use App\Models\Divisi; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JurnalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari pengajuan mahasiswa yang statusnya DITERIMA beserta relasi kuota dan divisinya
        $pengajuan = Pengajuan::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->with('kuota.divisi')
            ->first();

        if (!$pengajuan) {
            return view('mahasiswa.jurnal.not_active');
        }

        // Ambil daftar divisi yang aktif untuk ditampilkan pada dropdown pilihan di form
        $divisis = Divisi::where('status', 'Aktif')->get();

        $jurnals = Jurnal::where('user_id', $user->id)
            ->with('divisi') // Pastikan model Jurnal memiliki relasi ke Divisi
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('mahasiswa.jurnal.index', compact('pengajuan', 'jurnals', 'divisis'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pengajuan = Pengajuan::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->firstOrFail();

        $request->validate([
            'divisi_id'        => 'required|exists:divisis,id', // Validasi pilihan divisi
            'tanggal'          => 'required|date',
            'jam_mulai'        => 'nullable|date_format:H:i',
            'jam_selesai'      => 'nullable|date_format:H:i|after_or_equal:jam_mulai',
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
            'divisi_id'        => $request->divisi_id,
            'tanggal'          => $request->tanggal,
            'jam_mulai'        => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'kegiatan'         => $request->kegiatan,
            'deskripsi'        => $request->deskripsi,
            'file_dokumentasi' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Jurnal harian berhasil dicatat.');
    }

    public function edit(Jurnal $jurnal)
    {
        if ($jurnal->user_id !== Auth::id()) {
            abort(403);
        }

        $divisis = Divisi::where('status', 'Aktif')->get();

        return view('mahasiswa.jurnal.edit', compact('jurnal', 'divisis'));
    }

    public function update(Request $request, Jurnal $jurnal)
    {
        if ($jurnal->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'divisi_id'        => 'required|exists:divisis,id',
            'tanggal'          => 'required|date',
            'jam_mulai'        => 'nullable',
            'jam_selesai'      => 'nullable',
            'kegiatan'         => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'file_dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        if ($request->hasFile('file_dokumentasi')) {
            if ($jurnal->file_dokumentasi && Storage::disk('public')->exists($jurnal->file_dokumentasi)) {
                Storage::disk('public')->delete($jurnal->file_dokumentasi);
            }
            $jurnal->file_dokumentasi = $request->file('file_dokumentasi')->store('dokumentasi_jurnal', 'public');
        }

        // UPDATE SECARA MANUAL (DIJAMIN MASUK KE DATABASE)
        $jurnal->divisi_id        = $request->divisi_id;
        $jurnal->tanggal          = $request->tanggal;
        $jurnal->jam_mulai        = $request->jam_mulai;
        $jurnal->jam_selesai      = $request->jam_selesai;
        $jurnal->kegiatan         = $request->kegiatan;
        $jurnal->deskripsi        = $request->deskripsi;
        // file_dokumentasi tetap dipertahankan jika tidak upload baru
        $jurnal->save();

        return redirect()->route('jurnal.index')->with('success', 'Jurnal kegiatan berhasil diperbarui.');
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