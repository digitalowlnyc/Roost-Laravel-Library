<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Helpers;


class OutputHelper
{
    const CLEAR_SEQUENCE = "\e[0m";

    public static function highlight($str) {
        return "\e[41;97m" .$str . OutputHelper::CLEAR_SEQUENCE;
    }

    public static function banner($msg) {
        $banner = PHP_EOL . str_repeat('=', 100) . PHP_EOL;
        return $banner . $msg . $banner;
    }
}