<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Laravel\Exceptions;


class RouteNotDefinedException extends \Exception
{
    function __construct($routeName) {
        $message = 'Route is not defined: ' . print_r($routeName, true);
        parent::__construct($message, 0, null);
    }
}