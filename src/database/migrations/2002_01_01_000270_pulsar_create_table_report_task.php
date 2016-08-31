<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableReportTask extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_023_report_task'))
		{
			Schema::create('001_023_report_task', function ($table) {
				$table->engine = 'InnoDB';

				$table->increments('id_023')->unsigned();
				$table->integer('date_023')->unsigned();
				$table->integer('user_id_023')->unsigned();

                $table->string('email_023');
                $table->text('cc_023')->nullable(); // emails to send copy
                $table->string('subject_023');

                $table->string('filename_023');
                $table->string('extension_file_023');

                // 1 - one time
                // 2 - daily
                // 3 - weekly
                // 4 - monthly
                // 5 - quarterly
                $table->tinyInteger('frequency_023')->unsigned();

                // if frequency is one time, you can define data range
                $table->integer('from_023')->unsigned()->nullable();    // this field will be replace by #FROM# in query
                $table->integer('until_023')->unsigned()->nullable();   // this field will be replace by #UNTIL# in query

                // if frequency is daily, weekly, monthly or quarterly, you can define day of week or day of month to delivery report
                $table->tinyInteger('delivery_day_023')->unsigned()->nullable();

                $table->integer('last_run_023')->unsigned()->nullable();
                $table->integer('next_run_023')->unsigned()->nullable();

				$table->text('parameters_023')->nullable();
                $table->text('sql_023');

				$table->foreign('user_id_023', 'fk01_001_023_report_task')
					->references('id_010')
					->on('001_010_user')
					->onDelete('restrict')
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
		if(Schema::hasTable('001_023_report_task'))
		{
			Schema::drop('001_023_report_task');
		}
	}
}