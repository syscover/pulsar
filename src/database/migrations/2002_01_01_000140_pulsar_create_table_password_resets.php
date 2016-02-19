<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTablePasswordResets extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('001_021_password_resets'))
		{
			Schema::create('001_021_password_resets', function (Blueprint $table) {
				$table->string('email')->index();
				$table->string('token')->index();
				$table->timestamp('created_at');
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
		if (Schema::hasTable('001_021_password_resets'))
		{
			Schema::drop('001_021_password_resets');
		}
	}
}