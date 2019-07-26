<?php

namespace Roost\LaravelTools\Helpers;

class HttpHelper
{
	public static function header($key, $value, $force = false) {
		if(headers_sent() && !$force) {
			return;
		}

		header($key .": " . $value);
	}
}