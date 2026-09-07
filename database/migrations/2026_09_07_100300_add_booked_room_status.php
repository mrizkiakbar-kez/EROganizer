<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('rooms', function (Blueprint $table) {
                $table->enum('status', ['available', 'booked', 'unavailable'])->default('available')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('rooms', function (Blueprint $table) {
                $table->enum('status', ['available', 'unavailable'])->default('available')->change();
            });
        }
    }
};