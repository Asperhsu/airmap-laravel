<?php

namespace App\Jobs;

use App\Models\Record;
use App\Service\GeoCoding;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GeoCodingFetcher implements ShouldQueue
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
        $geoService = new GeoCoding();

        Record::doesntHave('geometries')
            ->get()
            ->map(function ($record) use ($geoService) {
                $bound = $geoService->findLatLng($record->lat, $record->lng);

                if ($bound) {
                    $record->geometries()->attach($bound->id);
                }
            });
    }
}
