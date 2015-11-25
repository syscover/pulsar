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
            $table->integer('object_028')->unsigned();
            $table->string('lang_028', 2);
            $table->string('resource_028', 30);
            $table->integer('field_028')->unsigned();

            // possible values
            $table->boolean('boolean_value_028')->nullable();
            $table->integer('int_value_028')->nullable();
            $table->text('text_value_028')->nullable();
            $table->decimal('decimal_value_028', 10, 2)->nullable();
            $table->timestamp('timestamp_value_028')->nullable();

            $table->primary(['lang_028', 'field_028', 'resource_028', 'object_028']);
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
