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
        Schema::create('equipment_rental_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_rental_request_id');
            $table->unsignedBigInteger('equipment_rental_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('equipment_rental_request_id')->references('id')->on('equipment_rental_requests')->onDelete('cascade');
            $table->foreign('equipment_rental_id')->references('id')->on('equipment_rentals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_rental_request_items');
    }
};
