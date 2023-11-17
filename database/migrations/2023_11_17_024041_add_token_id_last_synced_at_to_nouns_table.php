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
        Schema::table('nouns', function (Blueprint $table) {
            $table->timestamp('token_id_last_synced_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nouns', function (Blueprint $table) {
            $table->dropColumn('token_id_last_synced_at');
        });
    }
};
