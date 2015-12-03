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
            $table->increments('id_028')->unsigned();
            $table->integer('object_028')->unsigned();
            $table->string('lang_028', 2);
            $table->string('resource_028', 30);
            $table->integer('field_028')->unsigned();
            $table->string('type_028')->default('string');
            $table->longText('value_028');

            $table->index(['lang_028', 'field_028', 'resource_028', 'object_028'], 'pk01_001_028_field_result');
            $table->foreign('lang_028', 'fk01_001_028_field_result')->references('id_001')->on('001_001_lang')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('field_028', 'fk02_001_028_field_result')->references('id_026')->on('001_026_field')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('resource_028', 'fk03_001_028_field_result')->references('id_007')->on('001_007_resource')
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
