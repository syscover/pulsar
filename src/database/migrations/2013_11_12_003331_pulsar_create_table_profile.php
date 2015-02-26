<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableProfile extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_006_profile', function($table) {
                $table->engine = 'InnoDB';
                $table->increments('id_006')->unsigned();
                $table->string('name_006', 50);
            });
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