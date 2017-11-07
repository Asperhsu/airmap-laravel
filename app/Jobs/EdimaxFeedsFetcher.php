<?php

namespace App\Jobs;

use App\Datasource\Edimax;

class EdimaxFeedsFetcher extends FeedsFetcher
{
    public function feedResource()
    {
        return Edimax::feedResource();
    }

    public function parseFeed(array $raw){
        return Edimax::parse($raw);
    }

    public function filter(array $raw)
    {
        $macFirstFourDigits = substr($raw['id'], 0, 4);

        return in_array($macFirstFourDigits, ['28C2', '74DA']);
    }

    public function feeds(array $data)
    {
        return $data['devices'];
    }
}
