<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('band_rental_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('band_rental_requests', 'rental_type')) {
                $table->string('rental_type', 20)->default('hourly')->after('rental_purpose');
            }
        });
    }

    public function down(): void
    {
        Schema::table('band_rental_requests', function (Blueprint $table) {
            if (Schema::hasColumn('band_rental_requests', 'rental_type')) {
                $table->dropColumn('rental_type');
            }
        });
    }
};