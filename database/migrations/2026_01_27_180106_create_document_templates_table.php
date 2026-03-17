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
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template'); // Nama template surat
            $table->string('kategori')->nullable(); // Kategori: surat, rab, proposal, dll
            $table->string('file_path'); // Path file yang diupload
            $table->string('file_name'); // Nama file asli
            $table->string('file_type'); // docx, pdf, xlsx
            $table->bigInteger('file_size')->nullable(); // Ukuran file dalam bytes
            $table->text('deskripsi')->nullable(); // Deskripsi template
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
        Schema::dropIfExists('document_templates');
    }
};
