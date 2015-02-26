<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Model::unguard();

        $this->call('LangTableSeeder');
        $this->call('CountryTableSeeder');
        $this->call('TerritorialArea1TableSeeder');
        $this->call('TerritorialArea2TableSeeder');
        $this->call('ActionTableSeeder');
        $this->call('PackageTableSeeder');
        $this->call('ProfileTableSeeder');
        $this->call('ResourceTableSeeder');
        $this->call('UserTableSeeder');
        $this->call('PermissionTableSeeder');
    }
}