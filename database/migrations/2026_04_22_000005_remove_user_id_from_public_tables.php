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
        // Remove user_id from studio_bookings
        if (Schema::hasTable('studio_bookings') && Schema::hasColumn('studio_bookings', 'user_id')) {
            Schema::table('studio_bookings', function (Blueprint $table) {
                $table->dropForeignIdFor(\App\Models\User::class);
                $table->dropColumn('user_id');
            });
        }

        // Remove user_id from band_rental_requests
        if (Schema::hasTable('band_rental_requests') && Schema::hasColumn('band_rental_requests', 'user_id')) {
            Schema::table('band_rental_requests', function (Blueprint $table) {
                $table->dropForeignIdFor(\App\Models\User::class);
                $table->dropColumn('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore user_id to studio_bookings
        if (Schema::hasTable('studio_bookings') && !Schema::hasColumn('studio_bookings', 'user_id')) {
            Schema::table('studio_bookings', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id');
            });
        }

        // Restore user_id to band_rental_requests
        if (Schema::hasTable('band_rental_requests') && !Schema::hasColumn('band_rental_requests', 'user_id')) {
            Schema::table('band_rental_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }
};
