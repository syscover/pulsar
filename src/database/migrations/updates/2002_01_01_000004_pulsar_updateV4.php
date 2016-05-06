<?php

use Illuminate\Database\Schema\Blueprint;
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
		if(! Schema::hasColumn('001_026_field', 'field_type_name_026'))
		{
			Schema::table('001_026_field', function (Blueprint $table) {
				$table->string('field_type_name_026', 100)->after('field_type_id_026');
				$table->string('data_type_name_026', 100)->after('data_type_id_026');
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