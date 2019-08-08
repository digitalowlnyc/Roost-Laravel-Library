<?php

namespace Roost\LaravelTools;

use Illuminate\Support\ServiceProvider;
use Roost\LaravelTools\Commands\Deployment\DeploymentCheckHeartbeatsCommand;
use Roost\LaravelTools\Commands\Deployment\DeploymentTriggerHeartbeatsCommand;

class LaravelToolsServiceProvider extends ServiceProvider {
	public function boot() {
		$this->loadMigrationsFrom(__DIR__.'/../../../database');

		$this->publishes([
        	__DIR__.'/../../../config/admin_notifications.php' => config_path('admin_notifications.php'),
    	]);

		if ($this->app->runningInConsole()) {
			$this->commands([
				DeploymentTriggerHeartbeatsCommand::class,
				DeploymentCheckHeartbeatsCommand::class
			]);
		}
	}
}