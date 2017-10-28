<?php

namespace App\Service;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\LassAnalyse as Model;

class LassAnalyse {

    protected $malfunctionUrl = "https://data.lass-net.org/data/device_malfunction_daily.json";
    // protected $malfunctionUrl = "http://asper.tw/device_malfunction_daily.json";

    protected $pollutionUrl = "https://data.lass-net.org/data/device_pollution.json";
    // protected $pollutionUrl = "http://asper.tw/device_pollution.json";
    
    protected $rankingUrl = "https://data.lass-net.org/data/device_ranking.json";
    // protected $rankingUrl = "http://asper.tw/device_ranking.json";

    protected $analysis;
    
    public function __construct() {
        $this->analysis = collect();
    }

    public function fetch()
    {
        try {
            $this->fetchMalfunction();
            $this->fetchShortTermPollution();
            $this->fetchRanking();

            $uuids      = $this->analysis->keys()->toArray();
            $existsUUID = $this->existsUUID();
            $insertUUID = array_diff($uuids, $existsUUID);
            $updateUUID = array_intersect($uuids, $existsUUID);

            // dd($existsUUID, $insertUUID, $updateUUID);

            $this->insert($insertUUID);
            $this->update($updateUUID);
        } catch (\Exception $e) {
            logger('fetch analysis failed. '.$e);
        }
    }


    /* DB Operation */

    protected function existsUUID()
    {
        return Model::select('uuid')->get()->pluck('uuid')->toArray();
    }

    protected function insert(array $uuids)
    {
        $inserts = $this->analysis->only($uuids)->map(function ($item, $uuid) {
            return $this->toRecord($uuid);
        })->toArray();

        // dd($inserts);
        return Model::insert($inserts);
    }

    protected function update(array $uuids)
    {
        return $this->analysis->only($uuids)->map(function ($item, $uuid) {
            $data = $this->toRecord($uuid, true);

            // dd($data);
            return DB::table('lass_analyses')->where('uuid', (string) $uuid)->update($data);
        });
    }


    /* Data Structure */

    protected function analysis(string $uuid, array $attributes=null)
    {
        if ($this->analysis->has($uuid)) {
            $item = $this->analysis->get($uuid);

            if ($attributes) {
                $item = $item->merge($attributes);
                $this->analysis->put($uuid, $item);
            }

            return $item;
        }

        if ($attributes) {
            $item = collect([
                'indoor' => false,
                'shortterm_pollution' => false,
                'longterm_pollution' => false,
                'ranking' => null,
                'malfunction_at' => null,
                'pollution_at' => null,
                'ranking_at' => null,
            ])->merge($attributes);

            $this->analysis->put($uuid, $item);

            return $item;
        }

        return null;
    }

    protected function toRecord(string $uuid, bool $isUpdate=false)
    {
        $item = $this->analysis($uuid);

        $data = array_merge($item->toArray(), [
            'malfunction_at' => $item->get('malfunction_at') ? $item->get('malfunction_at')->toDatetimeString() : null,
            'pollution_at'   => $item->get('pollution_at') ? $item->get('pollution_at')->toDatetimeString() : null,
            'ranking_at'     => $item->get('ranking_at') ? $item->get('ranking_at')->toDatetimeString() : null,
        ]);

        if ($isUpdate) {
            $data = array_merge($data, [
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        } else {
            $data = array_merge($data, [
                'uuid'       => (string) $uuid,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }
        
        return $data;
    }


    /* Fetch and Parse source */

    /**
	 * malfunction manual
	 * "type-1": "non-detectable", 代表鄰居太少無從比較
	 * "type-2": "spatially greater", 代表接近長時間的固定污染源或機器故障
	 * "type-3": "spatially less" 代表可能為室內機或機器故障
	 * rate <= 0.3 is false, other is true
	 */
    public function fetchMalfunction()
    {
        $response = HttpClient::getJson($this->malfunctionUrl);
        $version = Carbon::parse($response['data']['version'])->timezone('UTC');

        foreach ($response['data']['feeds'] as $feed) {
            $uuid = $feed['device_id'];
            $attributes = [
                'malfunction_at' => $version,
            ];

            if ($this->isBelong($feed["2"])) {
                $attributes['longterm_pollution'] = true;
            }

            if ($this->isBelong($feed["3"])) {
                $attributes['indoor'] = true;
            }

            $this->analysis($uuid, $attributes);
        }

        return true;
    }

    public function fetchShortTermPollution()
    {
        $response = HttpClient::getJson($this->pollutionUrl);

        foreach ($response['data']['feeds'] as $feed) {
            $uuid = $feed['device_id'];
            $attributes = [
                'shortterm_pollution' => true,
                'pollution_at' => $this->parseTZLocalTIme($feed['timestamp']),
            ];

            $this->analysis($uuid, $attributes);
        }
        
        return true;
    }

    public function fetchRanking()
    {
        $response = HttpClient::getJson($this->rankingUrl);
        
        foreach ($response['data']['feeds'] as $feed) {
            $uuid = $feed['device_id'];
            $attributes = [
                'ranking' => $this->rankingToLevel($feed['ranking']),
                'ranking_at' => $this->parseTZLocalTIme($feed['timestamp']),
            ];
            
            $this->analysis($uuid, $attributes);
        }
        
        return true;
    }


    /* Helpers */

    public function isBelong ($rate){
        return $rate <= 0.3 ? false : true;
    }

    public function rankingToLevel(float $ranking)
    {
        if( $ranking < 0.5 ){ return 0; }
        if( $ranking >= 0.5 && $ranking < 0.6 ){ return 1; }
        if( $ranking >= 0.6 && $ranking < 0.7 ){ return 2; }
        if( $ranking >= 0.7 && $ranking < 0.8 ){ return 3; }
        if( $ranking >= 0.8 && $ranking < 0.9 ){ return 4; }
        if( $ranking >= 0.9 && $ranking <= 1 ){ return 5; }

        return null;
    }

    public function parseTZLocalTIme(string $timeString)
    {
        return Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $timeString, 'Asia/Taipei')->timezone('UTC');
    }

}