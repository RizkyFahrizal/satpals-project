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
        Schema::create('equipment_rental_requests', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('renter_name');
            $table->string('renter_npm_nik');
            $table->string('renter_phone');
            $table->string('renter_email');
            $table->string('renter_ktp_ktm')->nullable()->comment('Path foto KTP/KTM');
            $table->text('rental_location');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days');
            $table->decimal('total_price', 12, 2);
            $table->text('renter_notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_progress', 'done'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_rental_requests');
    }
};
