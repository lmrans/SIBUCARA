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
        Schema::table('tanamen', function (Blueprint $table) {
            $table->foreignId('pertanian_id')->after('id')->constrained('pertanians')->cascadeOnDelete();
            $table->string('nama_tanaman', 100)->after('pertanian_id');
            $table->string('jenis', 100)->after('nama_tanaman');
            $table->integer('jumlah')->after('jenis');
            $table->date('tanggal_tanam')->after('jumlah');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('tanggal_tanam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanamen', function (Blueprint $table) {
            $table->dropColumn(['status', 'tanggal_tanam', 'jumlah', 'jenis', 'nama_tanaman']);
            $table->dropForeign(['pertanian_id']);
            $table->dropColumn('pertanian_id');
        });
    }
};
