<?php

use Illuminate\Support\Facades\DB;

class LangTableSeeder extends Seeder
{
    public function run()
    {
        DB::insert(DB::raw("INSERT INTO `001_001_lang` (`id_001`, `name_001`, `image_001`, `sorting_001`, `base_001`, `active_001`) VALUES
        ('de', 'Alemán',    'de.jpg', 0, 0, 1),
        ('en', 'Inglés',    'en.jpg', 0, 0, 1),
        ('es', 'Español',   'es.jpg', 1, 1, 1),
        ('fr', 'Francés',   'fr.jpg', 0, 0, 1);"));
        
    }
}

/*
 * Comand to run:
 * php artisan db:seed --class="LangTableSeeder"
 */