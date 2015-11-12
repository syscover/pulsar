<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PulsarTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call(PulsarLangTableSeeder::class);
        $this->call(PulsarCountryTableSeeder::class);
        $this->call(PulsarTerritorialArea1TableSeeder::class);
        $this->call(PulsarTerritorialArea2TableSeeder::class);
        $this->call(PulsarActionTableSeeder::class);
        $this->call(PulsarPackageTableSeeder::class);
        $this->call(PulsarProfileTableSeeder::class);
        $this->call(PulsarResourceTableSeeder::class);
        $this->call(PulsarUserTableSeeder::class);
        $this->call(PulsarPermissionTableSeeder::class);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PulsarTableSeeder"
 */