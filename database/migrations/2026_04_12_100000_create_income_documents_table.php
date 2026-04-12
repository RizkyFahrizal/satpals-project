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
        Schema::create('income_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('income_id')->constrained('income')->onDelete('cascade');
            $table->string('file_path');
            $table->string('document_type')->nullable(); // bukti, invoice, dll
            $table->string('original_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_documents');
    }
};
