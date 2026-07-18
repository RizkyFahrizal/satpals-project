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
            // Add foreign key to diklat_periods
            $table->foreignId('diklat_period_id')->nullable()->after('id')->constrained('diklat_periods')->nullOnDelete();
            
            // Add tahun_masuk and tahun_daftar (auto-filled from period)
            $table->integer('tahun_masuk')->nullable()->after('prodi');
            $table->integer('tahun_daftar')->nullable()->after('tahun_masuk');
            
            // Add custom spesifikasi field (DJ, violin, harmonica, etc)
            $table->json('spesifikasi_lainnya')->nullable()->after('spesifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklat_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('diklat_registrations', 'diklat_period_id')) {
                $table->dropForeign(['diklat_period_id']);
                $table->dropColumn('diklat_period_id');
            }
            if (Schema::hasColumn('diklat_registrations', 'tahun_masuk')) {
                $table->dropColumn('tahun_masuk');
            }
            if (Schema::hasColumn('diklat_registrations', 'tahun_daftar')) {
                $table->dropColumn('tahun_daftar');
            }
            if (Schema::hasColumn('diklat_registrations', 'spesifikasi_lainnya')) {
                $table->dropColumn('spesifikasi_lainnya');
            }
        });
    }
};
