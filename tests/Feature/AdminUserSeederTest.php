<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_default_admin_account(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@eroganizer.com',
            'role' => 'admin',
        ]);

        $admin = User::where('email', 'admin@eroganizer.com')->first();
        $this->assertNotNull($admin);
        $this->assertTrue(password_verify('password123', $admin->password));
    }
}
