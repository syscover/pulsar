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
        Schema::create('001_026_field', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id_026')->unsigned();
            $table->integer('family_026')->unsigned();
            $table->string('name_026', 100)->nullable();
            $table->tinyInteger('type_026')->unsigned();
            // 1 - text
            // 2 - select
            // 3 - select multiple
            // 4 - number
            // 5 - email
            // 6 - check
            // 7
            //
            //

            $table->boolean('int_value_026');   // determines whether this field will have numeric values or strings
            $table->boolean('required_026');
            $table->smallInteger('sorting_026')->unsigned()->nullable();
            $table->integer('max_length_026')->unsigned()->nullable();
            $table->string('pattern_026', 50)->nullable()->nullable();

            $table->tinyInteger('label_size_026')->unsigned()->nullable();  // column bootstrap size
            $table->tinyInteger('field_size_026')->unsigned()->nullable();  // column bootstrap size

            $table->string('data_lang_026',255)->nullable();
            $table->text('data_026')->nullable();

            $table->foreign('family_026')->references('id_025')->on('001_025_field_family')
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
        Schema::drop('001_026_field');
    }
}
