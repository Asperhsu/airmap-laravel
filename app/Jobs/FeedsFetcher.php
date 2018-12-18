<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Service\HttpClient;
use App\Service\JsonCache;
use App\Models\Group;
use App\Models\Fetch;
use App\Models\Record;

abstract class FeedsFetcher implements ShouldQueue
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
     * Group instance
     *
     * @var App\Models\Group
     */
    public $group;

    /**
     * Fetch Record
     *
     * @var App\Models\Record
     */
    public $fetch;


    /**
     * Records to be insert
     *
     * @var [type]
     */
    protected $records = [];


    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * feed resource provider
     *
     * @return array | string
     */
    abstract public function feedResource();

    /**
     * single feed parser
     *
     * @param array $raw  raw feed
     * @return App\Models\Record
     */
    abstract public function parseFeed(array $raw);

    /**
     * feed ffilter, false will drop feed
     *
     * @param array   $raw  raw feed
     * @return boolean
     */
    public function filter(array $raw)
    {
        return true;
    }

    /**
     * feeds provider from request response
     *
     * @param array $data  response json data
     * @return void
     */
    public function feeds(array $data)
    {
        return $data;
    }

    /**
     * check exists than make record
     *
     * @param array $record  array
     * @param array $fetch  Fetch
     * @return void
     */
    protected function make(array $record, Fetch $fetch)
    {
        $existRecord = Record::where([
            'uuid' => $record['uuid'],
            'group_id' => $this->group->id,
            'published_at' => $record['published_at'],
        ])->exists();

        if ($existRecord) {
            return;
        }

        $floatFields = ['lat', 'lng', 'humidity', 'temperature'];
        $intFields = ['pm25'];

        foreach ($floatFields as $field) {
            $record[$field] = isset($record[$field]) ? floatval($record[$field]) : null;

            if ($record[$field] < 0 || $record[$field] > 999) {
                // logger(sprintf('device %s-%s outofrange %s:$s', $this->group->id, $record['uuid'], $field, $record[$field]));
                return false;
            }
        }
        foreach ($intFields as $field) {
            $record[$field] = isset($record[$field]) ? intval($record[$field]) : null;

            if ($record[$field] < 0 || $record[$field] > 999) {
                // logger(sprintf('device %s-%s outofrange %s:$s', $this->group->id, $record['uuid'], $field, $record[$field]));
                return false;
            }
        }

        $record['group_id'] = $this->group->id;
        $record['fetch_id'] = $fetch->id;
        $record['created_at'] = date('Y-m-d H:i:s');
        $record['updated_at'] = date('Y-m-d H:i:s');

        $this->records[] = $record;
    }

    protected function saveRecords()
    {
        collect($this->records)->chunk(200)->map(function ($rows) {
            Record::insert($rows->toArray());
        });
    }

    /**
     * fetch feed resource
     *
     * @param string $url
     * @return array
     */
    protected function fetch(string $url)
    {
        $response = HttpClient::getJson($url);

        if ($response['success'] === false || !is_array($response['data'])) {
            return [];
        }

        $feeds = $this->feeds($response['data']);

        $this->fetch = Fetch::create([
            'group_id' => $this->group->id,
            'transfer_ms' => $response['status']['transferTime'],
            'feeds' => count($feeds),
        ]);

        return is_array($feeds) ? $feeds : [];
    }

    /**
     * handle job
     *
     * @return void
     */
    public function handle()
    {
        collect($this->feedResource())->each(function ($url) {
            $feeds = $this->fetch($url);

            foreach ($feeds as $feed) {
                if (!$this->filter($feed)) {
                    continue;
                }

                try {
                    $record = $this->parseFeed($feed);
                    $this->make($record, $this->fetch);
                } catch (\Exception $e) {
                    logger(static::class . 'handle feed error.', $feed);
                }
            }
        });

        $this->saveRecords();
    }
}
