<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->cascadeOnDelete();
            $table->string('template_name')->default('classic');
            $table->string('background_type')->default('SOLID'); // SOLID | GRADIENT | IMAGE
            $table->string('background_value')->default('#ffffff');
            $table->string('primary_color')->default('#000000');
            $table->string('secondary_color')->default('#666666');
            $table->string('text_color')->default('#000000');
            $table->string('button_color')->default('#000000');
            $table->string('button_text_color')->default('#ffffff');
            $table->string('button_style')->default('ROUNDED'); // ROUNDED | PILL | SQUARE | GLASS | OUTLINE
            $table->string('font_family')->default('Inter');
            $table->string('font_size')->default('md');
            $table->string('font_weight')->default('normal');
            $table->string('layout')->default('center');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
