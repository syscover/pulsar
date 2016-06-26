<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableAction extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_008_action'))
		{
			Schema::create('001_008_action', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				
				$table->string('id_008', 25);
				$table->string('name_008');

				$table->primary('id_008', 'pk01_001_008_action');
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
		if (Schema::hasTable('001_008_action'))
		{
			Schema::drop('001_008_action');
		}
	}
}