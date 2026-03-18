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
            
            // Add tahun_masuk column (auto-filled from period)
            $table->integer('tahun_masuk')->nullable()->after('spesifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diklat_registrations', function (Blueprint $table) {
            $table->dropForeignKey(['diklat_period_id']);
            $table->dropColumn(['diklat_period_id', 'tahun_masuk']);
        });
    }
};
