<?php

namespace Roost\LaravelTools\Commands\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QueueHeartbeat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var int
	 */
	private $queuedAtTime;
	private $emailTo;

	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailTo = null)
    {
        $this->queuedAtTime = time();
        $this->emailTo = $emailTo;
    }

    private function echoAndLog($message) {
    	echo date("r") . ": " . $message . PHP_EOL;
    	Log::info($message);
	}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    	$message = "Queue heartbeat job seen (job was queued at " . date("r", $this->queuedAtTime) . ")";
        $this->echoAndLog($message);

		$to = $this->emailTo;

		if(!empty($to)) {
			Mail::raw($message, function (Message $message) use ($to) {
				echo "Sending email to : " . $to . PHP_EOL;
				$message->to($to);
				$message->subject("Queue heartbeat");
			});
		}
    }
}
