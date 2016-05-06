<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableField extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('001_026_field'))
        {
            Schema::create('001_026_field', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id_026')->unsigned();
                $table->integer('group_026')->unsigned();
                $table->string('name_026')->nullable();
                // lang_026 set in json on data_026
                // label_026 set in json on data_026

                $table->tinyInteger('field_type_id_026')->unsigned(); // see config/pulsar.php
                $table->string('field_type_name_026');
                // 1 - Text
                // 2 - Select
                // 3 - Select multiple
                // 4 - Number
                // 5 - Email
                // 6 - Checkboc
                // 7 - Select 2
                // 8 - Select 2 multiple
                // 9 - Text area
                // 10 - Wysiwyg

                $table->tinyInteger('data_type_id_026')->unsigned();
                $table->string('data_type_name_026');
                // 1 - String
                // 2 - Boolean
                // 3 - Integer
                // 4 - Float
                // 5 - Array
                // 6 - Object

                $table->boolean('required_026');
                $table->smallInteger('sorting_026')->unsigned()->nullable();
                $table->integer('max_length_026')->unsigned()->nullable();
                $table->string('pattern_026')->nullable()->nullable();

                $table->tinyInteger('label_size_026')->unsigned()->nullable();  // column bootstrap size
                $table->tinyInteger('field_size_026')->unsigned()->nullable();  // column bootstrap size

                $table->text('data_lang_026')->nullable();
                $table->text('data_026')->nullable();

                $table->foreign('group_026', 'fk01_001_026_field')->references('id_025')->on('001_025_field_group')
                    ->onDelete('cascade')->onUpdate('cascade');
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
        if (Schema::hasTable('001_026_field'))
        {
            Schema::drop('001_026_field');
        }
    }
}
