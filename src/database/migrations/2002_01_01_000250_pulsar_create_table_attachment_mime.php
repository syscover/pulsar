<?php

use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableAttachmentMime extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('001_019_attachment_mime'))
		{
			Schema::create('001_019_attachment_mime', function ($table) {
				$table->engine = 'InnoDB';

				$table->increments('id_019')->unsigned();
				$table->string('resource_id_019', 30);
				$table->string('mime_019');

				$table->foreign('resource_id_019', 'fk01_001_019_attachment_mime')->references('id_007')->on('001_007_resource')
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
		if(Schema::hasTable('001_019_attachment_mime'))
		{
			Schema::drop('001_019_attachment_mime');
		}
	}
}