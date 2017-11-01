<?php

namespace App\Jobs;

use DB;
use App\Models\Record;
use App\Service\GeoCoding;
use App\Models\LatestRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GeoCodingFetcher implements ShouldQueue
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
        $geoService = new GeoCoding();

        LatestRecord::whereNull('geometry_id')
            ->orderBy('record_id')
            ->chunk(100, function ($records) use ($geoService) {
                foreach ($records as $record) {
                    $geometry = $geoService->findLatLng($record->lat, $record->lng);

                    if ($geometry) {
                        DB::table('site_geometries')->insert([
                            'group_id'    => $record->group_id,
                            'uuid'        => $record->uuid,
                            'geometry_id' => $geometry->id,
                        ]);
                    }
                }
            });

        return false;

        Record::join('latest_records', function ($join) {
                $join->on('records.id', '=', 'latest_records.record_id');
                $join->whereNull('geometry_id');
                $join->where('latest_records.lat', '<>', 0);
                $join->where('latest_records.lng', '<>', 0);
            })
            ->take(500)
            ->get()
            ->map(function ($record) use ($geoService) {
                $bound = $geoService->findLatLng($record->lat, $record->lng);
                
                if ($bound) {
                    $record->geometries()->attach($bound->id);
                    return $record->id;
                }

                return false;
            });
    }
}
