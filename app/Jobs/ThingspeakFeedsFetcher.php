<?php
namespace App\Jobs;

use App\Datasource\Thingspeak as DS;
use App\Models\Thingspeak as Model;
use App\Service\JsonCache;

class ThingspeakFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        // won't use this method

    }

    public function parseFeed(array $raw)
    {
        return DS::parse($raw);
    }

    public function feeds(array $data)
    {
        if (!isset($data['channel']['id'])) {
            logger($data);
            return [];
        }

        $info = [
            'id' => $data['channel']['id'],
            'name' => $data['channel']['name'],
            'latitude' => $data['channel']['latitude'],
            'longitude' => $data['channel']['longitude'],
        ];

        $items = [];
        foreach ($data['feeds'] as $feed) {
            $items[] = array_merge($info, $feed);
        }

        return $items;
    }

    public function handle()
    {
        $template = DS::feedResource();

        Model::where('group_id', $this->group->id)->active()->each(function ($ts) use ($template) {
            $url = str_replace('{{channel}}', $ts->channel, $template);

            $feeds = $this->fetch($url);

            foreach ($feeds as $feed) {
                $feed['maker'] = $ts->maker;
                $feed['fieldsMap'] = $ts->fields_map;

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
