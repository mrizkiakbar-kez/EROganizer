<?php

namespace Tests\Feature;

use Database\Seeders\MemberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberSeederIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_seeder_can_run_more_than_once_without_duplicate_codes(): void
    {
        $this->seed(MemberSeeder::class);
        $this->seed(MemberSeeder::class);

        $this->assertDatabaseCount('members', 2);
        $this->assertDatabaseHas('members', ['kode_anggota' => 'MBR001']);
        $this->assertDatabaseHas('members', ['kode_anggota' => 'MBR002']);
    }
}
