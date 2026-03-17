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
        Schema::create('diklat_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->string('no_telepon_pribadi');
            $table->string('no_telepon_ortu')->nullable();
            $table->string('npm')->unique();
            $table->string('fakultas');
            $table->string('prodi');
            $table->json('spesifikasi'); // Array of: drum, keyboard, vocal, bass, guitar
            $table->string('bukti_pembayaran')->nullable(); // Image path
            $table->text('riwayat_penyakit')->nullable();
            $table->text('riwayat_alergi')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diklat_registrations');
    }
};
