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
            $table->text('rle_data')->nullable()->default(null)->after('png_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noun_traits', function (Blueprint $table) {
            $table->dropColumn('rle_data');
        });
    }
};
