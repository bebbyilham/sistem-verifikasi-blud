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
        Schema::create('dokumen_pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dokumen', 30)->unique();
            $table->foreignId('bidang_id')->constrained('bidangs');
            $table->foreignId('pptk_id')->constrained('users');
            $table->string('jenis_dokumen', 50);
            $table->enum('sumber_dana', ['BLUD', 'APBD']);
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_ajuan');
            $table->string('status', 30);
            $table->string('file_path', 255);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pengeluarans');
    }
};
