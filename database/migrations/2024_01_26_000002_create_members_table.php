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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diklat_registration_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('no_telepon');
            $table->string('npm')->unique();
            $table->string('fakultas');
            $table->string('prodi');
            $table->json('spesifikasi'); // drum, keyboard, vocal, bass, guitar
            $table->year('tahun_daftar');
            $table->string('angkatan')->nullable(); // e.g., "2024", "2025"
            $table->enum('status', ['aktif', 'alumni', 'keluar'])->default('aktif');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
