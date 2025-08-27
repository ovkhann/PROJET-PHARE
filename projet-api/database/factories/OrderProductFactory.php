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
class OrderProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'product_id' => random_int(1, Product::count()),
            'order_id' => random_int(1, Order::count()),
            
        ];
    }

}
