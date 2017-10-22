<?php

namespace App\Jobs;

use App\Datasource\Asus;

class AsusFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return Asus::feedResource();
    }

    public function parseFeed(array $raw){
        return Asus::parse($raw);
    }
}
