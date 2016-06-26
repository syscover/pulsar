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
        if(! Schema::hasTable('001_027_field_value'))
        {
            Schema::create('001_027_field_value', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->string('id_027', 30);
                $table->string('lang_id_027', 2);
                $table->integer('field_id_027')->unsigned();

                // counter to assign number to id_027 if has not ID
                $table->integer('counter_027')->unsigned()->nullable();

                $table->string('name_027');

                $table->smallInteger('sorting_027')->unsigned()->nullable();
                $table->boolean('featured_027');
                $table->string('data_lang_027')->nullable();
                $table->text('data_027')->nullable();
                
                $table->foreign('lang_id_027', 'fk01_001_027_field_value')->references('id_001')->on('001_001_lang')
                    ->onDelete('restrict')->onUpdate('cascade');
                $table->foreign('field_id_027', 'fk02_001_027_field_value')->references('id_026')->on('001_026_field')
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->primary(['id_027', 'lang_id_027', 'field_id_027'], 'pk01_001_027_field_value');
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
        if (Schema::hasTable('001_027_field_value'))
        {
            Schema::drop('001_027_field_value');
        }
    }
}
