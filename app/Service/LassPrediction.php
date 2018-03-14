<?php

namespace App\Service;

use App\Models\LassPrediction as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Formatter\PredictionToChartFormatter;

class LassPrediction
{
    const CACHE_TAG = 'LASS_PREDICTION';

    protected $predictions;

    protected $mahajanUrlTemplate = "https://pm25next.lass-net.org/static/overview_{{YmdH}}_0.json";
    protected $yangUrlTemplate = "https://pm25next.lass-net.org/static/overview_{{YmdH}}_1.json";


    public function __construct()
    {
        $this->predictions = collect();
    }

    public function fetch()
    {
        Cache::tags(static::CACHE_TAG)->forget('records');

        try {
            $this->fetchMahajan();
            $this->fetchYang();

            list('insert' => $insertUUID, 'update' => $updateUUID) = $this->findDifference();

            $this->insert($insertUUID);
            $this->update($updateUUID);
        } catch (\Exception $e) {
            logger('fetch predictions failed. '.$e);
        }
    }


    /* DB Operation */

    protected function findDifference()
    {
        $predictionsKeys = $this->predictions->map(function ($item, $uuid) {
            return $uuid . '-' . $item->get('method');
        })->values()->toArray();

        $existsKeys = Model::select('uuid', 'method')->get()
            ->map(function ($item) {
                return $item->uuid . '-' . $item->method;
            })
            ->values()->toArray();

        $insert = array_diff($predictionsKeys, $existsKeys);
        $update = array_intersect($predictionsKeys, $existsKeys);

        return compact('insert', 'update');
    }

    protected function insert(array $keys)
    {
        $inserts = $this->predictions->filter(function ($item, $uuid) use ($keys) {
            $key = $uuid . '-' . $item->get('method');
            return in_array($key, $keys);
        })->map(function ($item, $uuid) {
            return $this->toRecord($uuid);
        })->toArray();

        return Model::insert($inserts);
    }

    protected function update(array $keys)
    {
        return $this->predictions->filter(function ($item, $uuid) use ($keys) {
            $key = $uuid . '-' . $item->get('method');
            return in_array($key, $keys);
        })->map(function ($item, $uuid) {
            $data = $this->toRecord($uuid, true);

            // dd($data);
            return DB::table('lass_predictions')
                ->where('uuid', (string) $uuid)
                ->where('method', $item->get('method'))
                ->update($data);
        })->toArray();
    }


    /* Data Structure */

    protected function prediction(string $uuid, array $attributes=null)
    {
        if ($this->predictions->has($uuid)) {
            $item = $this->predictions->get($uuid);

            if ($attributes) {
                $item = $item->merge($attributes);
                $this->predictions->put($uuid, $item);
            }

            return $item;
        }

        if ($attributes) {
            $item = collect([
                'method' => null,
                'current' => null,
                'add1h' => null,
                'add2h' => null,
                'add3h' => null,
                'add4h' => null,
                'add5h' => null,
            ])->merge($attributes);

            $this->predictions->put($uuid, $item);

            return $item;
        }

        return null;
    }

    protected function toRecord(string $uuid, bool $isUpdate=false)
    {
        $item = $this->prediction($uuid);

        if ($isUpdate) {
            $data = array_merge($item->toArray(), [
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        } else {
            $data = array_merge($item->toArray(), [
                'uuid'       => (string) $uuid,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        return $data;
    }


    /* Fetch and Parse source */

    protected function fetchAPI(string $method, string $url)
    {
        $response = HttpClient::getJson($url);

        if (!$response['data']) {
            return false;
        }

        $version = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $response['data']['version'], 'Asia/Taipei')->timezone('UTC');

        foreach ($response['data']['feed'] as $feed) {
            $uuid = $feed['device_id'];

            $attributes = [
                'method' => $method,
                'current' => is_null($feed['now']) ? null : floatval($feed['now']),
                'add1h' => is_null($feed['now+1h']) ? null : floatval($feed['now+1h']),
                'add2h' => is_null($feed['now+2h']) ? null : floatval($feed['now+2h']),
                'add3h' => is_null($feed['now+3h']) ? null : floatval($feed['now+3h']),
                'add4h' => is_null($feed['now+4h']) ? null : floatval($feed['now+4h']),
                'add5h' => is_null($feed['now+5h']) ? null : floatval($feed['now+5h']),
                'published_at' => $version,
            ];

            if (is_null($attributes['add1h'])
                && is_null($attributes['add2h'])
                && is_null($attributes['add3h'])
                && is_null($attributes['add4h'])
                && is_null($attributes['add5h'])
            ) {
                continue;
            }

            $this->prediction($uuid, $attributes);
        }

        return true;
    }

    public function fetchMahajan()
    {
        // Mahajan is 1 hours behind
        $date = Carbon::now('Asia/Taipei')->subHours(1)->format('YmdH');
        $url = str_replace('{{YmdH}}', $date, $this->mahajanUrlTemplate);

        return $this->fetchAPI('Mahajan', $url);
    }

    public function fetchYang()
    {
        // Yang is 1 hours behind
        $date = Carbon::now('Asia/Taipei')->subHours(1)->format('YmdH');
        $url = str_replace('{{YmdH}}', $date, $this->yangUrlTemplate);

        return $this->fetchAPI('Yang', $url);
    }


    /* Load exists results */

    protected function validRecords()
    {
        if (Cache::tags(static::CACHE_TAG)->has('records')) {
            return Cache::tags(static::CACHE_TAG)->get('records');
        }

        $records = DB::table('lass_predictions')
            ->orderBy('method')
            ->orderBy('published_at', 'desc')
            ->get();

        Cache::tags(static::CACHE_TAG)->put('records', $records, 60);
        return $records;
    }

    public function findUuid(string $uuid)
    {
        $dataset = collect();
        $records = $this->validRecords()->where('uuid', $uuid);

        $records->groupBy('method')->map(function ($items, $method) use ($dataset) {
            $values = collect();
            $item = $items->first();
            $publishedAt = Carbon::parse($item->published_at);

            $values->push([
                'time' => $publishedAt,
                'value' => $item->current,
            ]);

            for ($i=1; $i<=5; $i++) {
                $values->push([
                    'time' => $publishedAt->copy()->addHours($i),
                    'value' => $item->{'add'.$i.'h'},
                ]);
            }

            $dataset->put($method, $values);
        });

        return PredictionToChartFormatter::format($dataset);
    }
}
