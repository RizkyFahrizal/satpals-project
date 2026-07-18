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
        Schema::table('members', function (Blueprint $table) {
            // Add diklat_period_id foreign key
            $table->foreignId('diklat_period_id')->nullable()->after('diklat_registration_id')->constrained('diklat_periods')->nullOnDelete();
        });

        // Migrate existing members by getting period_id from their diklat_registration
        DB::statement('
            UPDATE members m
            JOIN diklat_registrations dr ON m.diklat_registration_id = dr.id
            SET m.diklat_period_id = dr.diklat_period_id
            WHERE m.diklat_period_id IS NULL
        ');

        // Modify members table to remove 'keluar' status and update enum
        DB::statement("
            ALTER TABLE members 
            MODIFY COLUMN status ENUM('aktif', 'alumni') DEFAULT 'aktif'
        ");

        // Update any 'keluar' status to 'alumni'
        DB::table('members')
            ->where('status', 'keluar')
            ->update(['status' => 'alumni']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore 'keluar' status
        DB::statement("
            ALTER TABLE members 
            MODIFY COLUMN status ENUM('aktif', 'alumni', 'keluar') DEFAULT 'aktif'
        ");

        Schema::table('members', function (Blueprint $table) {
            $table->dropForeignKey(['diklat_period_id']);
            $table->dropColumn('diklat_period_id');
        });
    }
};
