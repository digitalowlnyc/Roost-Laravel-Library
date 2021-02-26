<?php

namespace Roost\LaravelTools\Commands\Deployment;

use Illuminate\Support\Facades\Log;
use Roost\LaravelTools\Commands\AppBaseCommand;
use Roost\LaravelTools\Commands\Lib\ProcessLockFile;
use Roost\LaravelTools\Commands\Traits\CommandEmailSender;
use Roost\LaravelTools\Commands\Traits\InternalCommandEmailSender;

class CheckProcessLockFiles extends AppBaseCommand
{
	const STALE_ELAPSED_TIME_LIMIT_SECONDS = 120;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'roost:commands:check-process-lock-files';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = __CLASS__;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handleCommand()
	{
		$notificationEmailTo = config("custom.alerting.admin.emails", []);

		if(empty($notificationEmailTo)) {
			echo "Must provide notification email" . PHP_EOL;
			exit(1);
		}

		$lockFiles = ProcessLockFile::getAllLockFiles();

		printLine(sprintf("Found %s files to check", count($lockFiles)));

		$staleLockFiles = [];
		foreach($lockFiles as $lockFile) {
			printLine(sprintf("Checking file %s", $lockFile));
			if(time() - filemtime($lockFile) > static::STALE_ELAPSED_TIME_LIMIT_SECONDS) {
				$staleLockFiles[] = $lockFile;
			}
		}

		if(!empty($staleLockFiles)) {
			$subject = "Lock files need cleaning";
			$html = sprintf("The following lock files need cleaning:<br>%s", implode("<br>", $staleLockFiles));
			$this->emailOrNotify($subject, $html, $notificationEmailTo);
		}

		return true;
	}

	public function emailOrNotify($subject, $html, $notificationEmailTo) {
		if(!empty($notificationEmailTo)) {
			(new InternalCommandEmailSender())->sendHtmlEmail($subject, $html, $notificationEmailTo);
		} else {
			Log::alert($subject);
		}
	}
}
