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
        Schema::create('letter_archives', function (Blueprint $table) {
            $table->id();
            $table->string('nama_surat'); // Nama/judul surat
            $table->string('nomor_surat')->nullable(); // Nomor surat
            $table->enum('jenis', ['masuk', 'keluar']); // Jenis surat: masuk atau keluar
            $table->date('tanggal_surat'); // Tanggal surat
            $table->string('pengirim')->nullable(); // Pengirim (untuk surat masuk)
            $table->string('penerima')->nullable(); // Penerima (untuk surat keluar)
            $table->string('perihal')->nullable(); // Perihal surat
            $table->string('file_path'); // Path file yang diupload
            $table->string('file_name'); // Nama file asli
            $table->string('file_type'); // docx, pdf, xlsx
            $table->bigInteger('file_size')->nullable(); // Ukuran file dalam bytes
            $table->text('keterangan')->nullable(); // Keterangan tambahan
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
        Schema::dropIfExists('letter_archives');
    }
};
