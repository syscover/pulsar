<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableCronJob extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_011_cron_job', function($table) {
                $table->engine = 'InnoDB';
                $table->increments('id_011')->unsigned();
                $table->string('name_011', 100);
                $table->integer('package_011')->unsigned();
                $table->string('cron_expression_011', 255);
                $table->string('key_011',50);
                $table->integer('last_run_011')->unsigned();
                $table->integer('next_run_011')->unsigned();
                $table->boolean('active_011');

                $table->foreign('package_011', 'fk01_001_011_cron_job')->references('id_012')->on('001_012_package')
                        ->onDelete('cascade')->onUpdate('cascade');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_011_cron_job');
	}

}