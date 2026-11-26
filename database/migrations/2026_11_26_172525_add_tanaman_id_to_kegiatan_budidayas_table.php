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
            $table->foreignId('tanaman_id')->nullable()->after('id')->constrained('tanamen')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_budidayas', function (Blueprint $table) {
            $table->dropForeign(['tanaman_id']);
            $table->dropColumn('tanaman_id');
        });
    }
};
