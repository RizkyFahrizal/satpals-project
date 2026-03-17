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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('judul_lomba');
            $table->string('juara'); // Juara 1, Juara 2, Juara 3, Finalis, dll
            $table->text('deskripsi')->nullable();
            $table->string('nama_band')->nullable();
            $table->json('anggota')->nullable(); // Array nama-nama anggota
            $table->date('tanggal_lomba');
            $table->string('tempat_lomba');
            $table->string('penyelenggara')->nullable();
            $table->string('foto_1')->nullable(); // Foto dokumentasi 1
            $table->string('foto_2')->nullable(); // Foto dokumentasi 2
            $table->string('foto_3')->nullable(); // Foto dokumentasi 3
            $table->boolean('is_published')->default(true);
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
        Schema::dropIfExists('achievements');
    }
};
