<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class DebugHelper
{
	public static function bootstrap() {
		if(!AppHelper::isDebuggingEnabled()) {
    		return;
		}

		$user = Cookie::get("debug_user");

    	if(empty($user)) {
    		return;
		}

		$encrypter = app(\Illuminate\Contracts\Encryption\Encrypter::class);
    	$user = $encrypter->decrypt($user, false);

    	$overrideFileName = base_path(".config_overrides_" . $user . ".ini");

    	if($overrideFileName) {
			$settings = parse_ini_file($overrideFileName);
			if($settings !== null) {
				foreach($settings as $setting => $value) {
					EnvHelper::setOverride($setting, $value);
				}
			} else {
				Log::error("Could not load env overrides");
			}
		}
	}
}