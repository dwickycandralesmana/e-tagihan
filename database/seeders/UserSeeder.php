<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id'       => Str::uuid(),
            'name'     => 'Admin',
            'email'    => 'admin@e-tagihan.com',
            'password' => bcrypt('Admin123!@'),
            'type'     => 'admin',
        ]);
    }
}
