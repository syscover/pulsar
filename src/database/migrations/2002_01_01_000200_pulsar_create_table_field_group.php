<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableFieldGroup extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('001_025_field_group'))
        {
            Schema::create('001_025_field_group', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id_025')->unsigned();
                $table->string('name_025')->nullable();
                $table->string('resource_025', 30);                             // resource which belong to this family field
                $table->text('data_025')->nullable();

                $table->foreign('resource_025', 'fk01_001_025_field_group')->references('id_007')->on('001_007_resource')
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
        if (Schema::hasTable('001_025_field_group'))
        {
            Schema::drop('001_025_field_group');
        }
    }
}
