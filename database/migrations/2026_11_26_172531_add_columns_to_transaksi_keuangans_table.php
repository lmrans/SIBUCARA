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
        Schema::table('transaksi_keuangans', function (Blueprint $table) {
            $table->foreignId('pertanian_id')->after('id')->constrained('pertanians')->cascadeOnDelete();
            $table->foreignId('user_id')->after('pertanian_id')->constrained('users');
            $table->foreignId('penjualan_id')->nullable()->after('user_id')->constrained('penjualans')->nullOnDelete();
            $table->enum('jenis', ['pemasukan', 'pengeluaran'])->after('penjualan_id');
            $table->string('kategori', 100)->after('jenis');
            $table->decimal('jumlah', 15, 2)->after('kategori');
            $table->date('tanggal_transaksi')->after('jumlah');
            $table->text('keterangan')->after('tanggal_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keuangans', function (Blueprint $table) {
            $table->dropColumn(['keterangan', 'tanggal_transaksi', 'jumlah', 'kategori', 'jenis']);
            $table->dropForeign(['penjualan_id']);
            $table->dropColumn('penjualan_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['pertanian_id']);
            $table->dropColumn('pertanian_id');
        });
    }
};
