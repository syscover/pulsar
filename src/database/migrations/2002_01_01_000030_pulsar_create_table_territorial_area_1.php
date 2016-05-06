<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableTerritorialArea1 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_003_territorial_area_1'))
		{
			Schema::create('001_003_territorial_area_1', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->string('id_003', 6)->primary();
				$table->string('country_003', 2)->index();
				$table->string('name_003');

				$table->foreign('country_003', 'fk01_001_003_territorial_area_1')->references('id_002')->on('001_002_country')
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
		if (Schema::hasTable('001_003_territorial_area_1'))
		{
			Schema::drop('001_003_territorial_area_1');
		}
	}
}