<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Sessions;

use BlueNest\LaravelTools\Laravel\Exceptions\JsonEncodingException;
use BlueNest\LaravelTools\Laravel\Exceptions\SessionMissingValueException;

class AppSessionManager
{
    const APP_SESSION_KEY = 'APP_SESSION';

    static function put($key, $value) {
        $sessionArray = self::getAppSession();
        $sessionArray[$key] = $value;
        self::saveAppSession($sessionArray);
    }

    static function delete($key) {
        $sessionArray = self::getAppSession();
        unset($sessionArray[$key]);
        self::saveAppSession($sessionArray);
    }

    static function get($key) {
        checkVarType($key, 'string');
        $sessionArray = self::getAppSession();

        if(!isset($sessionArray[$key])) {
            throw new SessionMissingValueException('Missing value for: ' . $key);
        }

        return $sessionArray[$key];
    }

    static function getOptional($key) {
        checkVarType($key, 'string');
        $sessionArray = self::getAppSession();

        if(!isset($sessionArray[$key])) {
            return null;
        }

        return $sessionArray[$key];
    }

    static function getAppSession() {
        $appSession = session()->get(self::APP_SESSION_KEY);
        if($appSession !== null) {
            $appSession = json_decode($appSession, true);
        } else {
            $appSession = [];
        }

        return $appSession;
    }

    static function saveAppSession(Array $appSessionArray) {
        $encodedValue = json_encode($appSessionArray);
        if($encodedValue === false) {
            throw new JsonEncodingException($appSessionArray);
        }
        session()->put(self::APP_SESSION_KEY, $encodedValue);
    }
}