<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill angkatan from diklat_period tahun_masuk where missing
        DB::statement('
            UPDATE members m
            SET m.angkatan = (
                SELECT dp.tahun_masuk
                FROM diklat_registrations dr
                JOIN diklat_periods dp ON dr.diklat_period_id = dp.id
                WHERE dr.id = m.diklat_registration_id
            )
            WHERE m.angkatan IS NULL AND m.diklat_registration_id IS NOT NULL
        ');

        // Backfill angkatan from tahun_daftar if still NULL
        DB::statement('
            UPDATE members
            SET angkatan = tahun_daftar
            WHERE angkatan IS NULL AND tahun_daftar IS NOT NULL
        ');

        // Drop tahun_daftar column
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('tahun_daftar');
        });

        // Make angkatan NOT NULL
        Schema::table('members', function (Blueprint $table) {
            $table->string('angkatan')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->year('tahun_daftar')->after('spesifikasi')->nullable();
            $table->string('angkatan')->nullable()->change();
        });
    }
};
