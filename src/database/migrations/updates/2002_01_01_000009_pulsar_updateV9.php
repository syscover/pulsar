<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PulsarUpdateV9 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasColumn('001_028_field_result', 'data_type_type_028'))
		{
			Schema::table('001_028_field_result', function (Blueprint $table) {
				// rename column
				DB::select(DB::raw('ALTER TABLE 001_028_field_result CHANGE type_028 data_type_type_028 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL'));
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