<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            ['id_010' => '1','remember_token_010' => NULL,'lang_010' => 'es','profile_010' => '1','access_010' => '1','user_010' => 'admin@pulsar.local','password_010' => '$2y$10$3eFZAd31wPmg2mMZB/CZ4.CkcZKY9xr7A3Z9ou6mp7OkSIc3Qo.yW','email_010' => 'adminn@pulsar.local','name_010' => 'Pulsar','surname_010' => 'Pulsar','created_at' => '0000-00-00 00:00:00','updated_at' => '0000-00-00 00:00:00']
        ]);
    }
}

/*
 * Comand to run:
 * php artisan db:seed --class="UserTableSeeder"
 */