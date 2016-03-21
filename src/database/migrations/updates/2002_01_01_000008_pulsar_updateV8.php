<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Syscover\Pulsar\Models\Resource;
use \Syscover\Pulsar\Models\Package;

class PulsarUpdateV8 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(Resource::where('id_007', 'admin-attachment-mime')->count() == 0 && Package::where('id_012', 2)->count() == 1)
		{
			Resource::create([
				'id_007' 		=> 'admin-attachment-mime',
				'name_007' 		=> 'Attachments - Attachment Mime',
				'package_007' 	=> '2'
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