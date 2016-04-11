<?php namespace Syscover\Pulsar;

use Illuminate\Support\Facades\Validator;
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
		// include route.php file
		if (!$this->app->routesAreCached())
			require __DIR__ . '/../../routes.php';

		// include helpers file
		require __DIR__ . '/Helpers/helpers.php';

		// register views
		$this->loadViewsFrom(__DIR__ . '/../../views', 'pulsar');

		// register translations
		$this->loadTranslationsFrom(__DIR__ . '/../../lang', 'pulsar');

		// register translations files
		$this->publishes([
			__DIR__ . '/../../lang/en/validation.php'			=> resource_path('/lang/en/validation.php'),
			__DIR__ . '/../../lang/es/validation.php'			=> resource_path('/lang/es/validation.php')
		]);

		// register public files
		$this->publishes([
			__DIR__ . '/../../../public'						=> public_path('/packages/syscover/pulsar')
		]);

		// register config files
		$this->publishes([
            __DIR__ . '/../../config/pulsar.php' 				=> config_path('pulsar.php'),
            __DIR__ . '/../../config/cron.php'					=> config_path('cron.php'),
			__DIR__ . '/../../config/api.php' 					=> config_path('api.php')
        ]);

        // register migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations/' 			=> base_path('/database/migrations'),
			__DIR__ . '/../../database/migrations/updates/' 	=> base_path('/database/migrations/updates'),
        ], 'migrations');

        // register migrations
        $this->publishes([
            __DIR__ . '/../../database/seeds/' 					=> base_path('/database/seeds')
        ], 'seeds');

		// register factories
		$this->publishes([
			__DIR__ . '/../../database/factories/' 				=> base_path('/database/factories')
		], 'factories');

		// register tests
		$this->publishes([
			__DIR__ . '/../../tests/' 							=> base_path('/tests')
		], 'tests');

		// custom validator rules
		Validator::extend('digit', function($attribute, $value, $parameters, $validator) {
			return (strlen($value) == $parameters[0])? true : false;
		});
		
		Validator::extend('cronExpression', function($attribute, $value, $parameters, $validator) {
			try
			{
				\Cron\CronExpression::factory($value);
				return true;
			}
			catch (\InvalidArgumentException $e)
			{
				return false;
			}
		});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        //
	}
}