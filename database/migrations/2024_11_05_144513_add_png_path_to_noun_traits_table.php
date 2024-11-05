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
        Schema::table('noun_traits', function (Blueprint $table) {
            $table->string('png_path')->after('svg_path')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noun_traits', function (Blueprint $table) {
            $table->dropColumn('png_path');
        });
    }
};
