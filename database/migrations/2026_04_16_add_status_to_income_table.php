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
        Schema::table('income', function (Blueprint $table) {
            if (!Schema::hasColumn('income', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('income_date');
            }
            if (!Schema::hasColumn('income', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income', function (Blueprint $table) {
            if (Schema::hasColumn('income', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('income', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });
    }
};
