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
        Schema::dropIfExists('notifikasis');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumen_pengeluarans')->cascadeOnDelete();
            $table->string('pesan', 255);
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
        });
    }
};
