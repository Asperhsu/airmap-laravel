<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\JSONRepository;

class UpdateJSON extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record:update-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update json cache';

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
        JSONRepository::groups();
    }
}
