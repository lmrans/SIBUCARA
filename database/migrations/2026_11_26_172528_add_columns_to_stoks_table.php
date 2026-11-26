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
        Schema::table('stoks', function (Blueprint $table) {
            $table->foreignId('pertanian_id')->after('id')->constrained('pertanians')->cascadeOnDelete();
            $table->string('nama_barang', 100)->after('pertanian_id');
            $table->string('jenis_barang', 100)->after('nama_barang');
            $table->decimal('jumlah', 10, 2)->after('jenis_barang');
            $table->string('satuan', 30)->after('jumlah');
            $table->date('tanggal_masuk')->after('satuan');
            $table->text('keterangan')->nullable()->after('tanggal_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stoks', function (Blueprint $table) {
            $table->dropColumn(['keterangan', 'tanggal_masuk', 'satuan', 'jumlah', 'jenis_barang', 'nama_barang']);
            $table->dropForeign(['pertanian_id']);
            $table->dropColumn('pertanian_id');
        });
    }
};
