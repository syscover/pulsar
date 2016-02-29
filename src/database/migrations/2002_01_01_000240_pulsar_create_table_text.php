<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableText extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('001_017_text'))
        {
            Schema::create('001_017_text', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->integer('id_017')->unsigned();
                $table->string('lang_id_017', 2);
                $table->text('text_017')->nullable();

                $table->primary(['id_017', 'lang_id_017'], 'pk01_001_017_text');

                $table->foreign('lang_id_017', 'fk01_001_017_text')->references('id_001')->on('001_001_lang')
                    ->onDelete('restrict')->onUpdate('cascade');
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
        if (Schema::hasTable('001_017_text'))
        {
            Schema::drop('001_017_text');
        }
	}
}