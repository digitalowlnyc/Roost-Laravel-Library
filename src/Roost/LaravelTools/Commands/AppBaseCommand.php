<?php

namespace Roost\LaravelTools\Commands;

use Roost\LaravelTools\Helpers\AppHelper;
use Roost\Logging\GlobalLogging;
use Illuminate\Console\Command as IlluminateCommand;

abstract class AppBaseCommand extends IlluminateCommand {
	use AppCommandTrait;

	/**
	 * @var array
	 */
	private $commandPreHandlers = [];

	/**
	 * @var array
	 */
	private $commandPostHandlers = [];

	abstract function handleCommand();

	public function exitWithFailure($msg, $exitCode = 1) {
		echo $msg . PHP_EOL;
		exit($exitCode);
	}

	public function proceedPrompt($prompt = "") {
		if(AppHelper::isDevelopmentOrDebuggingEnabled()) {
			$fullPrompt = "Proceed?";
			if($prompt !== "") {
				$fullPrompt = $prompt . ". " . $fullPrompt;
			}
			$val = $this->askYesNo($fullPrompt);
			$this->proceedIf($val);
		}
	}

	public function askYesNo($question) {

		do {
			$answer = $this->ask($question);

			$answer = strtolower(trim($answer));

			if(in_array($answer, ["y", "yes", "1"])) {
				$answer = true;
			} elseif(in_array($answer, ["n", "no", "0"])) {
				$answer = false;
			} else {
				$answer = null;
				$this->info("Answer must be (y)es/(n)o");
			}
		} while($answer === null);

		return $answer;
	}

	public function proceedIf($val) {
		if($val) {
			return;
		} else {
			$this->info("Exiting");
			exit;
		}
	}

	public function registerCommandPreHandler(callable $callable) {
		$this->commandPreHandlers[] = $callable;
	}

	public function handle() {
		$this->markCommandRunning();

		foreach($this->commandPreHandlers as $commandPreHandler) {
			$commandPreHandler($this);
		}
		$this->info("Command processing started at: " . date("r"));

		$commandResult = $this->handleCommand();

		foreach($this->commandPostHandlers as $commandPostHandler) {
			$commandPostHandler($this);
		}

		$this->info("Command processing finished at: " . date("r"));

		return $commandResult;
	}

	private function markCommandRunning() {
		$commandMarkerFile = str_replace("\\", "", __CLASS__);

		file_put_contents("/tmp/_running_marker_" . $commandMarkerFile . ".marker", date("r"));
	}
}
