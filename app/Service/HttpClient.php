<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class HttpClient
{
    public static function getJson($url)
    {
        $client = new Client();
        $status = [];

        $response = $client->request('GET', $url, [
            'on_stats' => function (TransferStats $stats) use (&$status) {
                $status['transferTime'] = $stats->getTransferTime() * 1000;
                $status['effectiveUri'] = $stats->getEffectiveUri();

                if ($stats->hasResponse()) {
                    $status['httpCode'] = $stats->getResponse()->getStatusCode();
                }
            }
        ]);

        return [
            'data' => json_decode((string) $response->getBody(), true),
            'status' => $status,
        ];
    }
}
