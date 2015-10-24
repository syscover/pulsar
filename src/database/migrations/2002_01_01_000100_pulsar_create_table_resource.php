<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableResource extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_007_resource', function($table) {
                $table->engine = 'InnoDB';
                $table->string('id_007', 30)->primary();
                $table->string('name_007', 50);
                $table->integer('package_007')->unsigned();

                $table->foreign('package_007')->references('id_012')->on('001_012_package')
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
            Schema::drop('001_007_resource');
	}

}