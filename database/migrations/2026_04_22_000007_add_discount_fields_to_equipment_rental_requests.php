<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment_rental_requests', function (Blueprint $table) {
            $table->unsignedInteger('harga_pokok')->nullable()->after('total_price');
            $table->unsignedSmallInteger('diskon_persen')->default(0)->after('harga_pokok');
            $table->unsignedInteger('diskon_nominal')->default(0)->after('diskon_persen');
            $table->unsignedInteger('harga_final')->default(0)->after('diskon_nominal');
        });
    }

    public function down(): void
    {
        Schema::table('equipment_rental_requests', function (Blueprint $table) {
            $table->dropColumn([
                'harga_pokok',
                'diskon_persen',
                'diskon_nominal',
                'harga_final',
            ]);
        });
    }
};