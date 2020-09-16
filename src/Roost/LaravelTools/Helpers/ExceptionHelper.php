<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Helpers;

class ExceptionHelper
{
	// Includes take precedence over excludes
	const EXCEPTION_STACK_LINES_TO_INCLUDE = [
		"Illuminate\\Routing\\Router->{closure}()" // keep line if route is a closure
	];

	const EXCEPTION_STACK_LINES_TO_IGNORE = [
		"laravel/framework/src",
		"symfony/console/Application"
	];

	public static function describe($ex) {
		self::checkIsValidExceptionType($ex);

		return print_r(static::asArray($ex), true);
	}

	public static function asArray($ex, $abbreviated = true) {
		self::checkIsValidExceptionType($ex);

		return [
			"message" => $ex->getMessage(),
			"type" => get_class($ex),
			"trace" => static::getStackTrace($ex, $abbreviated)
		];
	}

	/**
	 * @param \Error|\Exception $e
	 * @return string
	 */
	public static function location($e) {
		self::checkIsValidExceptionType($e);
		return $e->getFile() . ":" . $e->getLine();
	}

	public static function getStackTrace($ex, $abbreviated = true) {
		$stackTrace = $ex->getTraceAsString();
		return $stackTrace;
	}


	/**
	 * @param $ex \Error|\Exception
	 * @param bool $abbreviated
	 * @return array
	 */
	public static function getStackTraceArray($ex, $abbreviated = true) {
		self::checkIsValidExceptionType($ex);

		$stackTrace = $ex->getTraceAsString();
		$stackTraceLines = explode(PHP_EOL, $stackTrace);

		if($abbreviated) {
			$stackTraceLines = array_filter($stackTraceLines, function ($line) {
				foreach(static::EXCEPTION_STACK_LINES_TO_INCLUDE as $stackToInclude) {
					if(strpos($line, $stackToInclude) !== false) {
						return true;
					}
				}

				foreach(static::EXCEPTION_STACK_LINES_TO_IGNORE as $stackToIgnore) {
					if(strpos($line, $stackToIgnore) !== false) {
						return false;
					}
				}

				return true;
			});
			array_unshift($stackTraceLines, "[ExceptionHelper: Abbreviated stack trace]");
			$stackTraceLines = implode(PHP_EOL, $stackTraceLines);
		}

		return $stackTraceLines;
	}

	public static function checkIsValidExceptionType($ex) {
		if(!($ex instanceof \Error || $ex instanceof \Exception)) {
			new \RuntimeException(__FUNCTION__ . "() does not support this class of exception");
		}
	}

	public static function log($ex, $codeLocation = null) {
		$msg =  "Logging exception ";

		if($codeLocation !== null) {
			$msg .= " from " . $codeLocation;
		}

		$msg .= ": ";

		if($ex instanceof \PDOException) {
			$msg .= ", (errorInfo=" . json_encode($ex->errorInfo) . ")";
        }


		echo $msg;
		echo $ex;
	}

	static function getCallerBacktrace($file) {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $callerBacktraceEntry = null;

        foreach($backtrace as $backtraceEntry) {
            if(!in_array($backtraceEntry['file'], [__FILE__, $file])) {
                $callerBacktraceEntry = $backtraceEntry;
                break;
            }
        }

        return $callerBacktraceEntry;
    }

    static function getCallerBacktraceLogInfo($file) {
        $caller = self::getCallerBacktrace($file);
        if($caller !== null) {
            if(isset($caller['class']) && $caller['class'] != null) {
                $classPath = explode('\\', $caller['class']);
                $func = array_pop($classPath) . $caller['type'] . $caller['function'];
            } else {
                $func = $caller['function'];
            }
            $func = $func . '(...)';

            $fileLocation = basename($caller['file']) . '@' . $caller['line'];
            $logAppend = $fileLocation . '[' . $func . ']';
            return $logAppend;
        } else {
            return null;
        }
    }
}