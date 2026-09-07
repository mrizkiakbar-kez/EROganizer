<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacyAdmin = User::where('email', 'admin@ub.com')->first();
        if ($legacyAdmin && ! User::where('email', 'admin@eroganizer.com')->exists()) {
            $legacyAdmin->update(['email' => 'admin@eroganizer.com']);
        }

        User::updateOrCreate(
            ['email' => 'admin@eroganizer.com'],
            [
                'name' => 'Admin EROganizer',
                'email' => 'admin@eroganizer.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
    }
}