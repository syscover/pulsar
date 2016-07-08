<?php

use Illuminate\Database\Seeder;
use Syscover\Pulsar\Models\CronJob;

class PulsarCronjobTableSeeder extends Seeder {

    public function run()
    {   
        CronJob::insert([
            ['name_011' => 'Check to create advanced search exports',     'package_id_011' => 2,     'cron_expression_011' => '*/2 * * * *',     'key_011' => '10',  'last_run_011' => 0,    'next_run_011' => 0,    'active_011' => 1],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="PulsarCronjobTableSeeder"
 */