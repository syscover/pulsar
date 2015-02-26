<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\Profile;

class ProfileTableSeeder extends Seeder
{
    public function run()
    {
        Profile::insert([
            ['id_006' => '1','name_006' => 'Administrador']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="ProfileTableSeeder"
 */