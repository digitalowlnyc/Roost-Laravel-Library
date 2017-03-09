<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

class RequestHelper {
    public static function internalRequest($url, $method) {
        $newRequest = Request::create($url, $method, Input::get());
        $data = Route::dispatch($newRequest)->getContent();
        return $data;
    }
}