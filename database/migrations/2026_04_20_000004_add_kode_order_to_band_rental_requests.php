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
            // Add kode_order if it doesn't exist
            if (!Schema::hasColumn('band_rental_requests', 'kode_order')) {
                $table->string('kode_order')->nullable()->after('rental_purpose')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('band_rental_requests', function (Blueprint $table) {
            $table->dropColumn('kode_order');
        });
    }
};
