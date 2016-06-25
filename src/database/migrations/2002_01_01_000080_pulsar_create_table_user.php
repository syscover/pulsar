<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PulsarCreateTableUser extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(! Schema::hasTable('001_010_user'))
        {
            Schema::create('001_010_user', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id_010')->unsigned();
                $table->string('name_010');
                $table->string('surname_010')->nullable();
                $table->string('lang_id_010', 2);
                $table->string('email_010');
                $table->integer('profile_id_010')->unsigned();
                $table->boolean('access_010');
                $table->string('user_010');
                $table->string('password_010');
                $table->string('remember_token_010')->nullable();

                $table->timestamps();

                $table->foreign('lang_id_010', 'fk01_001_010_user')->references('id_001')->on('001_001_lang')
                    ->onDelete('restrict')->onUpdate('cascade');
                $table->foreign('profile_id_010', 'fk02_001_010_user')->references('id_006')->on('001_006_profile')
                    ->onDelete('restrict')->onUpdate('cascade');
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
        if (Schema::hasTable('001_010_user'))
        {
            Schema::drop('001_010_user');
        }
	}
}