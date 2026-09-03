<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuans', 'foto')) {
                $table->string('foto')->nullable();
            }
            if (!Schema::hasColumn('pengajuans', 'file_surat_pengantar')) {
                $table->string('file_surat_pengantar')->nullable();
            }
            if (!Schema::hasColumn('pengajuans', 'file_surat_balasan')) {
                $table->string('file_surat_balasan')->nullable();
            }
            if (!Schema::hasColumn('pengajuans', 'catatan_revisi')) {
                $table->text('catatan_revisi')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn(['foto', 'file_surat_pengantar', 'file_surat_balasan', 'catatan_revisi']);
        });
    }
};