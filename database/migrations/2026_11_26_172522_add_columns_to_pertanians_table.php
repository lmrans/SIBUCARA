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
        Schema::table('pertanians', function (Blueprint $table) {
            $table->foreignId('pemilik_id')->after('id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_pertanian', 100)->after('pemilik_id');
            $table->string('lokasi', 255)->after('nama_pertanian');
            $table->decimal('luas_lahan', 10, 2)->after('lokasi');
            $table->text('deskripsi')->nullable()->after('luas_lahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertanians', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'luas_lahan', 'lokasi', 'nama_pertanian']);
            $table->dropForeign(['pemilik_id']);
            $table->dropColumn('pemilik_id');
        });
    }
};
