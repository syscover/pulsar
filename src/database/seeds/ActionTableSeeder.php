<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\Action;

class ActionTableSeeder extends Seeder
{
    public function run()
    {
        Action::insert([
            ['id_008' => 'access', 'name_008' => 'Access'],
            ['id_008' => 'create', 'name_008' => 'Create'],
            ['id_008' => 'delete', 'name_008' => 'Delete'],
            ['id_008' => 'edit', 'name_008' => 'Edit'],
            ['id_008' => 'show', 'name_008' => 'Show']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="ActionTableSeeder"
 */