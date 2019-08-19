<?php

namespace Roost\LaravelTools\Commands;

use Roost\LaravelTools\Helpers\AppHelper;
use Roost\Logging\GlobalLogging;
use Illuminate\Console\Command as IlluminateCommand;

abstract class AppBaseCommand extends IlluminateCommand {
	use AppCommandTrait;

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
}