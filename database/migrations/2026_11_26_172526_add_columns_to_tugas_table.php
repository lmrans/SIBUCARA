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
        Schema::table('tugas', function (Blueprint $table) {
            $table->foreignId('budidaya_id')->after('id')->constrained('kegiatan_budidayas')->cascadeOnDelete();
            $table->foreignId('dibuat_oleh')->after('budidaya_id')->constrained('users');
            $table->foreignId('ditugaskan_ke')->after('dibuat_oleh')->constrained('users');
            $table->string('judul_tugas', 100)->after('ditugaskan_ke');
            $table->text('deskripsi')->nullable()->after('judul_tugas');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang')->after('deskripsi');
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai'])->default('terjadwal')->after('prioritas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn(['status', 'prioritas', 'deskripsi', 'judul_tugas']);
            $table->dropForeign(['ditugaskan_ke']);
            $table->dropColumn('ditugaskan_ke');
            $table->dropForeign(['dibuat_oleh']);
            $table->dropColumn('dibuat_oleh');
            $table->dropForeign(['budidaya_id']);
            $table->dropColumn('budidaya_id');
        });
    }
};
