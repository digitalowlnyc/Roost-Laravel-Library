<?php

namespace Roost\LaravelTools\Commands\Deployment;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Roost\LaravelTools\Commands\Models\QueueHeartbeat;
use Roost\LaravelTools\Laravel\Notifications\AdminNotifier;
use Roost\LaravelTools\Laravel\Notifications\Notification;
use Roost\LaravelTools\Laravel\Queue\QueueLog;

class DeploymentCheckHeartbeatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluenest:deployment:check-heartbeats {--staleness=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check heartbeats";

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
    public function handle()
    {
    	$stalenessCutoffInMinutes = $this->option("staleness");

    	if(!is_numeric($stalenessCutoffInMinutes)) {
    		$this->error("Staleness must be a number of minutes");
    		return;
		}

    	$stalenessCutoffInMinutes = floatval($stalenessCutoffInMinutes);

    	$queueHeartbeatClass = $this->getClassName(QueueHeartbeat::class);
        $heartbeat = QueueLog::query()
			->where("job_name", "like", "%" . $queueHeartbeatClass . "%")
			->orderBy("created_at", "desc")
			->limit(1)
			->get()
			->first();

        $foundValidHeartbeat = false;
        $heartbeatAgeInMinutes = null;
        if($heartbeat !== null) {
        	/** @var Carbon $heartbeatStartedAt */
        	$heartbeatStartedAt = $heartbeat->finish_time !== null ? $heartbeat->finish_time : $heartbeat->created_at;

			$heartbeatAgeInMinutes = $heartbeatStartedAt->diffInMinutes();
			if($heartbeatAgeInMinutes > $stalenessCutoffInMinutes) {
				$this->warn("Last heartbeat is too stale: " . $heartbeatAgeInMinutes . " mins ago (cutoff is configured to " . $stalenessCutoffInMinutes . ")");
			} else {
				$foundValidHeartbeat = true;
				$this->info("Found a valid heartbeat (" . $heartbeatAgeInMinutes . " mins old)");
			}
		} else {
        	$this->warn("No heartbeat found in queue log");
		}

        if(!$foundValidHeartbeat) {
        	$this->info("Sending an admin notification");
        	$notifcation = Notification::with()
				->level("CRITICAL")
				->subject("Heartbeat missed");

        	if($heartbeatAgeInMinutes === null) {
        		$notifcation->body("Queue has not had a heartbeat");
			} else {
        		$notifcation->body("Queue has not had a heartbeat in " . $heartbeatAgeInMinutes . " minutes");
			}

        	AdminNotifier::notify($notifcation);
        	$this->info("Sent an admin notification");
		}
    }

    function getClassName($class) {
    	$parts = explode("\\", $class);

    	end($parts);

    	return current($parts);
	}
}