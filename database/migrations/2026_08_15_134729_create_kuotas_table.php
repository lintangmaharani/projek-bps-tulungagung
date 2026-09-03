<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuotas', function (Blueprint $table) {
            $table->id();
            // Tambahkan kolom kategori untuk 3 pilihan
            $table->enum('kategori', ['Siswa SMK', 'Mahasiswa Magang', 'Mahasiswa PKL']);
            $table->string('nama_periode'); // Contoh: "Periode SMK 2026"
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->integer('jumlah_kuota');
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuotas');
    }
};