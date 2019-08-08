<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (Blue Nest Digital LLC, All rights reserved)
 * Copyright: Copyright 2019 Blue Nest Digital LLC
 */

namespace Roost\LaravelTools\Laravel\Notifications;


use Roost\LaravelTools\Laravel\Notifications\AdminAlert;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Log;

class AdminNotifier
{
	const FALLBACK_EMAIL = "admin@localhost";

	public static function notify(Notification $notification) {
		static::notifyRaw(
			$notification->subject,
			$notification->body,
			$notification->level,
			$notification->topic,
			$notification->channels
		);
	}

	public static function notifyRaw($subject, $body = null, $level = "CRITICAL", $topic = "", $channels = []) {
		$notificationsConfig = config("admin_notifications");

		if($notificationsConfig === null) {
			$notificationsConfig = [
				"default" => [
					"to" => [
						"email" => []
					],
					"channels" => ["database_admin_notifications", "log"]
				]
			];
		}

		$config = $notificationsConfig["default"];
		$configuredChannels = $config["channels"];

		if(!empty($channels)) {
			$configuredChannels = $channels;
		}

		$sentTo = [];

		$databaseNotification = null;
		if(in_array("database_admin_notifications", $configuredChannels)) {
			try {
				$databaseNotification = AdminDatabaseNotification::create([
					"subject" => $subject,
					"body" => $body,
					"sent_to" => "",
					"level" => $level,
					"topic" => $topic,
				]);
			} catch(\Exception $e) {
				Log::error("Could not create database admin notification: " . $e->getMessage());
			}
		}

		$notification = new AnonymousNotifiable();

		if(in_array("file", $configuredChannels)) {
			$notification = $notification->route('file', null);
			$sentTo[] = "file";
		}

		if(in_array("log", $configuredChannels)) {
			Log::log($level, "[ADMIN ALERT] [" . $subject . "] " . substr($body, 0, 100));
			$sentTo[] = "log";
		}

		if(in_array("email", $configuredChannels)) {
			if(isset($config["to"]["emails"])) {
				$emails = $config["to"]["emails"];
			} else {
				$emails = [static::FALLBACK_EMAIL];
			}

			$sentTo[] = implode(", ", $emails);
			$notification->route("email", $emails);
		}

		if(in_array("database", $configuredChannels)) {
			$notification->route("database", null);
			$sentTo[] = "database";
		}

		if($databaseNotification !== null) {
			$databaseNotification->update([
				"sent_to" => implode(", ", $sentTo)
			]);
		}

		$notification->notify(new AdminAlert($subject, $body));

		return true;
	}
}