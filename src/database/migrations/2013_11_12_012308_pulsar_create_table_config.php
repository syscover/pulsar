<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableConfig extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_018_config', function($table){
                $table->engine = 'InnoDB';
                $table->string('id_018',50)->primary();
                $table->text('value_018');
                $table->timestamps();
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_018_config');
	}

}