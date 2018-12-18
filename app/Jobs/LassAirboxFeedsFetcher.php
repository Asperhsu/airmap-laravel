<?php

namespace App\Jobs;

use App\Datasource\LassAirbox;

class LassAirboxFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return LassAirbox::feedResource();
    }

    public function parseFeed(array $raw)
    {
        return LassAirbox::parse($raw);
    }

    public function feeds(array $data)
    {
        return $data['feeds'];
    }
}
