<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableAttachmentFamily extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('001_015_attachment_family'))
        {
            Schema::create('001_015_attachment_family', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id_015')->unsigned();
                $table->string('resource_015', 30);                             // resource which belong to this attachment
                $table->string('name_015', 100);
                $table->smallInteger('width_015')->unsigned()->nullable();
                $table->smallInteger('height_015')->unsigned()->nullable();
                $table->text('data_015')->nullable();

                $table->foreign('resource_015', 'fk01_001_015_attachment_family')->references('id_007')->on('001_007_resource')
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
        Schema::drop('001_015_attachment_family');
    }
}
