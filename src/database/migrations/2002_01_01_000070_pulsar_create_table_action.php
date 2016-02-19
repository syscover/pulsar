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
		if(!Schema::hasTable('001_008_action'))
		{
			Schema::create('001_008_action', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->string('id_008', 25)->primary();
				$table->string('name_008');
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