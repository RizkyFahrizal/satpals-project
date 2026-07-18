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
        Schema::table('board_members', function (Blueprint $table) {
            // Drop divisi column - not needed, jabatan is enough
            if (Schema::hasColumn('board_members', 'divisi')) {
                $table->dropColumn('divisi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            // Recreate divisi column if rollback
            $table->string('divisi')->nullable()->after('jabatan');
        });
    }
};
