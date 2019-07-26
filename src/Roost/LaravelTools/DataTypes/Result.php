<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\DataTypes;

class Result
{
    private $status = 0;
    private $errorMessages = [];

    function __construct($status) {
        $this->status = $status;
    }

    function succeeded() {
        return $this->status >= 1;
    }

    function getErrors() {
        return $this->errorMessages;
    }

    function withError($errorMsg) {
        $this->errorMessages[] = $errorMsg;
        return $this;
    }
}