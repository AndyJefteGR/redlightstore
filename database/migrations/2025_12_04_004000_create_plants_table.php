<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plot_id')->constrained()->onDelete('cascade');
            $table->string('plant_type');
            $table->unsignedTinyInteger('days_required');
            $table->unsignedTinyInteger('days_developed')->default(0);
            $table->unsignedTinyInteger('stage')->default(0);
            $table->string('current_image');
            $table->boolean('watered_today')->default(false);
            $table->boolean('fertilized_today')->default(false);
            $table->unsignedTinyInteger('days_without_water')->default(0);
            $table->timestamp('last_watered_at')->nullable();
            $table->timestamp('last_fertilized_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
