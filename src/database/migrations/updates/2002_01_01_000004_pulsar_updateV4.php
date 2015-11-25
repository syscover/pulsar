<?php

use Illuminate\Database\Migrations\Migration;


class PulsarUpdateV4 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasColumn('001_026_field', 'field_type_text_026'))
		{
			Schema::table('001_026_field', function ($table) {
				$table->string('field_type_text_026', 100)->after('field_type_026');
				$table->string('data_type_text_026', 100)->after('data_type_026');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}

}