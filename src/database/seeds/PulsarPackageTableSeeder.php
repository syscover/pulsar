<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\Package;

class PulsarPackageTableSeeder extends Seeder
{
    public function run()
    {
        Package::insert([
            ['id_012' => '1',   'name_012' => 'Pulsar',                         'folder_012' => '',             'sorting_012' => 1,     'active_012' => '1'],
            ['id_012' => '2',   'name_012' => 'Pulsar Administration Package',  'folder_012' => 'pulsar',       'sorting_012' => 2,     'active_012' => '1'],
            ['id_012' => '9',   'name_012' => 'Marketplace Package',            'folder_012' => 'marketplace',  'sorting_012' => 9,     'active_012' => '0']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PulsarPackageTableSeeder"
 */