<?php
class ActionTableSeeder extends Seeder
{
    public function run()
    {
        DB::insert(DB::raw("INSERT INTO `001_008_action` (`id_008`, `name_008`) VALUES
            ('access',      'Access'),
            ('show',        'Show'),
            ('create',      'Create'),
            ('delete',      'Delete'),
            ('edit',        'Edit');"));
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="ActionTableSeeder"
 */