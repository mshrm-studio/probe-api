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
        Schema::table('lil_nouns', function (Blueprint $table) {
            $table->unsignedSmallInteger('background_index')->nullable();
            $table->unsignedSmallInteger('body_index')->nullable();
            $table->unsignedSmallInteger('accessory_index')->nullable();
            $table->unsignedSmallInteger('head_index')->nullable();
            $table->unsignedSmallInteger('glasses_index')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lil_nouns', function (Blueprint $table) {
            $table->dropColumn('background_index');
            $table->dropColumn('body_index');
            $table->dropColumn('accessory_index');
            $table->dropColumn('head_index');
            $table->dropColumn('glasses_index');
        });
    }
};
