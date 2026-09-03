<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('jurnals', function (Blueprint $table) {
            $table->unsignedBigInteger('divisi_id')->after('pengajuan_id')->nullable();
            
            // Opsional (jika ingin relasi foreign key secara langsung di database):
            // $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('jurnals', function (Blueprint $table) {
            $table->dropColumn('divisi_id');
        });
    }
};
