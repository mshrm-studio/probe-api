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
        Schema::create('noun_traits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('layer');
            $table->integer('seed_id');
            $table->string('svg_path');
            $table->unique(['layer', 'seed_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noun_traits');
    }
};
