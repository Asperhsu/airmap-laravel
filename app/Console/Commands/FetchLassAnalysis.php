<?php

namespace App\Console\Commands;

use App\Jobs\LassAnalysisFetcher;
use Illuminate\Console\Command;

class FetchLassAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record:fetch-lass-analysis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch lass analysis';

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
        $job = new LassAnalysisFetcher();
        dispatch($job);
    }
}