<?php

namespace Roost\LaravelTools\Commands\Deployment;

use Illuminate\Console\Command;
use Roost\LaravelTools\Commands\Models\QueueHeartbeat;

class ApplicationTriggerHeartbeatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluenest:deployment:trigger-heartbeats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Trigger heartbeats";

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
    	$this->info("Dispatching heartbeat");
        QueueHeartbeat::dispatch();
        $this->info("Done");
    }
}
