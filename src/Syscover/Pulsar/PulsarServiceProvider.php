<?php namespace Syscover\Pulsar;

use Illuminate\Support\ServiceProvider;
use Syscover\Pulsar\Libraries\CustomValidator;

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
		include realpath(__DIR__ . '/../../routes.php');

		// register views
		$this->loadViewsFrom(realpath(__DIR__ . '/../../views'), 'pulsar');

		// register translations
		$this->loadTranslationsFrom(realpath(__DIR__ . '/../../lang'), 'pulsar');

		// register public files
		$this->publishes([
			realpath(__DIR__ . '/../../../public') => public_path('/packages/syscover/pulsar')
		]);

		// register config files
		$this->publishes([
            realpath(__DIR__ . '/../../config/pulsar.php') => config_path('pulsar.php'),
            realpath(__DIR__ . '/../../config/auth.php') => config_path('auth.php'),
            realpath(__DIR__ . '/../../config/cron.php') => config_path('cron.php'),
			realpath(__DIR__ . '/../../config/apis.php') => config_path('apis.php')
        ]);

        // register custom validator
        $this->app['validator']->resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });

        // register migrations
        $this->publishes([
            __DIR__.'/../../database/migrations/' => base_path('/database/migrations'),
			__DIR__.'/../../database/migrations/updates/' => base_path('/database/migrations/updates'),
        ], 'migrations');

        // register migrations
        $this->publishes([
            __DIR__.'/../../database/seeds/' => base_path('/database/seeds')
        ], 'seeds');
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
