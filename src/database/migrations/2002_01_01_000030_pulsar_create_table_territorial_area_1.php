<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableTerritorialArea1 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_003_territorial_area_1', function($table) {
                $table->engine = 'InnoDB';
                $table->string('id_003', 6)->primary();
                $table->string('country_003', 2)->index();
                $table->string('name_003', 50);

                $table->foreign('country_003')->references('id_002')->on('001_002_country')
                        ->onDelete('restrict')->onUpdate('cascade');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_003_territorial_area_1');
	}

}