<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarUpdateV5 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasColumn('001_028_field_result', 'type_028'))
		{
			Schema::table('001_028_field_result', function (Blueprint $table) {
				$table->string('type_028')->default('string')->after('field_028');
				$table->longText('value_028')->after('type_028');

				$table->dropColumn('boolean_value_028');
				$table->dropColumn('int_value_028');
				$table->dropColumn('text_value_028');
				$table->dropColumn('decimal_value_028');
				$table->dropColumn('timestamp_value_028');
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