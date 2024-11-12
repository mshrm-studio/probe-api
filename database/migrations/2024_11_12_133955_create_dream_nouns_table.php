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
        Schema::create('dream_nouns', function (Blueprint $table) {
            $table->id();
            $table->string('dreamer');

            $table->integer('accessory_seed_id')->nullable()->default(null);
            $table->foreign('accessory_seed_id')->references('seed_id')->on('noun_traits')->nullOnDelete();

            $table->integer('background_seed_id')->nullable()->default(null);
            $table->foreign('background_seed_id')->references('seed_id')->on('noun_traits')->nullOnDelete();

            $table->integer('body_seed_id')->nullable()->default(null);
            $table->foreign('body_seed_id')->references('seed_id')->on('noun_traits')->nullOnDelete();

            $table->integer('glasses_seed_id')->nullable()->default(null);
            $table->foreign('glasses_seed_id')->references('seed_id')->on('noun_traits')->nullOnDelete();

            $table->integer('head_seed_id')->nullable()->default(null);
            $table->foreign('head_seed_id')->references('seed_id')->on('noun_traits')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dream_nouns');
    }
};
