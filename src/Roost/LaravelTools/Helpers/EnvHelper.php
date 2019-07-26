<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Support\Facades\Log;

class EnvHelper
{
	private static $overrides = [];

	public static function is($key, $compareValue = null) {
		$configValue = static::get($key, null);

		return stringIsEqual($configValue, $compareValue);
	}

	public static function get($key, $default = null) {
		if(array_key_exists($key, static::$overrides)) {
			$value = static::$overrides[$key];
		} else {
			$value = env($key, $default);
		}

		return $value;
	}

	public static function getArray($key, $default = []) {
		if(env($key) === false) {
			return $default;
		}

		$value = static::get($key);

		return explode(",", $value);
	}

	public static function setOverride($key, $value) {
		Log::debug("Setting environment override for key " . $key);
		static::$overrides[$key] = $value;
	}
}