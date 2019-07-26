<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Support\Facades\Config;

class ConfigHelper
{
	public static function get($key, $default = null) {
		return config($key, $default);
	}

	public static function getArray($key, $default = []) {
		if(!Config::has($key)) {
			return $default;
		}

		$value = Config::get($key);

		if(is_string($value)) {
			$value = explode(",", $value);
		}

		return $value;
	}
}