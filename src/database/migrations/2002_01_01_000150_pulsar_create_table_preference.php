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
		if(! Schema::hasTable('001_018_preference'))
		{
			Schema::create('001_018_preference', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				
				$table->string('id_018', 50);
				$table->integer('package_id_018')->unsigned();
				$table->text('value_018')->nullable();
				$table->timestamps();
				
				$table->foreign('package_id_018', 'fk01_001_018_preference')
					->references('id_012')
					->on('001_012_package')
					->onDelete('restrict')
					->onUpdate('cascade');
				
				$table->primary('id_018', 'pk01_001_018_preference');
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
		if (Schema::hasTable('001_018_preference'))
		{
			Schema::drop('001_018_preference');
		}
	}
}