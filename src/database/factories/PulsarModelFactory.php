<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Syscover\Pulsar\Models\User::class, function (Faker\Generator $faker) {
    return [
        'id_010'                => 1000,
        'lang_010'              => 'en',
        'profile_010'           => 1,
        'access_010'            => true,
        'user_010'              => 'test',
        'password_010'          => bcrypt('123456'),
        'email_010'             => 'info@test.local',
        'name_010'              => 'Test',
        'surname_010'           => 'Pulsar',
    ];
});
