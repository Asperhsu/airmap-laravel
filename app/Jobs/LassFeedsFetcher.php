<?php

namespace App\Jobs;

use App\Datasource\Lass;

class LassFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return Lass::feedResource();
    }

    public function parseFeed(array $raw){
        return Lass::parse($raw);
    }
    
    public function feeds(array $data)
    {
        return $data['feeds'];
    }
}
