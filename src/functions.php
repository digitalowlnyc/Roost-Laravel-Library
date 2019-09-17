<?php

use Roost\LaravelTools\Helpers\EnvHelper;
use Roost\Logging\GlobalLogging;
use Symfony\Component\VarDumper\VarDumper;

if(!function_exists("exitWithError")) {
	function exitWithError($message = null) {
		try {
			GlobalLogging::instance()->error($message);
		} catch(Exception $e) {
		} catch(Error $err) {
		}
		echo "Exiting: " . $message . PHP_EOL;
		exit(1);
	}
}

if(!function_exists("printLine")) {
	function printLine($msg) {
		echo $msg . PHP_EOL;
	}
}

if(!function_exists("printReturn")) {
	function printReturn($val) {
		return print_r($val, true);
	}
}

if(!function_exists("printOut")) {
	function printOut($val) {
		print_r($val);
	}
}


if(!function_exists("exceptionDescription")) {
	function exceptionDescription($exception) {
		$exceptionClassName = "";
		try {
			$reflect = new ReflectionClass($exception);
			$exceptionClassName = $reflect->getShortName();
		} catch(Exception $e) {
			// ignore
		}
		/** @var $exception Exception */
		$description = $exception->getMessage() . "[" . $exceptionClassName . "@" . $exception->getFile() . ":" . $exception->getLine() . "]";

		return $description;
	}
}

if(!function_exists("logString")) {
	function logString($val) {
		if($val === null) {
			return "null";
		}
		if($val === false) {
			return "false";
		}
		if($val === true) {
			return "true";
		}
		if(gettype($val) !== "string") {
			$val = json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}

		return $val;
	}
}

if(!function_exists("stringIsEqual")) {
	function stringIsEqual($str1, $str2) {
		return strcasecmp($str1, $str2) === 0;
	}
}

if(!function_exists("convertDatabaseStringToHumanFriendly")) {
	function convertDatabaseStringToHumanFriendly($str, $nullValueText = "") {
		if($str === null) {
			return $nullValueText;
		}
		$str = str_replace("_", " ", $str);
		$str = ucwords(strtolower($str));
		return $str;
	}
}

if(!function_exists("array_combine_same")) {
	function array_combine_same($arr) {
		return array_combine($arr, $arr);
	}
}

if(!class_exists("DataWrapper")) {
	class DataWrapper {

		public function __construct($data) {
			$this->data = $data;
		}

		public function get($key, $default = null) {
			if(!array_key_exists($key, $this->data)) {
				return $default;
			}
			return $this->data[$key];
		}

		public function input($key, $default = null) {
			return $this->get($key, $default);
		}
	}
}

if(!class_exists("BND")) {
	class BND {
		static function log(...$msg) {
			\Illuminate\Support\Facades\Log::info(...$msg);
		}
	}
}

if (!function_exists('ddd')) {
    function ddd(...$moreVars)
    {
    	$varDumperClassExists = class_exists("VarDumper");

    	echo "ddd() called:" . PHP_EOL;
    	if(!$varDumperClassExists) {
    		echo " (install VarDumper for formatted output)" . PHP_EOL;
		}
        foreach ($moreVars as $v) {
        	if($varDumperClassExists) {
				VarDumper::dump($v);
			} else {
        		var_dump($v);
			}
        }

        exit(1);
    }
}

if (!function_exists('lll')) {
    function lll(...$args) {
    	$allArgsAreStrings = true;
    	foreach($args as &$arg) {
    		if(!is_string($arg)) {
    			$allArgsAreStrings = false;
    			$arg = json_encode($arg, JSON_PRETTY_PRINT);
			}
		}

		if($allArgsAreStrings) {
			Illuminate\Support\Facades\Log::info(...$args);
		} else {
    		Illuminate\Support\Facades\Log::info($args);
		}
    }
}

if(!function_exists("counter_global")) {
	function counter_global($counterLabel = "default") {
		$counterVar = "counter_global_" . $counterLabel;
		global ${$counterVar};

		if(!isset(${$counterVar})) {
			${$counterVar} = 1;
		} else {
			${$counterVar} += 1;
		}

		return ${$counterVar};
	}
}

