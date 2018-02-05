<?php

namespace App\Jobs;

use App\Models\Group;
use App\Datasource\Epa;
use App\Service\HttpClient;
use Illuminate\Support\Facades\Storage;

class EpaFeedsFetcher extends FeedsFetcher
{
    protected $siteInfoFilename = 'epa-sites-info.json';
    protected $siteInfo;

    public function __construct(Group $group)
    {
        parent::__construct($group);

        $this->siteInfo = $this->loadSitesInfo();
    }

    public function loadSitesInfo()
    {
        if (!Storage::exists($this->siteInfoFilename)) {
            $url = 'http://opendata.epa.gov.tw/ws/Data/AQXSite/?$format=json';
            $response = HttpClient::getJson($url);
            $data = $response['data'];
            Storage::put($this->siteInfoFilename, json_encode($data));
        }

        $data = json_decode(Storage::get($this->siteInfoFilename), true);
        return collect($data);
    }

    public function getSiteInfo(string $country, string $siteName)
    {
        $index = $this->siteInfo->search(function ($item, $key) use ($country, $siteName) {
            return $item['County'] == $country && $item['SiteName'] == $siteName;
        });

        return $this->siteInfo->get($index);
    }

    public function feedResource()
    {
        return Epa::feedResource();
    }

    public function filter(array $raw)
    {
        // check site has infomation
        return $this->getSiteInfo($raw['County'], $raw['SiteName']) !== null;
    }

    public function parseFeed(array $raw)
    {
        $info = $this->getSiteInfo($raw['County'], $raw['SiteName']);
        $row = array_merge($raw, $info);

        return Epa::parse(array_merge($raw, $info));
    }
}
