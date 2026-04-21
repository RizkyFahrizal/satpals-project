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
            $table->text('venue_address')->nullable()->after('rental_purpose')->comment('Alamat lengkap tempat persewaan band');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('band_rental_requests', function (Blueprint $table) {
            $table->dropColumn('venue_address');
        });
    }
};
