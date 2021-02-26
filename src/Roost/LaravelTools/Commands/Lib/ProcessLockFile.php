<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (Blue Nest Digital LLC, All rights reserved)
 * Copyright: Copyright 2021 Blue Nest Digital LLC
 */

namespace Roost\LaravelTools\Commands\Lib;

use Illuminate\Support\Facades\Log;

class ProcessLockFile
{
	private $lockFileLabel;

	function __construct($lockFileLabel) {
		$this->lockFileLabel = $this->convertLockFileLabel($lockFileLabel);
	}

	public function convertLockFileLabel($label) {
		$fileName = preg_replace('#[^0-9a-z]#i', '_', $label);
		return $fileName;
	}

	public static function getAllLockFiles() {
		return glob(static::getLockFileDirectory() .'/SCRIPT_LOCK*.lock');
	}

	public static function getLockFileDirectory() {
		return "/tmp";
	}

	private function getLockFilePath() {
		return static::getLockFileDirectory() . "/SCRIPT_LOCK_" . $this->lockFileLabel . ".lock";
	}

	public function lockProcess() {
		$runningCommandFrom = $this->getRunningCommandFrom();

		$lockFile = $this->getLockFilePath();

		if(file_exists($lockFile)) {
			$errorMessage = sprintf(date("r") . ": [ProcessLockFile] ERROR: Already running, only one instance allowed at a time (remove lock file manually if stuck: %s)", $lockFile) ;
			echo $errorMessage . PHP_EOL;
			Log::error($errorMessage);
			exit(1);
		}

		file_put_contents($lockFile, json_encode([
			"lock_file_id" => uniqid(),
			"date" => date("r"),
			"run_by" . $runningCommandFrom
		]));

		chmod($lockFile, 0777);
	}

	public function registerShutdownHandler($lockFile) {
		register_shutdown_function(function() use($lockFile) {
			echo "Shutdown handler: checking for lock file" . PHP_EOL;
			if($lockFile->isLocked()) {
				echo "Aborting and removing lock file" . PHP_EOL;
				$lockFile->unlockProcess();
			}
		});
	}

	public function isLocked() {
		return file_exists($this->getLockFilePath());
	}

	public function unlockProcess() {
		$lockFile = $this->getLockFilePath();

		if(!unlink($lockFile)) {
			throw new \RuntimeException("Could not remove lock file (" . $lockFile . ")");
		}
	}

	public function getRunningCommandFrom() {
		$runningCommandFrom = env("RUNNING_SCRIPT_FROM", null);

		if($runningCommandFrom !== null) {
			return "RUNNING_SCRIPT_FROM=" . $runningCommandFrom;
		}

		$runningCommandFrom = env("LOGNAME", null);

		if($runningCommandFrom !== null) {
			return "LOGNAME=" . $runningCommandFrom;
		}

		$runningCommandFrom = env("USER", null);

		if($runningCommandFrom !== null) {
			return "USER=" . $runningCommandFrom;
		}

		return "<not available>";
	}
}
