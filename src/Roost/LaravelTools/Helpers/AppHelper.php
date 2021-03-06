<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Support\Facades\App;
use Roost\LaravelTools\Laravel\Databases\DatabaseHelper;

class AppHelper
{
	private static $appLabel = null;
	private static $appInstanceId = null;

	public static function isDebuggingEnabled($strict = true) {
		$enabled = config("app.debug", false);

		if($strict) {
			$enabled = $enabled && static::isDevEnvironment();
		}

		return $enabled;
	}

	public static function isDevelopmentOrDebuggingEnabled() {
		$enabled = config("app.debug", false);

		$enabled = $enabled || static::isDevEnvironment();

		return $enabled;
	}

	public static function isDevEnvironment() {
		$DEV_ENVIRONMENTS = ["local", "staging", "development", "testing"];
		return in_array(strtolower(config("app.env", "")), $DEV_ENVIRONMENTS);
	}

	public static function setAppLabel($label) {
		static::$appLabel = $label;
	}

	public static function getAppLabel() {
		if(static::$appLabel !== null) {
			return static::$appLabel;
		}

        $instance = App::getFacadeRoot();

		if(!$instance) {
			$label = "script";
		} else if(App::runningInConsole()) {
			$label = "console";
		} else {
			$label = "web";
		}

		return $label;
	}

	public static function getAppInstanceId() {
		if(static::$appInstanceId === null) {
			static::$appInstanceId = "APP_ID_" . DatabaseHelper::getUniqueAlphaNumeric(10) . "_" . microtime();
		}

		return static::$appInstanceId;
	}
}