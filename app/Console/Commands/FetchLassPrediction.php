<?php

namespace App\Console\Commands;

use App\Jobs\LassPredictionFetcher;
use Illuminate\Console\Command;

class FetchLassPrediction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:lass-prediction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch lass prediction';

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
        $job = new LassPredictionFetcher();
        dispatch($job);
    }
}
