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
            $table->unsignedBigInteger('index')->nullable();
            $table->index('index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lil_nouns', function (Blueprint $table) {
            $table->dropIndex(['index']);
            $table->dropColumn('index');
        });
    }
};
