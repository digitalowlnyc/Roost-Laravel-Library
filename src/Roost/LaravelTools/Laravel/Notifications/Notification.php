<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (Blue Nest Digital LLC, All rights reserved)
 * Copyright: Copyright 2019 Blue Nest Digital LLC
 */

namespace Roost\LaravelTools\Laravel\Notifications;

class Notification {
	public $body = null;
	public $subject = null;
	public $level = "CRITICAL";

	static function with($body = null, $subject = null, $level = null) {
		$me = new self;

		if($level !== null) {
			$me->level = $level;
		}
		if($body !== null) {
			$me->body = $body;
		}
		if($subject !== null) {
			$me->subject = $subject;
		}

		return $me;
	}

	function level($level) {
		$this->level = $level;
		return $this;
	}

	function body($body) {
		$this->body = $body;
		return $this;
	}

	function subject($subject) {
		$this->subject = $subject;
		return $this;
	}
}