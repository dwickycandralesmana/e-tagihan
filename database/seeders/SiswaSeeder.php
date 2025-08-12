<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = [
            'nis' => '123456789',
            'nama' => 'John Doe',
        ];

        foreach ($siswa as $key => $value) {
            \App\Models\Siswa::updateOrCreate(['nis' => $value['nis']], $value);
        }
    }
}
