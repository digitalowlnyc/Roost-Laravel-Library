<?php

namespace Roost\LaravelTools\Helpers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class RedirectHelper
{
	/**
	 * @param null $to
	 * @param int $status
	 * @param array $headers
	 * @param null $secure
	 * @return RedirectResponse|\Illuminate\Routing\Redirector
	 */
	static function redirect($to = null, $status = 302, $headers = [], $secure = null) {
		Log::debug("RedirectHelper: redirect to: " . $to);
		return redirect($to, $status, $headers, $secure);
	}

	/**
	 * @param RedirectResponse $redirectResponse
	 * @return RedirectResponse
	 */
	static function redirectWithResponse(RedirectResponse $redirectResponse) {
		Log::debug("RedirectHelper: redirect with response: " . $redirectResponse->getTargetUrl());
		return $redirectResponse;
	}

	/**
	 * @param $url
	 * @return mixed
	 */
	static function redirectWithUrl($url) {
		Log::debug("RedirectHelper: redirect with url: " . $url);
		return $url;
	}
}