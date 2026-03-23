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
            // Remove spesifikasi if exists
            if (Schema::hasColumn('diklat_registrations', 'spesifikasi')) {
                $table->dropColumn('spesifikasi');
            }
            
            // Add tahun_daftar if not exists
            if (!Schema::hasColumn('diklat_registrations', 'tahun_daftar')) {
                $table->integer('tahun_daftar')->nullable()->after('prodi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklat_registrations', function (Blueprint $table) {
            // Add spesifikasi back
            if (!Schema::hasColumn('diklat_registrations', 'spesifikasi')) {
                $table->json('spesifikasi')->nullable();
            }
            
            // Remove tahun_daftar
            if (Schema::hasColumn('diklat_registrations', 'tahun_daftar')) {
                $table->dropColumn('tahun_daftar');
            }
        });
    }
};
