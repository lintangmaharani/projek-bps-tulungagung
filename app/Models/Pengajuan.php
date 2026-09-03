<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kuota_id',
        'nama_lengkap',
        'email',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'tipe_pendidikan',
        'nama_instansi',
        'fakultas',
        'prodi_jurusan',
        'tingkat',
        'tgl_mulai',
        'tgl_selesai',
        'foto',
        'file_surat_pengantar',
        'file_surat_balasan',
        'file_sertifikat', 
        'status',
        'catatan_revisi',
    ];

    // Relasi ke Model Kuota
    public function kuota()
    {
        return $this->belongsTo(Kuota::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Jurnal
    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'pengajuan_id');
    }
}