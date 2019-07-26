<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Laravel\Routes;

use Roost\LaravelTools\Laravel\Logging\UserLog;
use Illuminate\Routing\UrlGenerator;
use Roost\LaravelTools\Laravel\Exceptions\RouteNotDefinedException;

class RouteHelper extends UrlGenerator
{
    /** @var $generator \Illuminate\Routing\UrlGenerator */
    private $generator;

    function __construct(UrlGenerator $generator) {
        $this->generator = $generator;
    }

    static function build() {
        $generator = app(UrlGenerator::class);
        $new = new RouteHelper($generator);
        return $new;
    }

    function getRouteInstance($routeName) {
        /** @var $routeInstance \Illuminate\Routing\Route */
        $routeInstance = $this->generator->routes->getByName($routeName);

        if($routeInstance === null) {
            throw new RouteNotDefinedException('Route not defined: ' . $routeName);
        }

        return $routeInstance;
    }

    function toRouteHelper($routeName, $parameters = []) {
        UserLog::info('Redirecting to ' . print_r($routeName, true) . 'with parameters ' . print_r($parameters, true));
        $route = self::getRouteInstance($routeName);

        if($route === null) {
            throw new RouteNotDefinedException('Route not defined: ' . $route);
        }

        if(count($parameters)) {
            return redirect()->route($routeName, $parameters);
        } else {
            return redirect()->route($routeName);
        }
    }
}