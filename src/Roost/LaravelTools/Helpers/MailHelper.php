<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailHelper
{
	const CONFIG_KEY_FOR_SHOULD_QUEUE = "mail.mailhelper-use-queue";

	private static $verbose = true;
	private static $SHOULD_QUEUE_BY_DEFAULT = false;
	private static $STATICS_HAVE_INITIALIZED = false;

	public static function send($to, $mailable, $queue = null) {
		/** @var Mailer $mail */
		$mailer = Mail::getFacadeRoot();

		$mail = (new PendingMail($mailer));

		$mailAddress = new MailAddress($to);

		static::sendMail($mail, $mailAddress, $mailable, $queue);
	}

	public static function sendToAddresses(MailAddress $mailAddress, $mailable, $queue = null) {
		/** @var Mailer $mail */
		$mailer = Mail::getFacadeRoot();

		/** @var PendingMail $mail */
		$mail = (new PendingMail($mailer));

		static::sendMail($mail,$mailAddress, $mailable, $queue);
	}

	public static function sendMail(PendingMail $mail, MailAddress $mailAddress, $mailable, $queue = null) {

		foreach($mailAddress->getAddresses() as $addressType => $emailAddresses) {
			switch($addressType) {
				case MailAddress::TO:
					$mail->to($emailAddresses);
					break;
				case MailAddress::CC:
					$mail->cc($emailAddresses);
					break;
				case MailAddress::BCC:
					$mail->bcc($emailAddresses);
					break;
			}
		}

		if($queue === null) {
			$queue = static::shouldQueue();
		}

		if($queue) {
			$mailResult = $mail->queue($mailable);
		} else {
			$mailResult = $mail->send($mailable);
		}

		if(static::$verbose) {
			$queued = $mailResult !== null;

			if($queued) {
				Log::info("Queued email to: " . json_encode($mailAddress->getAddresses(), true));
			} else {
				Log::info("Sent email to: " . json_encode($mailAddress->getAddresses(), true));
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