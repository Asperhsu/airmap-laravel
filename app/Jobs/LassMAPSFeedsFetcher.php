<?php

namespace App\Jobs;

use App\Datasource\LassMAPS;

class LassMAPSFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return LassMAPS::feedResource();
    }

    public function parseFeed(array $raw){
        return LassMAPS::parse($raw);
    }

    public function feeds(array $data)
    {
        return $data['feeds'];
    }
}
