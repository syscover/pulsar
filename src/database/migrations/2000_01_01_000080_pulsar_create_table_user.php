<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableUser extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('001_010_user', function($table){
                $table->engine = 'InnoDB';
                $table->increments('id_010')->unsigned();
                $table->string('remember_token_010', 100)->nullable();
                $table->string('lang_010', 2)->index();
                $table->integer('profile_010')->unsigned();
                $table->boolean('access_010');
                $table->string('user_010', 50);
                $table->string('password_010', 255);
                $table->string('email_010', 50);
                $table->string('name_010', 50);
                $table->string('surname_010', 50)->nullable();

                $table->timestamps();

                $table->foreign('lang_010')->references('id_001')->on('001_001_lang')
                        ->onDelete('restrict')->onUpdate('cascade');
                $table->foreign('profile_010')->references('id_006')->on('001_006_profile')
                        ->onDelete('restrict')->onUpdate('cascade');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('001_010_user');
	}

}