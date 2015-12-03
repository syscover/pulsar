<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableFieldValue extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('001_027_field_value', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id_027')->unsigned();
            $table->string('lang_027', 2);
            $table->integer('field_027')->unsigned();
            $table->string('name_027', 255);
            $table->smallInteger('sorting_027')->unsigned()->nullable();
            $table->boolean('featured_027');
            $table->string('data_lang_027', 255)->nullable();
            $table->text('data_027')->nullable();

            $table->primary(['id_027', 'lang_027'], 'pk01_001_027_field_value');
            $table->foreign('lang_027', 'fk01_001_027_field_value')->references('id_001')->on('001_001_lang')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('field_027', 'fk02_001_027_field_value')->references('id_026')->on('001_026_field')
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
        Schema::drop('001_027_field_value');
    }
}
