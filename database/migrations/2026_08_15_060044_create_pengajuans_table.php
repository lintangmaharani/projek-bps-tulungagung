<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Menggunakan nullable unsignedBigInteger agar aman jika tabel kuotas dibuat belakangan
            $table->unsignedBigInteger('kuota_id')->nullable();

            // Tahap 1 - Data Diri
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_hp');

            // Tahap 2 - Riwayat Pendidikan (String agar aman untuk "Perguruan Tinggi")
            $table->string('tipe_pendidikan', 100);
            $table->string('nama_instansi');
            $table->string('fakultas')->nullable();
            $table->string('prodi_jurusan');
            $table->string('tingkat');

            // Tahap 3 - Periode & Berkas
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('file_cv');
            $table->string('file_surat_pengantar');

            // Status Pengajuan
            $table->enum('status', ['diproses', 'diterima', 'ditolak'])->default('diproses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};