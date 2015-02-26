<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            ['id_010' => '1','remember_token_010' => NULL,'lang_010' => 'es','profile_010' => '1','access_010' => '1','user_010' => 'cpalacin@syscover.com','password_010' => '$2y$10$BWz3whvzxlfCJKVDYGAwguohPYQLSHlIBAa6.PCpE8Qdy/Ccc5jYq','email_010' => 'cpalacin@syscover.com','name_010' => 'José Carlos','surname_010' => 'Rodríguez Palacín','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00']
        ]);
    }
}

/*
 * Comand to run:
 * php artisan db:seed --class="UserTableSeeder"
 */