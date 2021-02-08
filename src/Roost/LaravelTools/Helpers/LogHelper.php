<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Support\Facades\Log;

class LogHelper
{
	/**
	 *
	 *
	 * @param $stack
	 * @param bool $useBackupLogger
	 * @return 	\Illuminate\Support\Facades\Log
	 */
	public static function getLogger($stack, $useBackupLogger = true) {
		$channels = ConfigHelper::get("logging.channels");
		$channels = array_keys($channels);

		if(!in_array($stack, $channels)) {
			if(!$useBackupLogger) {
				throw new \RuntimeException("getLogger(): logger stack not found: " . $stack);
			} else {
				Log::warning("getLogger(): no logger for stack " . $stack . ", using default");
				return Log::channel();
			}
		}

		return Log::channel($stack);
	}

	/**
	 * Wrap log calls in a block to keep code clean when accessing
	 * other data/objects that are just for logging purposes.
	 *
	 * @param $callback
	 */
	public static function output(callable $callback) {
		ob_start();
		$callback();
		$output = ob_get_clean();
		echo $output;
	}
}