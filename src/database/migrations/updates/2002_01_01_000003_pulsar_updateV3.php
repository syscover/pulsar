<?php

use Illuminate\Database\Migrations\Migration;


class PulsarUpdateV3 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasColumn('001_012_package', 'sorting_012'))
		{
			Schema::table('001_012_package', function ($table) {
				$table->integer('sorting_012')->unsigned()->nullable()->after('active_012');
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