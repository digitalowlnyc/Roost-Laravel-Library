<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Helpers;

class PhpHelpers {
    /**
     * Prints in array as a PHP-parseable array, for example:
     * ['hello' => 'world', 'more' => ['data', 'is', 'here' ]]
     *
     * @param $arr
     * @return mixed|string
     */
    public static function toPhpArrayString($arr) {
        // BND FIXME: Cannot replace : with => because it may occur as a value, such as
        // a url (e.g. http://www.example.com)
        $encoded = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return str_replace(array('{', '}'), array('[', ']'), $encoded);
    }
}
