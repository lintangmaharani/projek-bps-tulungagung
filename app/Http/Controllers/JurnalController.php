<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Pengajuan;
use App\Models\Divisi;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Notifications\NotifPesertaUploadJurnal;

class JurnalController extends Controller
{
    public function index(Request $request)
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

        $query = Jurnal::where('user_id', $user->id)
            ->with('divisi');

        // Pencarian kegiatan, deskripsi, atau divisi
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
                ->orWhereHas('divisi', function ($d) use ($search) {
                    $d->where('nama_divisi', 'like', "%{$search}%");
                });
            });
        }

        // Filter status verifikasi
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        $jurnals = $query
            ->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.jurnal.index', compact('pengajuan', 'jurnals', 'divisis'));
    }

    private function validateTanggalDanJam(Request $request): void
    {
        $tanggal = Carbon::parse($request->tanggal);
        $hari = $tanggal->dayOfWeek;

        // Tanggal hanya boleh hari ini sampai 2 hari ke belakang
        if (
            $tanggal->lt(now()->startOfDay()->subDays(2)) ||
            $tanggal->gt(now()->endOfDay())
        ) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tanggal jurnal hanya dapat dipilih maksimal 2 hari ke belakang dan tidak boleh tanggal yang akan datang.',
            ]);
        }

        // Sabtu dan Minggu tidak diperbolehkan
        if ($hari === Carbon::SATURDAY || $hari === Carbon::SUNDAY) {
            throw ValidationException::withMessages([
                'tanggal' => 'Jurnal kegiatan hanya dapat diisi untuk hari Senin sampai Jumat.',
            ]);
        }

        $jamMulai = $request->jam_mulai;
        $jamSelesai = $request->jam_selesai;

        $batasMulai = '07:30';
        $batasSelesai = $hari === Carbon::FRIDAY ? '16:30' : '16:00';

        if ($jamMulai < $batasMulai || $jamMulai > $batasSelesai) {
            throw ValidationException::withMessages([
                'jam_mulai' => "Jam mulai harus berada dalam rentang {$batasMulai} - {$batasSelesai}.",
            ]);
        }

        if ($jamSelesai <= $jamMulai) {
            throw ValidationException::withMessages([
                'jam_selesai' => 'Jam selesai harus lebih besar dari jam mulai.',
            ]);
        }

        if ($jamSelesai > $batasSelesai) {
            throw ValidationException::withMessages([
                'jam_selesai' => "Jam selesai maksimal pukul {$batasSelesai}.",
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pengajuan = Pengajuan::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->firstOrFail();

        $request->validate([
            'divisi_id'        => 'required|exists:divisis,id',
            'tanggal'          => 'required|date',
            'jam_mulai'        => 'required|date_format:H:i',
            'jam_selesai'      => 'required|date_format:H:i',
            'kegiatan'         => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'file_dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $this->validateTanggalDanJam($request);

        $filePath = null;
        if ($request->hasFile('file_dokumentasi')) {
            $filePath = $request->file('file_dokumentasi')->store('dokumentasi_jurnal', 'public');
        }

        $jurnal = Jurnal::create([
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

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifPesertaUploadJurnal($jurnal, $user));
        }

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

        $request->merge([
            'jam_mulai' => $request->jam_mulai
                ? substr($request->jam_mulai, 0, 5)
                : null,

            'jam_selesai' => $request->jam_selesai
                ? substr($request->jam_selesai, 0, 5)
                : null,
        ]);

        $request->validate([
            'divisi_id'        => 'required|exists:divisis,id',
            'tanggal'          => 'required|date',
            'jam_mulai'        => 'required|date_format:H:i',
            'jam_selesai'      => 'required|date_format:H:i',
            'kegiatan'         => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'file_dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $this->validateTanggalDanJam($request);

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