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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->text('tujuan_kegiatan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('ketua_pelaksana')->nullable();
            $table->json('divisi')->nullable(); // Array of {nama_divisi, ketua_divisi}
            $table->date('tanggal_kegiatan');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('tempat_kegiatan')->nullable();
            $table->string('foto_1')->nullable();
            $table->string('foto_2')->nullable();
            $table->string('foto_3')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
