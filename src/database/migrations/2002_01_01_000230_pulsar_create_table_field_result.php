<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableFieldResult extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('001_028_field_result', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->integer('field_028')->unsigned();
            $table->integer('value_028')->unsigned();
            $table->integer('object_028')->unsigned()->nullable();

            $table->primary(['field_028', 'value_028', 'object_028']);
            $table->foreign('field_028')->references('id_026')->on('001_026_field')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('value_028')->references('id_027')->on('001_027_field_value')
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
        Schema::drop('001_028_field_result');
    }
}
