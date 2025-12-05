<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('plot_number');
            $table->boolean('planted')->default(false);
            $table->unsignedTinyInteger('stage')->default(0);
            $table->string('current_image')->default('./img/plot_empty.png');
            $table->boolean('watered_today')->default(false);
            $table->boolean('fertilized_today')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'plot_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
