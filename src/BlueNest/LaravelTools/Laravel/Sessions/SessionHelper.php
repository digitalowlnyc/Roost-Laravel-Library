<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Sessions;

use BlueNest\LaravelTools\Laravel\Exceptions\SessionMissingValueException;

class SessionHelper
{
    static function valuesFromSession($keys) {
        $sessionArray = AppSessionManager::getAppSession();

        $sessionValues = [];
        foreach($keys as $key) {
            if(!isset($sessionArray[$key])) {
                throw new SessionMissingValueException('Value is missing from session: ' . $key);
            }
            $sessionValues[$key] = $sessionArray[$key];
        }

        return $sessionValues;
    }
}