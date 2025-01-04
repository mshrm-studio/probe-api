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
        Schema::table('dream_nouns', function (Blueprint $table) {
            $table->string('custom_trait_image')->nullable()->after('head_seed_id')->default(null);
            $table->string('custom_trait_layer')->nullable()->after('custom_trait_image')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dream_nouns', function (Blueprint $table) {
            $table->dropColumn('custom_trait_image');
            $table->dropColumn('custom_trait_layer');
        });
    }
};
