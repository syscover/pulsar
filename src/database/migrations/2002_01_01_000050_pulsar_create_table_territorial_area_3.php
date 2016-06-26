<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableTerritorialArea3 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_005_territorial_area_3'))
		{
			Schema::create('001_005_territorial_area_3', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->string('id_005', 10);
				$table->string('country_id_005', 2);
				$table->string('territorial_area_1_id_005', 6);
				$table->string('territorial_area_2_id_005', 10);
				$table->string('name_005');

				$table->primary('id_005', 'pk01_001_005_territorial_area_3');
				$table->foreign('country_id_005', 'fk01_001_005_territorial_area_3')->references('id_002')->on('001_002_country')
					->onDelete('restrict')->onUpdate('cascade');
				$table->foreign('territorial_area_1_id_005', 'fk02_001_005_territorial_area_3')->references('id_003')->on('001_003_territorial_area_1')
					->onDelete('restrict')->onUpdate('cascade');
				$table->foreign('territorial_area_2_id_005', 'fk03_001_005_territorial_area_3')->references('id_004')->on('001_004_territorial_area_2')
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
		if (Schema::hasTable('001_005_territorial_area_3'))
		{
			Schema::drop('001_005_territorial_area_3');
		}
	}
}