if(!function_exists("functionCallHelper")) {
	function functionCallHelper(callable $callable, callable $onErrorCallable = null) {
		try {
			return $callable();
		} catch(\Exception $e) {
			if($onErrorCallable !== null) {
				return $onErrorCallable($e);
			} else {
				echo __FUNCTION__ . ": Unhandled Exception encountered:" . PHP_EOL;
				echo $e;
			}
		} catch(\Error $err) {
			if($onErrorCallable !== null) {
				return $onErrorCallable($err);
			} else {
				echo __FUNCTION__ . ": Unhandled Error encountered:" . PHP_EOL;
				echo $err;
			}
		}

		throw new RuntimeException("functionCallHelper: failed call to function");
	}
}

if(!function_exists("functionCallTryer")) {
	function functionCallTryer(callable $callable, callable $onErrorCallable = null) {
		try {
			return functionCallHelper($callable, $onErrorCallable);
		} catch(Exception $e) {
			// No fallback
		} catch(Error $e) {
			// No fallback
		}

		return null;
	}
}

if(!(function_exists("bndIsDebugMode"))) {
	function bndIsDebugMode() {
		return config("app.debug");
	}
}

if(!(function_exists("logdebug"))) {
	class DebugLogger
	{
		const CLEAR_ON_NEW_REQUEST = true;

		static function logdebug($data, $file = "bnd.log", $append = true)
		{
			$dir = dirname($file);

			if(!is_dir($dir)) {
				mkdir($dir, 0777);
			}

			try {
				if(file_exists($file)) {
					$fileSize = filesize($file) / 1048576;
					if($fileSize > 50) {
						file_put_contents($file, "[TRUNCATED]" . PHP_EOL);
					}
				}

				if(!isset($GLOBALS["logdebug_identifier"])) {
					$GLOBALS["logdebug_identifier"] = str_random(8);

					if(static::CLEAR_ON_NEW_REQUEST) {
						file_put_contents($file, "");
					}
				}

				if(!is_string($data)) {
					$data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
				}

				$logLine = date("r") . "[" . $GLOBALS["logdebug_identifier"] . "] " . ": " . $data;

				$flag = $append ? FILE_APPEND : 0;

				$result = file_put_contents($file, $logLine . PHP_EOL, $flag);

				if(!$result) {
					error_log("Could not write successfully to file using logdebug()");
				}
			} catch(Exception $e) {
				if(bndIsDebugMode()) {
					echo __FUNCTION__ . ": Exception encountered: " . $e->getMessage();
				}
			} catch(Error $err) {
				if(bndIsDebugMode()) {
					echo __FUNCTION__ . ": Error encountered: " . $err->getMessage();
				}
			}
		}
	}

	function logdebug($data, $file = "bnd.log", $append = true) {
		if(strpos($file, ".log") === false) {
			throw new RuntimeException("Log file must end in .log");
		}
		DebugLogger::logdebug($data, $file, $append = true);
	}
}

if(!function_exists('envHelper')) {
	function envHelper($key, $default = null) {
		return EnvHelper::get($key, $default);
	}
}

if(!function_exists('typeOrClassOf')) {
    /**
     * Returns the type or class of a value
     *
     * @param $val
     * @return string
     */
    function typeOrClassOf($val) {
    	if(is_object($val)) {
    		return get_class($val);
		}
		return gettype($val);
    }
}

if(!function_exists('functionPrefixForLog')) {
    function functionPrefixForLog($functionName) {
    	return $functionName . "(" . "): ";
    }
}

if(!function_exists('temp_path')) {
    function temp_path() {
    	return storage_path("tmp");
    }
}

if(!function_exists('assertProperty')) {
    function assertProperty($object, $property, $expectedValue) {
    	$actualValue = $object->{$property};
    	if($actualValue !== $expectedValue) {
			throw new AssertionError("Field '" . $property . "' does not have expected value '" . $expectedValue . "', value is '" . $actualValue . "'");
		}
    }
}

if(!function_exists('logToFile')) {
	function logToFile($fileName, $message)
	{
		if(!is_string($message)) {
			$message = print_r($message, true);
		}
		file_put_contents($fileName, date("r") . ": " . $message . PHP_EOL, FILE_APPEND);
	}
}

if (! function_exists('testing_storage_path')) {
    /**
     * Get the path to the testing storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function testing_storage_path()
    {
        return storage_path("testing");
    }
}