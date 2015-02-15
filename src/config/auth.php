<?php

return array(
	/*
	|--------------------------------------------------------------------------
	| Default Authentication Driver
	|--------------------------------------------------------------------------
	|
	| This option controls the authentication driver that will be utilized.
	| This drivers manages the retrieval and authentication of the users
	| attempting to get access to protected areas of your application.
	|
	| Supported: "database", "eloquent"
	|
	*/

	'driver' => 'eloquent',

	/*
	|--------------------------------------------------------------------------
	| Authentication Model
	|--------------------------------------------------------------------------
	|
	| When using the "Eloquent" authentication driver, we need to know which
	| Eloquent model should be used to retrieve your users. Of course, it
	| is often just the "User" model but you may use whatever you like.
	|
	*/

	'model' => 'Pulsar\Pulsar\Models\User',

	/*
	|--------------------------------------------------------------------------
	| Authentication Table
	|--------------------------------------------------------------------------
	|
	| When using the "Database" authentication driver, we need to know which
	| table should be used to retrieve your users. We have chosen a basic
	| default value but you may easily change it to any table you like.
	|
	*/

	'table' => '001_010_user',
    
        /*
	|--------------------------------------------------------------------------
	| Authentication Username
	|--------------------------------------------------------------------------
	|
	| Here you may specify the database column that should be considered the
	| "username" for your users. Typically, this will either be "username"
	| or "email". Of course, you're free to change the value to anything.
	|
	*/

	'username' => 'user_010',

	/*
	|--------------------------------------------------------------------------
	| Authentication Password
	|--------------------------------------------------------------------------
	|
	| Here you may specify the database column that should be considered the
	| "password" for your users. Typically, this will be "password" but, 
	| again, you're free to change the value to anything you see fit.
	|
	*/

	'password' => 'password_010',

	/*
	|--------------------------------------------------------------------------
	| Password Reminder Settings
	|--------------------------------------------------------------------------
	|
	| Here you may set the settings for password reminders, including a view
	| that should be used as your password reminder e-mail. You will also
	| be able to set the name of the table that holds the reset tokens.
	|
	*/

	'reminder' => array(

		'email' => 'pulsar::pulsar.pulsar.emails.reminder',
            
		'table' => '001_066_password_reminders',

		'expire' => 60,
	),
);