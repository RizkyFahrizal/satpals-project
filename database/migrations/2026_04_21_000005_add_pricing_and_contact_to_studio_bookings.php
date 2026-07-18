<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->string('booking_code')->nullable()->unique()->after('id');
            $table->string('renter_email')->nullable()->after('nama_pemohon');
            $table->string('renter_phone')->nullable()->after('renter_email');
            $table->unsignedInteger('jumlah_non_ukm')->default(0)->after('renter_phone');
            $table->unsignedInteger('harga_satuan')->default(15000)->after('jumlah_non_ukm');
            $table->unsignedInteger('harga_pokok')->default(0)->after('harga_satuan');
            $table->unsignedSmallInteger('diskon_persen')->default(0)->after('harga_pokok');
            $table->unsignedInteger('diskon_nominal')->default(0)->after('diskon_persen');
            $table->unsignedInteger('harga_final')->default(0)->after('diskon_nominal');
            $table->foreignId('income_id')->nullable()->after('harga_final')->constrained('income')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('income_id');
            $table->dropUnique(['booking_code']);
            $table->dropColumn([
                'booking_code',
                'renter_email',
                'renter_phone',
                'jumlah_non_ukm',
                'harga_satuan',
                'harga_pokok',
                'diskon_persen',
                'diskon_nominal',
                'harga_final',
            ]);
        });
    }
};
