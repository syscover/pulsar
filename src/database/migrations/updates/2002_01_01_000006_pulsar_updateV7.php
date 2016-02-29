<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class PulsarUpdateV7 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(Schema::hasColumn('001_017_text', 'lang_017'))
		{
			$key = DB::select(DB::raw('SHOW KEYS FROM 001_017_text WHERE Key_name=\'fk01_001_017_text\''));

			if($key != null)
			{
				Schema::table('001_017_text', function (Blueprint $table) {
					$table->dropForeign('fk01_001_017_text');
					$table->dropIndex('fk01_001_017_text');
				});

				Schema::table('001_017_text', function (Blueprint $table) {
					// rename column, function renameColumn doesn't work
					DB::select(DB::raw('ALTER TABLE 001_017_text CHANGE lang_017 lang_id_017 VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL'));
					$table->foreign('lang_id_017', 'fk01_001_017_text')->references('id_001')->on('001_001_lang')
						->onDelete('restrict')->onUpdate('cascade');
				});
			}
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}

}