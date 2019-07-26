<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Laravel\Databases;

use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
	public static function getUniqueAlphaNumeric($length)
	{
		return str_random($length);
	}

	public static function enableQueryLogging()
	{
		DB::connection()->enableQueryLog();
	}

    public static function getQueries() {
        return DB::getQueryLog();
    }
}