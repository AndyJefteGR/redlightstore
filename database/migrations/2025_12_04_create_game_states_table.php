<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('game_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tick_count')->default(0);
            $table->enum('current_cycle', ['day', 'night'])->default('day');
            $table->unsignedInteger('day_duration')->default(2);
            $table->unsignedInteger('night_duration')->default(2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_states');
    }
};