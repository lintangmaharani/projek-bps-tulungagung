<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori',
        'tgl_mulai',
        'tgl_selesai',
        'jumlah_kuota',
        'status',
    ];

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function getTerisiAttribute()
    {
        return $this->pengajuans()
            ->whereIn('status', ['diterima', 'diproses'])
            ->count();
    }

    public function getSisaAttribute()
    {
        $sisa = $this->jumlah_kuota - $this->terisi;
        return $sisa < 0 ? 0 : $sisa;
    }
}