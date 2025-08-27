<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Option;




/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Creature>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake()->name(),
            'price' => random_int(0, 120),
            'stock' => random_int(0, 50),
            'description' => $this->faker->paragraph(1),
            'archived' => random_int(0, 1),
            'option_id' => Option::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
        ];
    }

}
