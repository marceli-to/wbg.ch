<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The newsletter feature has been removed; drop the subscriber list.
     */
    public function up(): void
    {
        Schema::dropIfExists('subscribers');
    }

    public function down(): void
    {
        //
    }
};
