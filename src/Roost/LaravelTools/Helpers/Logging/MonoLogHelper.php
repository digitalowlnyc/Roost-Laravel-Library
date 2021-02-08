<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (Blue Nest Digital LLC, All rights reserved)
 * Copyright: Copyright 2021 Blue Nest Digital LLC
 */

namespace Roost\LaravelTools\Helpers\Logging;


use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MonoLogHelper
{
	public static function getLogFileUrls($logger) {

		if($logger instanceof \Illuminate\Log\Logger) {
			$logger = $logger->getLogger();
		}

		if(!($logger instanceof Logger)) {
			return "";
		}

		$handlers = $logger->getHandlers();

		$paths = [];

		/** @var  $handler */
		foreach($handlers as $handler) {
			if($handler instanceof RotatingFileHandler) {
				$logPath = $handler->getUrl();
			} else if($handler instanceof StreamHandler) {
				$logPath = $handler->getUrl();
			} else {
				$logPath = get_class($handler);
			}

			$paths[] = basename($logPath);
		}

		return $paths;
	}
}