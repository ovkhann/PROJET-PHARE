<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_get_all_products()
    {
        // Simule une collection de produits
        $products = [
            new Product(['name' => 'Produit A', 'price' => 50]),
            new Product(['name' => 'Produit B', 'price' => 75]),
        ];

        // Vérifie que la collection contient au moins un produit
        $this->assertNotEmpty($products);

        // Vérifie que chaque produit a un nom et un prix
        foreach ($products as $product) {
            $this->assertNotEmpty($product->name);
            $this->assertIsNumeric($product->price);
        }
    }

    public function test_create_product()
    {
        // Création d’un produit “en mémoire”
        $product = new Product();
        $product->name = 'Produit Test';
        $product->price = 99.99;

        // Vérifie que le produit a bien les bonnes valeurs
        $this->assertEquals('Produit Test', $product->name);
        $this->assertEquals(99.99, $product->price);

        // Vérifie que l’ID n’existe pas encore (pas de DB)
        $this->assertNull($product->id);
    }

    public function test_update_product()
    {
        // Création d’un produit “en mémoire”
        $product = new Product();
        $product->name = 'Produit Test';
        $product->price = 99.99;

        // Vérifie les valeurs initiales
        $this->assertEquals('Produit Test', $product->name);
        $this->assertEquals(99.99, $product->price);

        // 🔹 Mise à jour des valeurs
        $product->name = 'Produit Modifié';
        $product->price = 149.99;

        // Vérifie que la mise à jour a bien eu lieu
        $this->assertEquals('Produit Modifié', $product->name);
        $this->assertEquals(149.99, $product->price);

        // L’ID reste null car on n’a pas sauvegardé
        $this->assertNull($product->id);
    }
}
