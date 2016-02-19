<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableAttachment extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('001_016_attachment'))
        {
            Schema::create('001_016_attachment', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->integer('id_016')->unsigned();
                $table->string('lang_016', 2);
                $table->string('resource_016', 30);                             // resource which belong to this attachment
                $table->integer('object_016')->unsigned()->nullable();
                $table->integer('family_016')->unsigned()->nullable();
                $table->integer('library_016')->unsigned()->nullable();         // original element library
                $table->string('library_file_name_016', 1020)->nullable();
                $table->integer('sorting_016')->unsigned()->nullable();
                $table->string('url_016', 1020)->nullable();
                $table->string('name_016')->nullable();
                $table->string('file_name_016', 1020)->nullable();
                $table->string('mime_016')->nullable();
                $table->integer('size_016')->unsigned()->nullable();
                $table->tinyInteger('type_016')->unsigned();                    // 1 = image, 2 = file, 3 = video
                $table->string('type_text_016');
                $table->smallInteger('width_016')->unsigned()->nullable();
                $table->smallInteger('height_016')->unsigned()->nullable();

                $table->text('data_lang_016')->nullable();
                $table->text('data_016')->nullable();

                $table->foreign('lang_016', 'fk01_001_016_attachment')->references('id_001')->on('001_001_lang')
                    ->onDelete('restrict')->onUpdate('cascade');
                $table->foreign('resource_016', 'fk02_001_016_attachment')->references('id_007')->on('001_007_resource')
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('family_016', 'fk03_001_016_attachment')->references('id_015')->on('001_015_attachment_family')
                    ->onDelete('restrict')->onUpdate('cascade');
                $table->foreign('library_016', 'fk04_001_016_attachment')->references('id_014')->on('001_014_attachment_library')
                    ->onDelete('set null')->onUpdate('cascade');

                $table->index(['object_016']);
                $table->primary(['id_016', 'lang_016']);
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
        if (Schema::hasTable('001_016_attachment'))
        {
            Schema::drop('001_016_attachment');
        }
    }
}
