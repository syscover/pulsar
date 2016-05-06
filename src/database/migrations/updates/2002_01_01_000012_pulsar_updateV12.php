<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PulsarUpdateV12 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(Schema::hasColumn('001_026_field', 'field_type_026'))
		{
			// change column name
			DB::select(DB::raw('ALTER TABLE 001_026_field CHANGE field_type_026 field_type_id_026 TINYINT(3) UNSIGNED NOT NULL'));
		}

		if(Schema::hasColumn('001_026_field', 'field_type_text_026'))
		{
			// change column name
			DB::select(DB::raw('ALTER TABLE 001_026_field CHANGE field_type_text_026 field_type_name_026 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL'));
		}

		if(Schema::hasColumn('001_026_field', 'data_type_026'))
		{
			// change column name
			DB::select(DB::raw('ALTER TABLE 001_026_field CHANGE data_type_026 data_type_id_026 TINYINT(3) UNSIGNED NOT NULL'));
		}

		if(Schema::hasColumn('001_026_field', 'data_type_text_026'))
		{
			// change column name
			DB::select(DB::raw('ALTER TABLE 001_026_field CHANGE data_type_text_026 data_type_name_026 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL'));
		}

		if(Schema::hasColumn('001_028_field_result', 'data_type_028'))
		{
			// change column name
			DB::select(DB::raw('ALTER TABLE 001_028_field_result CHANGE data_type_028 data_type_type_028 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL'));
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}