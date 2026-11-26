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
        Schema::table('laporan_pekerjaans', function (Blueprint $table) {
            $table->foreignId('tugas_id')->after('id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('pekerja_id')->after('tugas_id')->constrained('users');
            $table->foreignId('divalidasi_oleh')->nullable()->after('pekerja_id')->constrained('users');
            $table->date('tanggal_laporan')->after('divalidasi_oleh');
            $table->text('deskripsi')->after('tanggal_laporan');
            $table->enum('status_validasi', ['menunggu', 'disetujui', 'ditolak', 'revisi'])
                ->default('menunggu')
                ->after('deskripsi');
            $table->text('catatan_validasi')->nullable()->after('status_validasi');
            $table->date('tanggal_validasi')->nullable()->after('catatan_validasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_pekerjaans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_validasi', 'catatan_validasi', 'status_validasi', 'deskripsi', 'tanggal_laporan']);
            $table->dropForeign(['divalidasi_oleh']);
            $table->dropColumn('divalidasi_oleh');
            $table->dropForeign(['pekerja_id']);
            $table->dropColumn('pekerja_id');
            $table->dropForeign(['tugas_id']);
            $table->dropColumn('tugas_id');
        });
    }
};
