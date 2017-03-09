<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Data;

class DataHelper
{
    static function ensureArray($value) {
        if(gettype($value) === 'object' && get_class($value) === 'Illuminate\Database\Eloquent\Collection') {
            $value = $value->toArray();
        }
        return $value;
    }
}