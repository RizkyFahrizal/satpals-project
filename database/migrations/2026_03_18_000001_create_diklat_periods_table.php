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
        Schema::create('diklat_periods', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode'); // e.g., "Diklat Kesenian 2024/2025"
            $table->integer('tahun_masuk'); // Tahun masuk angkatan yang bisa daftar
            $table->string('rekening_number'); // Nomor rekening untuk pembayaran
            $table->text('rekening_info')->nullable(); // Info lengkap: "Bank: BCA, Atas Nama: Satya Palapa, No: 1234567890"
            $table->boolean('is_open')->default(false); // Status: buka/tutup
            $table->date('tanggal_buka')->nullable(); // Kapan dibuka
            $table->date('tanggal_tutup')->nullable(); // Kapan ditutup
            $table->text('keterangan')->nullable(); // Deskripsi periode
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diklat_periods');
    }
};
