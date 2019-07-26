<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Laravel\Logging;

use Roost\LaravelTools\Helpers\ExceptionHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserLog
{
    private static function addUserInfo($str)
    {
        $user = Auth::user();

        $requestId = isset($GLOBALS['unique-request-id']) ? $GLOBALS['unique-request-id'] : strval(time());

        if($user !== null) {
            $userInfo = json_encode(['user-id' => $user->id]);
        } else {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'n/a';
            $userInfo = json_encode(['ip' => $ip]);
        }

        $str .= ' | user-context=' . $userInfo;
        $str = '[request-id=' . $requestId . '] ' . $str;

        try {
            $logAppendBacktrace = ExceptionHelper::getCallerBacktraceLogInfo(__FILE__);
            if($logAppendBacktrace !== null) {
                $str = $logAppendBacktrace . ': ' . $str;
            }
        } catch(\Exception $e) {
            error_log('Warning: Exception determining backtrace in addUserInfo()');
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