<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (Blue Nest Digital LLC, All rights reserved)
 * Copyright: Copyright 2019 Blue Nest Digital LLC
 */

namespace Roost\LaravelTools\Helpers;


class MailAddress
{
	public $to = [];
	public $cc = [];
	public $bcc = [];

	const TO = "to";
	const CC = "cc";
	const BCC = "bcc";

	public function __construct($to = null, $cc = null, $bcc = null) {
		if($to !== null) {
			$this->to($to);
		}

		if($cc !== null) {
			$this->cc($cc);
		}

		if($bcc !== null) {
			$this->bcc($bcc);
		}
	}

	public function to($val, $append = false) {
		if(!is_array($val)) {
			$val = [$val];
		}
		if(!$append) {
			$this->to = [];
		}

		foreach($val as $address) {
			$this->to[] = $address;
		}

		return $this;
	}

	public function cc($val, $append = false) {
		if(!is_array($val)) {
			$val = [$val];
		}
		if(!$append) {
			$this->cc = [];
		}

		foreach($val as $address) {
			$this->cc[] = $address;
		}

		return $this;
	}

	public function bcc($val, $append = false) {
		if(!is_array($val)) {
			$val = [$val];
		}
		if(!$append) {
			$this->bcc = [];
		}

		foreach($val as $address) {
			$this->bcc[] = $address;
		}

		return $this;
	}

	public function getAddresses() {
		$addresses = [];
		foreach([self::TO, self::CC, self::BCC] as $addressType) {
			if(!empty($this->{$addressType})) {
				$addresses[$addressType] = $this->{$addressType};
			}
		}

		return $addresses;
	}
}