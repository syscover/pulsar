<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableAction extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_008_action', function($table){
                $table->engine = 'InnoDB';
                $table->string('id_008',25)->primary();
                $table->string('name_008', 50);
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_008_action');
	}

}