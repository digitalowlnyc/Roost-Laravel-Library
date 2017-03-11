<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\API;

use BlueNest\LaravelTools\Laravel\Databases\DatabaseHelper;
use BlueNest\LaravelTools\Environment\EnvironmentHelper;
use Illuminate\Support\Facades\Log;

class BResponses {
    public static function respond($val) {
        $converted = BResponses::toArray($val);

        $apiResponse = [
            'response' => $converted
        ];

        $env = EnvironmentHelper::getEnvironmentDetails();

        if(!$env->isProduction() && $env->isDebugOn()) {
            $apiResponse['debug']['queries'] = DatabaseHelper::getQueries();
        }

        $options = JSON_UNESCAPED_SLASHES;
        if($env->isTesting() || $env->isDebugOn()) {
            $options |= JSON_PRETTY_PRINT;
        }
        return json_encode($apiResponse, $options);
    }

    public static function toArray($val) {
        if(gettype($val) === 'object' && method_exists($val, 'toArray')) {
            $val = $val->toArray();
        }
        return $val;
    }

    /**
     * @param $e \Exception
     * @return mixed|string|void
     */
    public static function exception($e) {
        Log::info($e->getTraceAsString());
        return BResponses::respond([
            'exception' => $e->getMessage(),
            'location' => $e->getFile() . ', Line #' . $e->getLine()
        ]);
    }
}