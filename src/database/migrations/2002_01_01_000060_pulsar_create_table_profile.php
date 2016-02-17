<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableProfile extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('001_006_profile'))
		{
			Schema::create('001_006_profile', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->increments('id_006')->unsigned();
				$table->string('name_006', 50);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_006_profile');
	}

}