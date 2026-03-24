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
        Schema::table('board_members', function (Blueprint $table) {
            // Add diklat_period_id foreign key
            $table->foreignId('diklat_period_id')->nullable()->after('member_id')->constrained('diklat_periods')->nullOnDelete();
            
            // Add timestamps for periode dibuka/ditutup
            $table->dateTime('tanggal_buka')->nullable()->after('periode');
            $table->dateTime('tanggal_tutup')->nullable()->after('tanggal_buka');
            
            // Add foto column if not exists
            if (!Schema::hasColumn('board_members', 'foto')) {
                $table->string('foto')->nullable()->after('urutan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            $table->dropForeignKey(['diklat_period_id']);
            $table->dropColumn('diklat_period_id');
            $table->dropColumn('tanggal_buka');
            $table->dropColumn('tanggal_tutup');
            if (Schema::hasColumn('board_members', 'foto')) {
                $table->dropColumn('foto');
            }
        });
    }
};
