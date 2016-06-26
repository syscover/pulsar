<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PulsarUpdateV10 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasColumn('001_027_field_value', 'counter_027'))
		{
			Schema::table('001_027_field_value', function (Blueprint $table) {

				// delete primary keys
				Schema::table('001_027_field_value', function (Blueprint $table) {
					$table->dropPrimary('PRIMARY');
				});

				// change id to string value
				DB::select(DB::raw('ALTER TABLE 001_027_field_value CHANGE id_027 id_027 VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL'));

				// change column value_027 to name_027
				DB::select(DB::raw('ALTER TABLE 001_027_field_value CHANGE value_027 name_027 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL'));

				// set primary key
				$table->primary(['id_027', 'lang_id_027', 'field_id_027'], 'pk01_001_027_field_value');

				// create counter column
				$table->integer('counter_027')->unsigned()->nullable()->after('field_id_027');
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