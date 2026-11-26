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
        Schema::table('memberships', function (Blueprint $table) {
            $table->foreignId('pertanian_id')->after('id')->constrained('pertanians')->cascadeOnDelete();
            $table->foreignId('user_id')->after('pertanian_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('divalidasi_oleh')->nullable()->after('user_id')->constrained('users');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('divalidasi_oleh');
            $table->date('tanggal_pengajuan')->after('status');
            $table->date('tanggal_verifikasi')->nullable()->after('tanggal_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn(['tanggal_verifikasi', 'tanggal_pengajuan', 'status']);
            $table->dropForeign(['divalidasi_oleh']);
            $table->dropColumn('divalidasi_oleh');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['pertanian_id']);
            $table->dropColumn('pertanian_id');
        });
    }
};
