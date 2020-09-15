<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (Blue Nest Digital LLC, All rights reserved)
 * Copyright: Copyright 2020 Blue Nest Digital LLC
 */

namespace Roost\LaravelTools\Commands\Traits;


use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

trait CommandSendsEmailTrait
{
	protected function sendTextEmail($subject, $body, $to, $cc = [], $bcc = []) {
		$this->sendEmail($subject, $body, "TEXT", $to, $cc, $bcc);
	}

	protected function sendHtmlEmail($subject, $body, $to, $cc = [], $bcc = []) {
		$this->sendEmail($subject, $body, "HTML", $to, $cc, $bcc);
	}

	private function sendEmail($subject, $body, $type, $to, $cc = [], $bcc = []) {
		if(!is_string($subject)) {
			throw new \InvalidArgumentException("subject must be a string");
		}
		if(!is_string($body)) {
			throw new \InvalidArgumentException("body must be a string");
		}

		Mail::send([], [], function (Message $message) use ($subject, $to, $cc, $bcc, $body, $type) {
			switch($type) {
				case "HTML":
					$message->setBody($body, 'text/html');
					break;
				case "TEXT":
					$message->setBody($body);
					break;
				default:
					throw new \RuntimeException("Unsupported type argument: " . $type);

			}

			$message->to($to);

			if(!empty($cc)) {
				$message->cc($cc);
			}
			if(!empty($bcc)) {
				$message->bcc($bcc);
			}

			$message->subject($subject);
		});

		$logMessage = "Sent email: subject=" . $subject . ", to=" . json_encode($to);

		if(!empty($cc)) {
			$logMessage .= ", cc=" . json_encode($cc);
		}

		if(!empty($bcc)) {
			$logMessage .= ", bcc=" . json_encode($bcc);
		}

		echo $logMessage . PHP_EOL;
	}
}