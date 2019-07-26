<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\DataTypes;

class MakeResult {
    static function success() {
        return new Result(1);
    }

    static function failure() {
        return new Result(0);
    }
}