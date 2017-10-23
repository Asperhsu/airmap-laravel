<?php

namespace App\Console\Commands;

use App\Jobs\GeoCodingFetcher;
use Illuminate\Console\Command;

class FetchGeoCoding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:geocoding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch geocoding for records';

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
        $job = new GeoCodingFetcher();
        dispatch($job);
    }
}
