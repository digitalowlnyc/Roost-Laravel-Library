<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use BlueNest\LaravelTools\API\BResponses;

class ApiRoute
{
    /**
     * Wrap the route handler to provide exception handling as JSON instead of potentially
     * having uncaught exceptions.
     *
     * @param $routeHandler
     * @return \Closure
     */
    static function createApiRouteHandler($routeHandler) {
        return function(Request $request) use ($routeHandler)
        {
            try {
                return $routeHandler($request);
            } catch(\Exception $e) {
                return BResponses::exception($e);
            }
        };
    }

    static function get($routeUrl, $routeHandler) {
        return Route::get($routeUrl, self::createApiRouteHandler($routeHandler));
    }

    static function post($routeUrl, $routeHandler) {
        return Route::post($routeUrl, self::createApiRouteHandler($routeHandler));
    }
}