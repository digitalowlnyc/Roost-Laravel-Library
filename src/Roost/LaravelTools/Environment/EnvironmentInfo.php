<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Environment;

class EnvironmentInfo
{
    private $isProduction;
    private $isTesting;
    private $isDebugOn;

    function __construct($isTesting, $isProduction, $isDebugOn) {
        $this->isTesting = $isTesting;
        $this->isProduction = $isProduction;
        $this->isDebugOn = $isDebugOn;
    }

    public function isProduction()
    {
        return $this->isProduction;
    }

    public function isTesting()
    {
        return $this->isTesting;
    }

    public function isDebugOn()
    {
        return $this->isDebugOn;
    }


}