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
        Schema::create('expense_approval_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_approval_id')->constrained('expense_approvals')->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('document_type')->nullable();
            $table->timestamps();
            
            $table->index('expense_approval_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_approval_documents');
    }
};
