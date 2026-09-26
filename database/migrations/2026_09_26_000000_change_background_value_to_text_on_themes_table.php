<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->text('background_value')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->string('background_value', 255)->nullable()->change();
        });
    }
};
