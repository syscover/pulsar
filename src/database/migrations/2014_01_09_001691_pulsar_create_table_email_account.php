<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PulsarCreateTableEmailAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('001_013_email_account', function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id_013')->unsigned();
            $table->string('name_013',100);
            $table->string('email_013',100);
            $table->string('reply_to_013',100)->nullable();
            $table->string('host_smtp_013',100);
            $table->string('user_smtp_013',100);
            $table->string('pass_smtp_013',255);
            $table->smallInteger('port_smtp_013');
            $table->string('secure_smtp_013',5);                // null/TLS/SSL/SSLv2/SSLv3
            $table->string('host_inbox_013',100);
            $table->string('user_inbox_013',100);
            $table->string('pass_inbox_013',255);
            $table->smallInteger('port_inbox_013');
            $table->string('secure_inbox_013',5);               // null/SSL
            $table->string('type_inbox_013',5);                 // pop, imap, mbox
            $table->integer('n_emails_013')->unsigned();
            $table->integer('last_check_uid_013')->unsigned();  // field that records the last uid checked to see if there are more messages bounced check
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('001_013_email_account');
    }
}