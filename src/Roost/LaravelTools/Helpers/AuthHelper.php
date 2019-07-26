<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthHelper
{
	public static function getGuards() {
		$guardConfig = config("auth.guards");

		if(is_array($guardConfig)) {
			return array_keys($guardConfig);
		} else {
			return [];
		}
	}

	public static function getSessionGuards() {
		$guardConfig = config("auth.guards");

		$sessionGuards = [];
		foreach($guardConfig as $guardName => $guard) {
			if(stringIsEqual($guard["driver"], "session")) {
				$sessionGuards[] = $guardName;
			}
		}

		return $sessionGuards;
	}

	public static function getGuardsExcept($excludedGuards) {
		if(!is_array($excludedGuards)) {
			$excludedGuards = [$excludedGuards];
		}
		$otherGuards = array_keys(Arr::except(array_flip(static::getGuards()), $excludedGuards));
		return $otherGuards;
	}

	public static function login(Authenticatable $user, bool $remember, string $guard = null) {
		static::singleLogin($guard);
		Auth::shouldUse($guard);
		Auth::login($user, $remember);

		Log::debug(__CLASS__ . ": logged in user id=" . $user->getAuthIdentifier() . " with guard " . $guard);
	}

	public static function singleLogin(string $guard) {
		Log::debug("AuthHelper::singleLogin called with: " . $guard);
		$otherGuards = static::getGuardsExcept([$guard]);
		static::logOutGuards($otherGuards);
	}

	public static function logOutAll() {
		$allGuards = static::getGuards();
		static::logOutGuards($allGuards);
	}

	public static function logOutGuards($guards) {
		Log::debug("logOutGuards: " . json_encode($guards));
		foreach($guards as $otherGuard) {

			/** @var SessionGuard $guard */
			$guard = Auth::guard($otherGuard);
			//Auth::shouldUse($otherGuard);

			if(!($guard instanceof SessionGuard)) {
				continue;
			}

			$guard->getSession()->remove("password_hash");

			if($guard->check()) {
				$otherUser = $guard->user();
				Log::debug(__CLASS__ . ": logged out user id=" . $otherUser->getAuthIdentifier() . " with guard " . $otherGuard);
				$guard->logout();
			} else {
				Log::debug(__CLASS__ . ": user not logged with guard " . $otherGuard);
			}
		}
	}
}