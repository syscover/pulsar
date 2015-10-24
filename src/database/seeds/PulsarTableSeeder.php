<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PulsarTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call(LangTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(TerritorialArea1TableSeeder::class);
        $this->call(TerritorialArea2TableSeeder::class);
        $this->call(ActionTableSeeder::class);
        $this->call(PackageTableSeeder::class);
        $this->call(ProfileTableSeeder::class);
        $this->call(ResourceTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PulsarTableSeeder"
 */