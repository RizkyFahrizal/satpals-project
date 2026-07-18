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
        Schema::table('bands', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('is_available');
            $table->string('instagram_username')->nullable()->after('whatsapp_number');
            $table->string('tiktok_username')->nullable()->after('instagram_username');
            $table->string('youtube_url')->nullable()->after('tiktok_username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bands', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_number', 'instagram_username', 'tiktok_username', 'youtube_url']);
        });
    }
};
