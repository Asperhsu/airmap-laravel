<?php

namespace App\Console\Commands;

use App\Jobs\ClearExpiredRecord as Job;
use Illuminate\Console\Command;

class ClearExpiredRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear expired records';

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
        $job = new Job();
        dispatch($job);
    }
}