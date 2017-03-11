<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Logging;

use Auth;
use Log;

class UserLog
{
    static function Info($str) {
        $str .= json_encode(['user-id' => Auth::user()->id]);
        Log::Info($str);
    }
}