<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_koreksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumen_pengeluarans')->cascadeOnDelete();
            $table->integer('versi_ke');
            $table->foreignId('jenis_kesalahan_id')->nullable()->constrained('jenis_kesalahans');
            $table->text('catatan_koreksi')->nullable();
            $table->dateTime('tanggal_koreksi');
            $table->foreignId('dikoreksi_oleh')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_koreksis');
    }
};
