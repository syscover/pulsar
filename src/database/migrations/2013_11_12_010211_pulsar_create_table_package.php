<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTablePackage extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_012_package', function($table){
                $table->engine = 'InnoDB';
                $table->increments('id_012')->unsigned();
                $table->string('name_012', 50);
				$table->string('folder_012', 50);
                $table->boolean('active_012');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_012_package');
	}

}