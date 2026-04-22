<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment_rental_requests', function (Blueprint $table) {
            $table->foreignId('income_id')->nullable()->after('admin_notes')->constrained('income')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->after('income_id')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });

        DB::statement("ALTER TABLE equipment_rental_requests MODIFY status ENUM('pending', 'approved', 'rejected', 'cancelled', 'completed', 'in_progress', 'done') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE equipment_rental_requests MODIFY status ENUM('pending', 'approved', 'rejected', 'in_progress', 'done') NOT NULL DEFAULT 'pending'");

        Schema::table('equipment_rental_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('income_id');
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn('approved_at');
        });
    }
};