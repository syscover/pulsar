<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableLibrary extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('001_014_library', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id_014')->unsigned();
            $table->string('resource_014', 30);                             // resource which belong to this attachment
            $table->string('url_014', 1020)->nullable();
            $table->string('file_name_014', 1020)->nullable();
            $table->string('mime_014', 255);
            $table->integer('size_014')->unsigned();
            $table->tinyInteger('type_014')->unsigned();                    // 1 = image, 2 = file, 3 = video
            $table->string('type_text_014', 50);
            $table->smallInteger('width_014')->unsigned()->nullable();
            $table->smallInteger('height_014')->unsigned()->nullable();
            $table->text('data_014')->nullable();

            $table->foreign('resource_014')->references('id_007')->on('001_007_resource')
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
        Schema::drop('001_014_library');
    }
}
