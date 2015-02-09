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
		require $this->app->basePath() . '/workbench/pulsar/pulsar/vendor/autoload.php';

		// include route.php file
		include $this->app->basePath() . '/workbench/pulsar/pulsar/src/routes.php';

		// register views
		$this->loadViewsFrom($this->app->basePath() . '/workbench/pulsar/pulsar/src/views', 'pulsar');

		// register translations
		$this->loadTranslationsFrom($this->app->basePath() . '/workbench/pulsar/pulsar/src/lang', 'pulsar');

		// register public files
		$this->publishes([
			$this->app->basePath() . '/workbench/pulsar/pulsar/public' => public_path('/packages/pulsar/pulsar')
		]);

		// register config files
		$this->publishes([
			//__DIR__ . '/../../config/pulsar.php' => config_path('pulsar.php'),
			$this->app->basePath() . '/workbench/pulsar/pulsar/src/config/pulsar.php' => config_path('pulsar.php'),
			$this->app->basePath() . '/workbench/pulsar/pulsar/src/config/auth.php' => config_path('auth.php')
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
