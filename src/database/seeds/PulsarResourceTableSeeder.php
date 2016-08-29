<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\Resource;

class PulsarResourceTableSeeder extends Seeder {

    public function run()
    {
        Resource::insert([
            ['id_007' => 'admin',                       'name_007' => 'Administration Package',                 'package_id_007' => '2'],
            ['id_007' => 'admin-dashboard',             'name_007' => 'Dashboard',                              'package_id_007' => '2'],
            ['id_007' => 'admin-country',               'name_007' => 'Countries',                              'package_id_007' => '2'],
            ['id_007' => 'admin-country-at1',           'name_007' => 'Countries -- Territorial Areas 1',       'package_id_007' => '2'],
            ['id_007' => 'admin-country-at2',           'name_007' => 'Countries -- Territorial Areas 2',       'package_id_007' => '2'],
            ['id_007' => 'admin-country-at3',           'name_007' => 'Countries -- Territorial Areas 3',       'package_id_007' => '2'],
            ['id_007' => 'admin-cron',                  'name_007' => 'Cron task',                              'package_id_007' => '2'],
            ['id_007' => 'admin-email-account',         'name_007' => 'Email accounts',                         'package_id_007' => '2'],
            ['id_007' => 'admin-google-services',       'name_007' => 'Google Services',                        'package_id_007' => '2'],
            ['id_007' => 'admin-lang',                  'name_007' => 'Languages',                              'package_id_007' => '2'],
            ['id_007' => 'admin-package',               'name_007' => 'Packages',                               'package_id_007' => '2'],
            ['id_007' => 'admin-perm',                  'name_007' => 'Permissions',                            'package_id_007' => '2'],
            ['id_007' => 'admin-perm-action',           'name_007' => 'Permissions -- Actions',                 'package_id_007' => '2'],
            ['id_007' => 'admin-perm-perm',             'name_007' => 'Permissions -- Permissions',             'package_id_007' => '2'],
            ['id_007' => 'admin-perm-profile',          'name_007' => 'Permissions -- Profiles',                'package_id_007' => '2'],
            ['id_007' => 'admin-perm-resource',         'name_007' => 'Permissions -- Resources',               'package_id_007' => '2'],
            ['id_007' => 'admin-user',                  'name_007' => 'Users',                                  'package_id_007' => '2'],
            ['id_007' => 'admin-attachment',            'name_007' => 'Attachments',                            'package_id_007' => '2'],
            ['id_007' => 'admin-attachment-family',     'name_007' => 'Attachments - Attachment Families',      'package_id_007' => '2'],
            ['id_007' => 'admin-attachment-mime',       'name_007' => 'Attachments - Attachments Mimes',        'package_id_007' => '2'],
            ['id_007' => 'admin-attachment-library',    'name_007' => 'Attachments - Library',                  'package_id_007' => '2'],
            ['id_007' => 'admin-field',                 'name_007' => 'Custom fields',                          'package_id_007' => '2'],
            ['id_007' => 'admin-field-value',           'name_007' => 'Custom fields - Values',                 'package_id_007' => '2'],
            ['id_007' => 'admin-field-group',           'name_007' => 'Custom fields - Groups',                 'package_id_007' => '2'],
            ['id_007' => 'admin-report',                'name_007' => 'Reports',                                'package_id_007' => '2'],
            ['id_007' => 'pulsar',                      'name_007' => 'Pulsar',                                 'package_id_007' => '1']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PulsarResourceTableSeeder"
 */