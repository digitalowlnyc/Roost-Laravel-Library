<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Environment;

class EnvironmentHelper {

    static function getEnvironmentDetails() {
        $isTesting = (config('app.env', 'production') == 'testing');
        $isProduction = (config('app.env', 'production') == 'testing');
        $isDebugOn = config('app.debug', false);
        return new EnvironmentInfo($isTesting, $isProduction, $isDebugOn);
    }
}