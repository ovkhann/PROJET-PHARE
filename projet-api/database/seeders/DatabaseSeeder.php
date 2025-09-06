<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Option;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Enums\RoleEnum;
use App\Models\Cart;
use App\Models\OrderProduct;
use App\Models\ProductUser;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'role' => RoleEnum::ROLE_USER->value,
            'email' => 'test@example.com',
            'password' => Hash::make('Test123'),
            'street_name' => 'LRSY',
            'billing_address' => 31,
            'delivery_address' => 13,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $this->call([
            CategorySeeder::class,
            OptionSeeder::class,
        ]);

        /* **************************************************************************** */

        // Création des produits
        $product1 = Product::create([
            'name' => 'VESTE PAPILLON - NAVY BLUE',
            'price' => 59.99,
            'stock' => 50,
            'description' => 'Elevate your wardrobe with the Butterfly Jacket in navy blue, a perfect blend of style and comfort. Crafted from high-quality, soft fabric, this jacket offers a tailored yet relaxed fit, ensuring ease of movement throughout the day. The elegant butterfly motif adds a subtle, modern flair, making it a standout piece for any casual or semi-casual outfit. Designed with practicality in mind, it features a full front zipper, comfortable cuffs, and side pockets for convenience. Versatile and chic, the Butterfly Jacket is ideal for layering over your everyday outfits or adding a touch of sophistication to your leisurewear.',
            'archived' => 0,
            'category_id' => 2,
            'picture' => [
                '/images/products/veste-papillon.jpg',
                '/images/products/jogging-papillon.jpg'
            ],
        ]);

        $product2 = Product::create([
            'name' => 'JOGGING PAPILLON - NAVY BLUE',
            'price' => 59.99,
            'stock' => 50,
            'description' => 'Combining comfort and style, the Butterfly Joggers in navy blue are perfect for lounging at home or staying active. Made from soft, breathable fabric, they offer a relaxed yet tailored fit, ensuring optimal freedom of movement. The elegant design is highlighted by a delicate butterfly motif, adding a unique and modern touch to this wardrobe staple. Featuring an elastic waistband with drawstring and practical side pockets, these joggers blend functionality with style. Versatile and chic, they’re ideal for casual outings, light workouts, or simply relaxing in comfort.',
            'archived' => 0,
            'category_id' => 3,
            'picture' => [
                '/images/products/jogging-papillon.jpg',
                '/images/products/veste-papillon.jpg',
            ],
        ]);




        $options = Option::all();

        // Attribution de toutes les options aux deux produits
        $product1->options()->sync($options->pluck('id')->toArray());
        $product2->options()->sync($options->pluck('id')->toArray());

        /* **************************************************************************** */

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Administrateur',
            'role' => RoleEnum::ROLE_ADMIN->value,
            'email' => 'admin@revolverealm.com',
            'password' => Hash::make('Test123'),
            'street_name' => 'LRSY',
            'billing_address' => 31,
            'delivery_address' => 13,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $users = User::factory(10)->create();
        // Product::factory(20)->create();
        Order::factory(5)->create();

        // ****************************************************************************

        // gestion du panier pour éviter les doublons
        $products = Product::all();

        foreach ($users as $user) {
            $randomProducts = $products->random(rand(1, 2));
            foreach ($randomProducts as $product) {
                ProductUser::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'quantity' => rand(1, 5),
                    ]
                );
            }
        }

        // OrderProduct::factory(5)->create();
    }
}
