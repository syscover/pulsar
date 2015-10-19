<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\Package;

class PackageTableSeeder extends Seeder
{
    public function run()
    {
        Package::insert([
            ['id_012' => '1',   'name_012' => 'Pulsar',                         'folder_012' => '',             'active_012' => '1'],
            ['id_012' => '2',   'name_012' => 'Pulsar Administration Package',  'folder_012' => 'pulsar',       'active_012' => '1'],
            ['id_012' => '3',   'name_012' => 'Comunik Package',                'folder_012' => 'comunik',      'active_012' => '0'],
            ['id_012' => '4',   'name_012' => 'Forms Package',                  'folder_012' => 'forms',        'active_012' => '0'],
            ['id_012' => '5',   'name_012' => 'Updater Package',                'folder_012' => 'updater',      'active_012' => '0'],
            ['id_012' => '6',   'name_012' => '--',                             'folder_012' => '--',           'active_012' => '0'],
            ['id_012' => '7',   'name_012' => 'Hotels Package',                 'folder_012' => 'hotels',       'active_012' => '0'],
            ['id_012' => '8',   'name_012' => 'Octopus Package',                'folder_012' => 'octopus',      'active_012' => '0'],
            ['id_012' => '9',   'name_012' => 'Marketplace Package',            'folder_012' => 'marketplace',  'active_012' => '0'],
            ['id_012' => '10',  'name_012' => '---',                            'folder_012' => '---',          'active_012' => '0'],
            ['id_012' => '11',  'name_012' => 'Booking Package',                'folder_012' => 'booking',      'active_012' => '0'],
            ['id_012' => '12',  'name_012' => 'CRM Package',                    'folder_012' => 'crm',          'active_012' => '0'],
            ['id_012' => '13',  'name_012' => 'CMS Package',                    'folder_012' => 'cms',          'active_012' => '0'],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PackageTableSeeder"
 */