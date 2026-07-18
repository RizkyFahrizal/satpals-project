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
        Schema::create('equipment_rentals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['paket', 'satuan'])->default('satuan');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('operator_crew_price', 12, 2)->nullable()->comment('Hanya untuk paket');
            $table->boolean('is_available')->default(true);
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
        Schema::dropIfExists('equipment_rentals');
    }
};
