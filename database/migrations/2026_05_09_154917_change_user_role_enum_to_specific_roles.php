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
        Schema::table('users', function (Blueprint $table) {
            // Change role enum to include specific positions
            $table->enum('role', [
                'super_admin',
                'public',
                'ketua_umum',
                'wakil_ketua_umum',
                'bendahara',
                'sekretaris',
                'mpa',
                'band',
                'peralatan',
                'humas',
                'pdd',
                'kesekretariatan'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert to old roles (anyone with specific role will be set to 'pengurus')
            $table->enum('role', [
                'super_admin',
                'pengurus',
                'public'
            ])->change();
        });
    }
};
