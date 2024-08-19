<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = [
            'brand_logo' => 'logo.png',
        ];

        foreach ($setting as $key => $value) {
            $string = explode('_', $key);
            $string = implode(' ', $string);
            $string = ucwords($string);

            \App\Models\Setting::updateOrCreate(['key' => $key, 'description' => $string, 'value' => $value]);
        }
    }
}
