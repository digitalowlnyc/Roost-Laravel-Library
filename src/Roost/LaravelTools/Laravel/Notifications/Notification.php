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
	public $channels = [];

	/**
	 * @param null $body
	 * @param null $subject
	 * @param null $level
	 * @return Notification
	 */
	static function with($subject = null, $body = null, $level = null) {
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

	/**
	 * @param $level
	 * @return Notification
	 */
	function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 * @param $body
	 * @return Notification
	 */
	function body($body) {
		$this->body = $body;
		return $this;
	}

	/**
	 * @param $subject
	 * @return Notification
	 */
	function subject($subject) {
		$this->subject = $subject;
		return $this;
	}

	function channels($channels) {
		$this->channels = $channels;
		return $this;
	}
}