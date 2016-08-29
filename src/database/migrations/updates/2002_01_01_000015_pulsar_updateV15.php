<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarUpdateV15 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    $response = \Syscover\Pulsar\Models\Resource::find('admin-report');

        if($response == null)
        {
            \Syscover\Pulsar\Models\Resource::create([
                'id_007'            => 'admin-report',
                'name_007'          => 'Reports',
                'package_id_007'    => '2'
            ]);
        }
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}