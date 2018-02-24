<?php

namespace App\Jobs;

use App\Datasource\Airq;

class AirqFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return Airq::feedResource();
    }

    public function parseFeed(array $raw)
    {
        return Airq::parse($raw);
    }
}
