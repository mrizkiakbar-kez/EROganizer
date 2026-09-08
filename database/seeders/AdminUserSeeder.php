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
        if ($legacyAdmin && ! User::where('email', 'admin@ub.ac.id')->exists()) {
            $legacyAdmin->update(['email' => 'admin@ub.ac.id']);
        }

        User::updateOrCreate(
            ['email' => 'admin@ub.ac.id'],
            [
                'name' => 'Admin',
                'email' => 'admin@ub.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}