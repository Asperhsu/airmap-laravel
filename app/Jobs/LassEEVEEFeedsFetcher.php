<?php

namespace App\Jobs;

use App\Datasource\LassEEVEE;

class LassEEVEEFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return LassEEVEE::feedResource();
    }

    public function parseFeed(array $raw){
        return LassEEVEE::parse($raw);
    }

    public function feeds(array $data)
    {
        return $data['feeds'];
    }
}
