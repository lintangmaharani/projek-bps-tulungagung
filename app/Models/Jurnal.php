<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pengajuan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'kegiatan',
        'deskripsi',
        'file_dokumentasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}