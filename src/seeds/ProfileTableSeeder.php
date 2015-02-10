<?php
class ProfileTableSeeder extends Seeder
{
    public function run()
    {   
        //DATA 001_006_profile
        DB::insert(DB::raw("INSERT INTO `001_006_profile` (`id_006`, `name_006`) VALUES
            (1, 'Administrador');"));
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="ProfileTableSeeder"
 */