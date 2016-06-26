<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableTerritorialArea2 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_004_territorial_area_2'))
		{
			Schema::create('001_004_territorial_area_2', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				
				$table->string('id_004', 10);
				$table->string('country_id_004', 2);
				$table->string('territorial_area_1_id_004', 6);
				$table->string('name_004');
				
				$table->foreign('country_id_004', 'fk01_001_004_territorial_area_2')->references('id_002')->on('001_002_country')
					->onDelete('restrict')->onUpdate('cascade');
				$table->foreign('territorial_area_1_id_004', 'fk02_001_004_territorial_area_2')->references('id_003')->on('001_003_territorial_area_1')
					->onDelete('restrict')->onUpdate('cascade');
				
				$table->primary('id_004', 'pk01_001_004_territorial_area_2');
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
		if (Schema::hasTable('001_004_territorial_area_2'))
		{
			Schema::drop('001_004_territorial_area_2');
		}
	}
}