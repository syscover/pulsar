<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\Lang;

class PulsarLangTableSeeder extends Seeder
{
    public function run()
    {
        Lang::insert([
            ['id_001' => 'de',  'name_001' => 'Alemán',     'image_001' => 'de.jpg',    'sorting_001' => '0',   'base_001' => '0',  'active_001' => '1'],
            ['id_001' => 'en',  'name_001' => 'Inglés',     'image_001' => 'en.jpg',    'sorting_001' => '0',   'base_001' => '0',  'active_001' => '1'],
            ['id_001' => 'es',  'name_001' => 'Español',    'image_001' => 'es.jpg',    'sorting_001' => '1',   'base_001' => '1',  'active_001' => '1'],
            ['id_001' => 'fr',  'name_001' => 'Francés',    'image_001' => 'fr.jpg',    'sorting_001' => '0',   'base_001' => '0',  'active_001' => '1']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PulsarLangTableSeeder"
 */