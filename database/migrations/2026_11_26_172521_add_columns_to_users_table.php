<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Database lama mungkin belum memiliki role/phone meskipun migrasi dasar
     * sudah ditandai sebagai ran, jadi kolom ditambahkan hanya jika belum ada.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['pemilik', 'mandor', 'pekerja'])
                    ->default('pekerja')
                    ->after('name');
            });
        }

        if (! Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 20)->nullable()->after('email');
            });
        }

        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 100)->nullable()->after('name');
            });
        }

        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * role/phone sengaja tidak dihapus karena merupakan bagian dari migrasi dasar
     * pada instalasi fresh; rollback hanya membalik kolom tambahan ERD.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'username']);
        });
    }
};
