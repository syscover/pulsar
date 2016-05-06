<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTablePackage extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_012_package'))
		{
			Schema::create('001_012_package', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->increments('id_012')->unsigned();
				$table->string('name_012');
				$table->string('folder_012');
				$table->boolean('active_012');
				$table->integer('sorting_012')->unsigned();
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
		if (Schema::hasTable('001_012_package'))
		{
			Schema::drop('001_012_package');
		}
	}
}