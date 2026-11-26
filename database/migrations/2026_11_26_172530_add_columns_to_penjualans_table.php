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
        Schema::table('penjualans', function (Blueprint $table) {
            $table->foreignId('panen_id')->after('id')->constrained('panens')->cascadeOnDelete();
            $table->date('tanggal_penjualan')->after('panen_id');
            $table->string('pembeli', 100)->after('tanggal_penjualan');
            $table->decimal('jumlah', 10, 2)->after('pembeli');
            $table->decimal('harga_satuan', 15, 2)->after('jumlah');
            $table->decimal('total_harga', 15, 2)->after('harga_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn(['total_harga', 'harga_satuan', 'jumlah', 'pembeli', 'tanggal_penjualan']);
            $table->dropForeign(['panen_id']);
            $table->dropColumn('panen_id');
        });
    }
};
