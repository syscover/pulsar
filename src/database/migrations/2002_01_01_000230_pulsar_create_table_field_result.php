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
        if(! Schema::hasTable('001_028_field_result'))
        {
            Schema::create('001_028_field_result', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                
                $table->increments('id_028')->unsigned();
                $table->integer('object_id_028')->unsigned();      // ID of record who owns the result
                $table->string('lang_id_028', 2);
                $table->string('resource_id_028', 30);
                $table->integer('field_id_028')->unsigned();
                
                // data type
                // 1 - String
                // 2 - Boolean
                // 3 - Integer
                // 4 - Float
                // 5 - Array
                // 6 - Object
                $table->string('data_type_type_028')->default('string');
                $table->longText('value_028');
                
                $table->foreign('lang_id_028', 'fk01_001_028_field_result')
                    ->references('id_001')
                    ->on('001_001_lang')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
                $table->foreign('field_id_028', 'fk02_001_028_field_result')
                    ->references('id_026')
                    ->on('001_026_field')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('resource_id_028', 'fk03_001_028_field_result')
                    ->references('id_007')
                    ->on('001_007_resource')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->index(['lang_id_028', 'field_id_028', 'resource_id_028', 'object_id_028'], 'pk01_001_028_field_result');
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
        if (Schema::hasTable('001_028_field_result'))
        {
            Schema::drop('001_028_field_result');
        }
    }
}
