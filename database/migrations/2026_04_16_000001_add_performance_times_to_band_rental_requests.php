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
        Schema::table('band_rental_requests', function (Blueprint $table) {
            $table->time('performance_start_time')->nullable()->after('performance_date');
            $table->time('performance_end_time')->nullable()->after('performance_start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('band_rental_requests', function (Blueprint $table) {
            $table->dropColumn(['performance_start_time', 'performance_end_time']);
        });
    }
};
