<?php

namespace Database\Seeders;

use App\Models\Option;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $size = ['S', 'M', 'L', 'XL'];
        foreach ($size as $value) {
            Option::create([
                'size' => $value,
            ]);
        }
    }
}
