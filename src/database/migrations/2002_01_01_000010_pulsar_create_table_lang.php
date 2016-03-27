<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableLang extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_001_lang'))
		{
			Schema::create('001_001_lang', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->string('id_001', 2)->primary();
				$table->string('name_001');
				$table->string('image_001')->nullable();
				$table->smallInteger('sorting_001')->unsigned();
				$table->boolean('base_001');
				$table->boolean('active_001');
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
		if (Schema::hasTable('001_001_lang'))
		{
			Schema::drop('001_001_lang');
		}
	}
}