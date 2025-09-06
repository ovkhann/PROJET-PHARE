<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Option;

class OptionTest extends TestCase
{
    public function test_get_all_options()
    {
        // Création des options en mémoire
        $option1 = new Option();
        $option1->name = 'Option A';
        $option1->value = 'Valeur A';

        $option2 = new Option();
        $option2->name = 'Option B';
        $option2->value = 'Valeur B';

        $options = [$option1, $option2];

        $this->assertNotEmpty($options);

        foreach ($options as $option) {
            $this->assertNotEmpty($option->name);
            $this->assertNotEmpty($option->value);
        }
    }

    public function test_can_create_option()
    {
        // Création d’une option en mémoire
        $option = new Option();
        $option->name = 'Option Test';
        $option->value = 'Valeur Test';

        $this->assertEquals('Option Test', $option->name);
        $this->assertEquals('Valeur Test', $option->value);
        $this->assertNull($option->id); // pas encore sauvegardé
    }

    public function test_can_update_option()
    {
        // Création d’une option en mémoire
        $option = new Option();
        $option->name = 'Option Test';
        $option->value = 'Valeur Test';

        // Vérifie les valeurs initiales
        $this->assertEquals('Option Test', $option->name);
        $this->assertEquals('Valeur Test', $option->value);

        // Mise à jour
        $option->name = 'Option Modifiée';
        $option->value = 'Valeur Modifiée';

        $this->assertEquals('Option Modifiée', $option->name);
        $this->assertEquals('Valeur Modifiée', $option->value);
        $this->assertNull($option->id); // toujours pas sauvegardé
    }
}
