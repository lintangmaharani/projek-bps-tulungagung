<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class PesertaAktifController extends Controller
{
    // Menampilkan seluruh peserta PKL/Magang aktif
    public function index(Request $request)
    {
        $query = Pengajuan::with(['user', 'kuota', 'jurnals'])
            ->where('status', 'diterima');

        // Pencarian nama, email, atau instansi
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nama_instansi', 'LIKE', "%{$search}%");
            });
        }

        // Filter tipe pendidikan
        if ($request->filled('tipe')) {
            $query->where('tipe_pendidikan', $request->tipe);
        }

        $pesertaAktif = $query
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.peserta.index', compact('pesertaAktif'));
    }


    // Menampilkan detail jurnal peserta
    public function show(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diterima') {
            abort(404);
        }

        $query = Jurnal::where('pengajuan_id', $pengajuan->id)
            ->with('divisi');

        // Pencarian jurnal
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                  ->orWhereHas('divisi', function ($d) use ($search) {
                      $d->where('nama_divisi', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter status verifikasi
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        $jurnals = $query
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.peserta.show', compact(
            'pengajuan',
            'jurnals'
        ));
    }


    // Verifikasi jurnal oleh admin
    public function verifikasiJurnal(Request $request, Jurnal $jurnal)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:diterima',
            'feedback_admin' => 'nullable|string',
        ]);

        $jurnal->update([
            'status_verifikasi' => $request->status_verifikasi,
            'feedback_admin' => $request->feedback_admin,
        ]);

        return back()->with(
            'success',
            'Verifikasi jurnal berhasil diperbarui.'
        );
    }


    // Menandai dokumentasi pernah dilihat admin
    public function lihatDokumentasi(Jurnal $jurnal)
    {
        $jurnal->update([
            'sudah_dilihat' => true
        ]);

        return response()->file(
            storage_path('app/public/' . $jurnal->file_dokumentasi)
        );
    }
}