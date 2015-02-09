<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PulsarServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// load autoload from package composer.json
		require realpath(__DIR__ . '/../vendor/autoload.php');

		// include route.php file
		include realpath(__DIR__ . '/routes.php');

		// register views
		$this->loadViewsFrom(realpath(__DIR__ . '/views'), 'pulsar');

		// register translations
		$this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'pulsar');

		// register public files
		$this->publishes([
			realpath(__DIR__ . '/../public') => public_path('/packages/pulsar/pulsar')
		]);

		// register config files
		$this->publishes([
			realpath(__DIR__ . '/config/pulsar.php') => config_path('pulsar.php'),
			realpath(__DIR__ . '/config/auth.php') => config_path('auth.php')
		]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{

	}

}
