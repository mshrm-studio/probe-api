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
        Schema::create('nouns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('index')->nullable();
            $table->index('index');
            $table->unsignedBigInteger('token_id')->nullable();
            $table->index('token_id');
            $table->longText('token_uri')->nullable();
            $table->unsignedSmallInteger('background_index')->nullable();
            $table->unsignedSmallInteger('body_index')->nullable();
            $table->unsignedSmallInteger('accessory_index')->nullable();
            $table->unsignedSmallInteger('head_index')->nullable();
            $table->unsignedSmallInteger('glasses_index')->nullable();
            $table->string('background_name')->nullable();
            $table->string('body_name')->nullable();
            $table->string('accessory_name')->nullable();
            $table->string('head_name')->nullable();
            $table->string('glasses_name')->nullable();
            $table->unsignedBigInteger('block_number')->nullable();
            $table->timestamp('minted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nouns');
    }
};
