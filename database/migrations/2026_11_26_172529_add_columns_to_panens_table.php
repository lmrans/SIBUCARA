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
        Schema::table('panens', function (Blueprint $table) {
            $table->foreignId('tanaman_id')->after('id')->constrained('tanamen')->cascadeOnDelete();
            $table->date('tanggal_panen')->after('tanaman_id');
            $table->decimal('jumlah', 10, 2)->after('tanggal_panen');
            $table->string('satuan', 30)->after('jumlah');
            $table->string('kualitas', 30)->after('satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panens', function (Blueprint $table) {
            $table->dropColumn(['kualitas', 'satuan', 'jumlah', 'tanggal_panen']);
            $table->dropForeign(['tanaman_id']);
            $table->dropColumn('tanaman_id');
        });
    }
};
