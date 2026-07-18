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
        Schema::table('diklat_registrations', function (Blueprint $table) {
            // Drop tahun_daftar column as it's redundant with tahun_masuk
            if (Schema::hasColumn('diklat_registrations', 'tahun_daftar')) {
                $table->dropColumn('tahun_daftar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklat_registrations', function (Blueprint $table) {
            // Restore tahun_daftar column if rollback
            if (!Schema::hasColumn('diklat_registrations', 'tahun_daftar')) {
                $table->integer('tahun_daftar')->nullable()->after('tahun_masuk');
            }
        });
    }
};
