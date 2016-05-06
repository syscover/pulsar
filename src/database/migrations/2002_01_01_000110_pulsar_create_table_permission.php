<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTablePermission extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_009_permission'))
		{
			Schema::create('001_009_permission', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->integer('profile_009')->unsigned();
				$table->string('resource_009', 30);
				$table->string('action_009', 25);

				$table->primary(['profile_009', 'resource_009', 'action_009']);
				$table->foreign('profile_009', 'fk01_001_009_permission')->references('id_006')->on('001_006_profile')
					->onDelete('cascade')->onUpdate('cascade');
				$table->foreign('resource_009', 'fk02_001_009_permission')->references('id_007')->on('001_007_resource')
					->onDelete('cascade')->onUpdate('cascade');
				$table->foreign('action_009', 'fk03_001_009_permission')->references('id_008')->on('001_008_action')
					->onDelete('cascade')->onUpdate('cascade');
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
		if (Schema::hasTable('001_009_permission'))
		{
			Schema::drop('001_009_permission');
		}
	}
}