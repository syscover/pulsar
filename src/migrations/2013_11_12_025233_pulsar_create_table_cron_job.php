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
            Schema::create('001_043_cron_job', function($table) {
                $table->engine = 'InnoDB';
                $table->increments('id_043')->unsigned();
                $table->string('name_043', 100);
                $table->integer('package_043')->unsigned();
                $table->string('cron_expression_043', 255);
                $table->string('key_043',50);
                $table->integer('last_run_043')->unsigned();
                $table->integer('next_run_043')->unsigned();
                $table->boolean('active_043');

                $table->foreign('package_043')->references('id_012')->on('001_012_package')
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
            Schema::drop('001_043_cron_job');
	}

}