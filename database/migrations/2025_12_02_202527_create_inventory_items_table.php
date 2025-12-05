<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This table is no longer needed as we're using user_inventories instead
        // Keeping the migration for reference but it's empty
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

