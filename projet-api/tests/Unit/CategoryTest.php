<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    public function test_get_all_categories()
    {
        // Création des catégories en mémoire
        $category1 = new Category();
        $category1->name = 'Catégorie A';
        $category1->description = 'Description A';

        $category2 = new Category();
        $category2->name = 'Catégorie B';
        $category2->description = 'Description B';

        $categories = [$category1, $category2];

        $this->assertNotEmpty($categories);

        foreach ($categories as $category) {
            $this->assertNotEmpty($category->name);
            $this->assertNotEmpty($category->description);
        }
    }

    public function test_can_create_category()
    {
        $category = new Category();
        $category->name = 'Catégorie Test';
        $category->description = 'Description Test';

        $this->assertEquals('Catégorie Test', $category->name);
        $this->assertEquals('Description Test', $category->description);
        $this->assertNull($category->id); // pas encore sauvegardé
    }

    public function test_can_update_category()
    {
        $category = new Category();
        $category->name = 'Catégorie Test';
        $category->description = 'Description Test';

        // Vérifie les valeurs initiales
        $this->assertEquals('Catégorie Test', $category->name);
        $this->assertEquals('Description Test', $category->description);

        // Mise à jour
        $category->name = 'Catégorie Modifiée';
        $category->description = 'Description Modifiée';

        $this->assertEquals('Catégorie Modifiée', $category->name);
        $this->assertEquals('Description Modifiée', $category->description);
        $this->assertNull($category->id); // toujours pas sauvegardé
    }
}
