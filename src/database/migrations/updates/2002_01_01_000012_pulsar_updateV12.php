<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarUpdateV12 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasColumn('001_013_email_account', 'checking_uid_013'))
		{
			Schema::table('001_013_email_account', function (Blueprint $table) {
				$table->integer('checking_uid_013')->unsigned()->nullable()->after('last_check_uid_013');
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