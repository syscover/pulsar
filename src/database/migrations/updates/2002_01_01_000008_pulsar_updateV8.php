<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Syscover\Pulsar\Models\Resource;

class PulsarUpdateV8 extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
		if(Resource::where('id_007', 'admin-attachment-mime')->count() == 0)
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