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
            $table->string('background_name')->nullable();
            $table->string('body_name')->nullable();
            $table->string('accessory_name')->nullable();
            $table->string('head_name')->nullable();
            $table->string('glasses_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lil_nouns', function (Blueprint $table) {
            $table->dropColumn('background_name');
            $table->dropColumn('body_name');
            $table->dropColumn('accessory_name');
            $table->dropColumn('head_name');
            $table->dropColumn('glasses_name');
        });
    }
};
