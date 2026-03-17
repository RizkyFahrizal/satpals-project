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
        Schema::create('board_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('jabatan'); // Ketua, Wakil Ketua, Sekretaris, etc.
            $table->string('divisi')->nullable(); // Divisi Musik, Divisi Humas, etc.
            $table->string('periode'); // "2024/2025", "2025/2026"
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // untuk sorting tampilan
            $table->timestamps();
        });

        // Add member_id to users table for linking
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('member_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropColumn('member_id');
        });
        
        Schema::dropIfExists('board_members');
    }
};
