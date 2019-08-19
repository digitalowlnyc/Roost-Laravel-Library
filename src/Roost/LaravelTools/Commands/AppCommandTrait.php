<?php


namespace Roost\LaravelTools\Commands;

trait AppCommandTrait
{
	public function getAnOption($option, $default = null) {
		if($this->hasOption($option)) {
			return $this->option($option);
		} else {
			return $default;
		}
	}

	public function getAnArgument($option, $default = null) {
		if($this->hasArgument($option)) {
			return $this->argument($option);
		} else {
			return $default;
		}
	}

	public function appendToSignature($append) {
    	$this->signature .= PHP_EOL . $append;
	}

	public function replaceSignature($replace, $replaceWith) {
		$this->signature = str_replace($replace, $replaceWith, $this->signature);
	}
}