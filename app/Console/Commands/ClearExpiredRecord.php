<?php

namespace App\Console\Commands;

use App\Models\Record;
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
        $expiredDays = config('datasource.expire-days');

        if ($expiredDays && $expiredDays > 0) {
            $time = date('Y-m-d H:i:s', strtotime('-'.$expiredDays.' days'));
            Record::where('created_at', '<', $time)->delete();
        }
    }
}