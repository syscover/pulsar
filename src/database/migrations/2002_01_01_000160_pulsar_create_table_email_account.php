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
        if(! Schema::hasTable('001_013_email_account'))
        {
            Schema::create('001_013_email_account', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                
                $table->increments('id_013')->unsigned();
                $table->string('name_013');
                $table->string('email_013');
                $table->string('reply_to_013')->nullable();
                $table->string('outgoing_server_013');
                $table->string('outgoing_user_013');
                $table->string('outgoing_pass_013');
                $table->string('outgoing_secure_013', 5);                // null/TLS/SSL/SSLv2/SSLv3
                $table->smallInteger('outgoing_port_013');
                $table->string('incoming_type_013', 5);                  // pop, imap, mbox
                $table->string('incoming_server_013');
                $table->string('incoming_user_013');
                $table->string('incoming_pass_013');
                $table->string('incoming_secure_013', 5);                // null/SSL
                $table->smallInteger('incoming_port_013');

                $table->integer('n_emails_013')->unsigned();
                // field that records the last uid checked to see if there are more messages bounced check
                $table->integer('last_check_uid_013')->unsigned();
                // uid that is cheking
                $table->integer('checking_uid_013')->unsigned()->nullable();
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
        if (Schema::hasTable('001_013_email_account'))
        {
            Schema::drop('001_013_email_account');
        }
    }
}