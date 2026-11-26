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
        Schema::table('kegiatan_budidayas', function (Blueprint $table) {
            $table->string('nama_kegiatan');
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('rencana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_budidayas', function (Blueprint $table) {
            $table->dropColumn(['nama_kegiatan', 'tanggal', 'deskripsi', 'status']);
        });
    }
};
