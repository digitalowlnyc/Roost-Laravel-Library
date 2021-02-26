<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (Blue Nest Digital LLC, All rights reserved)
 * Copyright: Copyright 2020 Blue Nest Digital LLC
 */

namespace Roost\LaravelTools\Commands\Traits;


class InternalCommandEmailSender extends CommandEmailSender
{
	public function __construct() {
		parent::__construct();
		$this->setEmailSubjectSuffix(sprintf("[env=%s]", config("app.env", "")));
	}
}