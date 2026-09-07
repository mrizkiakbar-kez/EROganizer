<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('borrowing_details');
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('books');
        Schema::dropIfExists('categories');
    }

    public function down(): void
    {
        // Library tables are retired and are not recreated by this application.
    }
};