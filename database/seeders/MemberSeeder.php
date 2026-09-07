<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'kode_anggota' => 'MBR001',
                'nama' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password',
                'telepon' => '081234567890',
                'alamat' => 'Jalan Contoh No. 1, Malang',
            ],
            [
                'kode_anggota' => 'MBR002',
                'nama' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => 'password',
                'telepon' => '081234567891',
                'alamat' => 'Jalan Contoh No. 2, Malang',
            ],
        ];

        foreach ($members as $member) {
            Member::updateOrCreate(
                ['kode_anggota' => $member['kode_anggota']],
                [
                    'nama' => $member['nama'],
                    'email' => $member['email'],
                    'password' => $member['password'],
                    'telepon' => $member['telepon'],
                    'alamat' => $member['alamat'],
                    'role' => 'member',
                ]
            );
        }
    }
}
