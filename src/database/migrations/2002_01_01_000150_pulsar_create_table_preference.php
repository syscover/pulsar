<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTablePreference extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('001_018_preference', function(Blueprint $table) {
			$table->string('id_018', 50)->primary();
            $table->integer('package_018')->unsigned();
			$table->text('value_018')->nullable();
			$table->timestamps();

            $table->foreign('package_018', 'fk01_001_018_preference')->references('id_012')->on('001_012_package')
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
		Schema::drop('001_018_preference');
	}

}