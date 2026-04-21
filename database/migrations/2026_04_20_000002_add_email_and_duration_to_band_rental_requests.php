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
            $table->string('renter_email')->nullable()->after('renter_phone');
            $table->unsignedSmallInteger('performance_duration_hours')->default(0)->after('performance_end_time');
            $table->unsignedSmallInteger('performance_duration_minutes')->default(0)->after('performance_duration_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('band_rental_requests', function (Blueprint $table) {
            $table->dropColumn(['renter_email', 'performance_duration_hours', 'performance_duration_minutes']);
        });
    }
};
