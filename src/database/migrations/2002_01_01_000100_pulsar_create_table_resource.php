<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableResource extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('001_007_resource'))
		{
			Schema::create('001_007_resource', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->string('id_007', 30)->primary();
				$table->string('name_007', 50);
				$table->integer('package_007')->unsigned();

				$table->foreign('package_007', 'fk01_001_007_resource')->references('id_012')->on('001_012_package')
					->onDelete('restrict')->onUpdate('cascade');
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
            Schema::drop('001_007_resource');
	}

}