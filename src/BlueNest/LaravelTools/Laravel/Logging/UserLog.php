<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Logging;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserLog
{
    private static function addUserInfo($str)
    {
        $user = Auth::user();
        if($user !== null) {
            $str .= '| user context=' . json_encode(['user-id' => $user->id]);
        } else {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'n/a';
            $str .= '| user context=' . json_encode(['ip' => $ip]);
        }
        return $str;
    }

    static function info($str)
    {
        Log::info(self::addUserInfo($str));
    }

    static function warning($str)
    {
        Log::warning(self::addUserInfo($str));
    }

    static function error($str)
    {
        Log::error(self::addUserInfo($str));
    }

    static function notice($str)
    {
        Log::notice(self::addUserInfo($str));
    }

    static function emergency($str)
    {
        Log::emergency(self::addUserInfo($str));
    }

    static function alert($str)
    {
        Log::alert(self::addUserInfo($str));
    }

    static function debug($str)
    {
        Log::debug(self::addUserInfo($str));
    }

    static function critical($str)
    {
        Log::critical(self::addUserInfo($str));
    }
}