<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableText extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{       
            Schema::create('001_017_text', function($table) {
                $table->engine = 'InnoDB';
                $table->increments('id_017')->unsigned();
                $table->string('lang_017', 2)->index();
                $table->text('text_017')->nullable();
                $table->timestamps();
                $table->foreign('lang_017')->references('id_001')->on('001_001_lang')
                        ->onDelete('restrict')->onUpdate('cascade');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_017_text');
	}

}