<?php

namespace Roost\LaravelTools;

use Illuminate\Support\ServiceProvider;

class LaravelToolsServiceProvider extends ServiceProvider {
	public function boot() {
		$this->loadMigrationsFrom(__DIR__.'/../../../database');
	}
}