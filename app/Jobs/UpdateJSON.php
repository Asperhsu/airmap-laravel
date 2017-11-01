<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Group;
use App\Service\JsonCache;
use App\Repository\JSONRepository;

class UpdateJSON implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job retry times
     *
     * @var integer
     */
    public $tries = 1;
    
    /**
     * Job exec timeout
     *
     * @var integer
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Group::where('enable', true)->each(function ($group) {
            JsonCache::forgetGroup($group->id);
        });
        
        JSONRepository::groups();
    }
}