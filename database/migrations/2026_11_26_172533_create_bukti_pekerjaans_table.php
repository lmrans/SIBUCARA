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
        Schema::create('bukti_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan_pekerjaans')->cascadeOnDelete();
            $table->string('nama_file', 255);
            $table->string('path_file', 255);
            $table->string('tipe_file', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_pekerjaans');
    }
};
