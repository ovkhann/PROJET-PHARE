<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Option;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Enums\RoleEnum;
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
            'name' => 'VESTE PAPILLON NAVY BLUE',
            'price' => 59.99,
            'stock' => 50,
            'description' => 'LOREM ...',
            'archived' => 0,
            'category_id' => 2,
            'picture' => '/images/products/veste-papillon.jpg',
        ]);

        $product2 = Product::create([
            'name' => 'JOGGING PAPILLON NAVY BLUE',
            'price' => 59.99,
            'stock' => 50,
            'description' => 'LOREM LOREMLOREMKLOREMLREORLERLEMRER,LE  KNRK ENRJENJ RBNEJRNENRKJEN RNEKR N',
            'archived' => 0,
            'category_id' => 3,
            'picture' => '/images/products/jogging-papillon.jpg',
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
