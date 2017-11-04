<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

class HttpClient
{
    public static function getJson($url)
    {
        $client = new Client();
        $success = false;
        $status = [];
        $data = [];
        
        $options = [
            'on_stats' => function (TransferStats $stats) use (&$status) {
                $status['transferTime'] = $stats->getTransferTime() * 1000;
                $status['effectiveUri'] = $stats->getEffectiveUri();

                if ($stats->hasResponse()) {
                    $status['httpCode'] = $stats->getResponse()->getStatusCode();
                }
            }
        ];

        try {
            $success = true;
            $response = $client->request('GET', $url, $options);
            $data = json_decode((string) $response->getBody(), true);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $status['httpCode'] = $response->getStatusCode();
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response) {
                $status['httpCode'] = $response->getStatusCode();
            }
        }

        return [
            'success' => $success,
            'status' => $status,
            'data' => $data,
        ];
    }
}
