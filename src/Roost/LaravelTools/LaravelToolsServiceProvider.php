<?php

namespace Roost\LaravelTools;

use Illuminate\Support\ServiceProvider;
use Roost\LaravelTools\Commands\Deployment\ApplicationCheckHeartbeatsCommand;
use Roost\LaravelTools\Commands\Deployment\ApplicationTriggerHeartbeatsCommand;

class LaravelToolsServiceProvider extends ServiceProvider {
	public function boot() {
		$this->loadMigrationsFrom(__DIR__.'/../../../database');

		if ($this->app->runningInConsole()) {
			$this->commands([
				ApplicationTriggerHeartbeatsCommand::class
			]);
    }
	}
}