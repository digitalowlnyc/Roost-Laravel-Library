<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Configs;

class ConfigHelper
{
    static function getArray($key, $default = null) {
        return self::getValue($key, 'array', $default);
    }

    static function getString($key, $default) {
        return self::getValue($key, 'string', $default);
    }

    static function getValue($key, $expectedType, $default = null) {
        $val = config($key, null);
        if($val === null) {
            if($default !== null) {
                return null;
            } else {
                throw new \Exception('Missing config value for key: ' . $key);
            }
        }

        if(gettype($val) !== $expectedType) {
            throw new \Exception('Expected type ' . surround($expectedType) . ' for key ' . surround($key) . ', found type ' . surround(gettype($val)));
        }

        return $val;
    }
}