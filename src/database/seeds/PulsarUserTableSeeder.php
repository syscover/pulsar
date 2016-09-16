<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\User;

class PulsarUserTableSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'id_010'                => 1,
                'name_010'              => 'Pulsar',
                'surname_010'           => 'Pulsar',
                'lang_id_010'           => 'en',
                'email_010'             => 'adminn@pulsar.local',
                'profile_id_010'        => '1',
                'access_010'            => '1',
                'user_010'              => 'admin@pulsar.local',
                'password_010'          => '$2y$10$3eFZAd31wPmg2mMZB/CZ4.CkcZKY9xr7A3Z9ou6mp7OkSIc3Qo.yW',
                'remember_token_010'    => null,
                'created_at'            => date("Y-m-d H:i:s"),
                'updated_at'            => date("Y-m-d H:i:s")
            ]
        ]);
    }
}

/*
 * Comand to run:
 * php artisan db:seed --class="PulsarUserTableSeeder"
 */