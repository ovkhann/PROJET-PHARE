<?php

namespace Database\Seeders;

use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $name = ['T-shirt', 'Hoodie', 'Pant', 'Beanie'];
        foreach ($name as $value) {
            Category::create([
                'name' => $value,
            ]);
        }
    }
}
