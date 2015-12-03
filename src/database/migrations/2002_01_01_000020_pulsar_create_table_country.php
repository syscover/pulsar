<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableCountry extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_002_country', function(Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->string('id_002',2);
                $table->string('lang_002',2);
                $table->string('name_002',100);
                $table->smallInteger('sorting_002')->unsigned();
                $table->string('prefix_002',5)->nullable();
                $table->string('territorial_area_1_002',50)->nullable();
                $table->string('territorial_area_2_002',50)->nullable();
                $table->string('territorial_area_3_002',50)->nullable();

                $table->string('data_lang_002',255)->nullable();
                $table->text('data_002')->nullable();

                $table->primary(['id_002', 'lang_002']);
                $table->foreign('lang_002', 'fk01_001_002_country')->references('id_001')->on('001_001_lang')
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
            Schema::drop('001_002_country');
	}

}