<?php

return [
    /*
	|--------------------------------------------------------------------------
	| Secret Key Google Maps
	|--------------------------------------------------------------------------
	|
	| To obtain this secret key, please visit this URL: https://developers.google.com/maps/
	|
	*/
    'googleMapsApiKey'              => env('API_KEY_GOOGLE_MAPS', 'your api key'),

    /*
	|--------------------------------------------------------------------------
	| Secret Key Google reCAPTCHA
	|--------------------------------------------------------------------------
	|
	| To obtain this secret key, please visit this URL: https://www.google.com/recaptcha/admin
	|
	*/

    'googleRecaptchaSecretKey' => env('GOOGLE_RECAPTCHA_SECRET_KEY', 'your secret key'),

	/*
	|--------------------------------------------------------------------------
	| Site Key Google reCAPTCHA
	|--------------------------------------------------------------------------
	|
	| To obtain this secret key, please visit this URL: https://www.google.com/recaptcha/admin
	|
	*/

	'googleRecaptchaSiteKey' => env('GOOGLE_RECAPTCHA_SITE_KEY', 'your site key')
];