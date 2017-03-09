<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\DataTypes;

class Result
{
    private $status = 0;

    function __construct($status) {
        $this->status = $status;
    }

    static function returnOk() {
        return new Result(1);
    }

    static function returnNotOk() {
        return new Result(0);
    }

    function isOk() {
        if($this->status >= 1) {
            return true;
        }
    }
}