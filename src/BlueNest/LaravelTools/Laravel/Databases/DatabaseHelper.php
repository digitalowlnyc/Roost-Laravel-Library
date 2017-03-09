<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Databases;

use DB;

class DatabaseHelper {
    public static function getQueries() {
        return DB::getQueryLog();
    }
}