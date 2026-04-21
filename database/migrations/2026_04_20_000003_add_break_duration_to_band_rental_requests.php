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
        Schema::table('band_rental_requests', function (Blueprint $table) {
            $table->unsignedSmallInteger('break_duration_hours')->default(0)->after('performance_duration_minutes');
            $table->unsignedSmallInteger('break_duration_minutes')->default(0)->after('break_duration_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('band_rental_requests', function (Blueprint $table) {
            $table->dropColumn(['break_duration_hours', 'break_duration_minutes']);
        });
    }
};
