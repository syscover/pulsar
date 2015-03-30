<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\Package;

class PackageTableSeeder extends Seeder
{
    public function run()
    {
        Package::insert([
            ['id_012' => '1','name_012' => 'Pulsar','active_012' => '1'],
            ['id_012' => '2','name_012' => 'Administration Package','active_012' => '1'],
            ['id_012' => '3','name_012' => 'Comunik Package','active_012' => '0'],
            ['id_012' => '4','name_012' => 'Wordpress Package','active_012' => '0'],
            ['id_012' => '5','name_012' => 'Updater Package','active_012' => '0'],
            ['id_012' => '6','name_012' => 'Forms Package','active_012' => '0'],
            ['id_012' => '7','name_012' => 'Hotels Package','active_012' => '0'],
            ['id_012' => '8','name_012' => 'Octopus Package','active_012' => '0'],
            ['id_012' => '9','name_012' => 'Marketplace Package','active_012' => '0'],
            ['id_012' => '10','name_012' => 'Mi financiacion Package','active_012' => '0'],
            ['id_012' => '11','name_012' => 'Booking Package','active_012' => '0'],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PackageTableSeeder"
 */