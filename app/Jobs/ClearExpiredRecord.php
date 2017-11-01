<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Record;
use DB;

class ClearExpiredRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $expiredDays = config('datasource.expire-days');

        if ($expiredDays && $expiredDays > 0) {
            $time = date('Y-m-d H:i:s', strtotime('-'.$expiredDays.' days'));
            DB::table('records')->where('created_at', '<', $time)->delete();
        }
    }
}