<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class EchoHelper
{
	public static function cli($message, $logIfNotConsole = true) {
		if(!App::runningInConsole()) {
			if($logIfNotConsole) {
				Log::info("EchoHelper::cli(): " . $message);
			}
		} else {
			echo $message . PHP_EOL;
		}
	}
}