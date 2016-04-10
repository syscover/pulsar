<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PulsarUpdateV11 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('001_027_field_value', function (Blueprint $table) {
			// insert id on counter column
			DB::select(DB::raw('UPDATE 001_027_field_value AS value1 JOIN 001_027_field_value AS value2 ON value1.id_027 = value2.id_027 SET value1.counter_027 = CAST(value2.id_027 AS UNSIGNED)'));

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}