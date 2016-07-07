<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableAdvancedSearchTask extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_022_advanced_search_task'))
		{
			Schema::create('001_022_advanced_search_task', function ($table) {
				$table->engine = 'InnoDB';

				$table->increments('id_022')->unsigned();
				$table->integer('date_022')->unsigned();
				$table->integer('user_id_022')->unsigned();
				$table->string('model_022');
				$table->string('parameters_022')->nullable();
				$table->string('extension_file_022');
				$table->string('filename_022');

				$table->foreign('user_id_022', 'fk01_001_022_advanced_search_task')
					->references('id_010')
					->on('001_010_user')
					->onDelete('restrict')
					->onUpdate('cascade');
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
		if(Schema::hasTable('001_022_advanced_search_task'))
		{
			Schema::drop('001_022_advanced_search_task');
		}
	}
}