<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;

class FetchDatasource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:record {--G|group=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch groups records';

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
        $group = $this->option('group');
        
        $where = [
            ['enable', '=', true]
        ];

        if ($group !== 'all') {
            $where[] = ['name', '=', $group];
        }
        
        Group::where($where)->each(function ($group) {
            $handler = $group->handler;

            if (class_exists($handler)) {
                $job = new $handler($group);
                dispatch($job);
            }
        });
    }
}