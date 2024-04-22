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
            $table->json('color_histogram')->nullable();
            $table->unsignedBigInteger('weight')->nullable();
            $table->unsignedBigInteger('area')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lil_nouns', function (Blueprint $table) {
            $table->dropColumn('color_histogram');
            $table->dropColumn('weight');
            $table->dropColumn('area');
        });
    }
};
