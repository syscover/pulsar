<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableCronJob extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_011_cron_job'))
		{
			Schema::create('001_011_cron_job', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				
				$table->increments('id_011')->unsigned();
				$table->string('name_011');
				$table->integer('package_id_011')->unsigned();
				$table->string('cron_expression_011');
				$table->string('key_011');
				$table->integer('last_run_011')->unsigned();
				$table->integer('next_run_011')->unsigned();
				$table->boolean('active_011');

				$table->foreign('package_id_011', 'fk01_001_011_cron_job')
					->references('id_012')
					->on('001_012_package')
					->onDelete('cascade')
					->onUpdate('cascade');
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
		if (Schema::hasTable('001_011_cron_job'))
		{
			Schema::drop('001_011_cron_job');
		}
	}
}