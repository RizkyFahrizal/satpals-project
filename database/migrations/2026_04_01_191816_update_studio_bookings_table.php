<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            // Add new fields for public booking
            $table->string('nomor_identitas')->nullable();
            $table->string('nama_pemohon')->nullable();
            
            // Make user_id nullable to support public bookings
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->dropColumn(['nomor_identitas', 'nama_pemohon']);
            
            // Revert user_id to required
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
