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
        Schema::table('themes', function (Blueprint $table) {
            $table->string('gradient_direction')->default('to bottom right')->after('background_value');
            $table->json('gradient_colors')->nullable()->after('gradient_direction');
            $table->string('background_image')->nullable()->after('gradient_colors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn(['gradient_direction', 'gradient_colors', 'background_image']);
        });
    }
};
