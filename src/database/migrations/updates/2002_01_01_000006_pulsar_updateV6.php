<?php

use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class PulsarUpdateV6 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(Schema::hasColumn('001_027_field_value', 'name_027'))
		{
			DB::select('ALTER TABLE 001_027_field_value CHANGE name_027 value_027 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL');
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}

}