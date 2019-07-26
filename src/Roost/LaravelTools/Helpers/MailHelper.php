<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailHelper
{
	const CONFIG_KEY_FOR_SHOULD_QUEUE = "mail.mailhelper-use-queue";

	private static $verbose = true;
	private static $SHOULD_QUEUE_BY_DEFAULT = false;
	private static $STATICS_HAVE_INITIALIZED = false;

	public static function send($to, $mailable, $queue = null) {
		if($queue === null) {
			$queue = static::shouldQueue();
		}

		$mail = Mail::to($to);

		if($queue) {
			$mailResult = $mail->queue($mailable);
		} else {
			$mailResult = $mail->send($mailable);
		}

		if(static::$verbose) {
			$queued = $mailResult !== null;

			if($queued) {
				Log::info("Queued email to: " . $to);
			} else {
				Log::info("Sent email to: ". $to);
			}
		}
	}

	public static function shouldQueue() {
		static::initializeStatics();

		return static::$SHOULD_QUEUE_BY_DEFAULT;
	}

	public static function setVerbose($verbose) {
		static::$verbose = $verbose;
	}

	public static function initializeStatics() {
		if(!static::$STATICS_HAVE_INITIALIZED) {
			static::$SHOULD_QUEUE_BY_DEFAULT = config(static::CONFIG_KEY_FOR_SHOULD_QUEUE, static::$SHOULD_QUEUE_BY_DEFAULT);
			static::$STATICS_HAVE_INITIALIZED = true;
		}
	}
}