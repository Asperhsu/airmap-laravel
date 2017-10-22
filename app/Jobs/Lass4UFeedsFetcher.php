<?php

namespace App\Jobs;

use App\Datasource\Lass4U;

class Lass4UFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return Lass4U::feedResource();
    }

    public function parseFeed(array $raw){
        return Lass4U::parse($raw);
    }

    public function feeds(array $data)
    {
        return $data['feeds'];
    }
}
