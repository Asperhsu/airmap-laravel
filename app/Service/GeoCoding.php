<?php

namespace App\Service;

use App\Models\Geometry;
use Cache;

class GeoCoding
{
    protected $bounds;

    public function __construct()
    {
        $this->load();
    }

    public function load(bool $refresh = false)
    {
        if ($refresh) {
            Cache::forget(static::class);
        }
        
        $this->bounds = Cache::rememberForever(static::class, function () {
            return Geometry::all();
        });
    }

    public function findLatLng(float $lat, float $lng)
    {
        $bound = $this->bounds->filter(function ($bound) use ($lat, $lng) {
            return $lat <= $bound->northlat && $lat >= $bound->southlat
                && $lng >= $bound->westlng && $lng <= $bound->eastlng;
        })->first();
        
        if ($bound) {
            return $bound;
        }

        logger(sprintf('GeoCoding fetch unknow lat: %s, lng: %s', $lat, $lng));   
        $bound = $this->fetchGeocoding($lat, $lng);
        $this->load(true);
        usleep(100000); // delay 0.1 sec for request rate

        if ($bound) {
            return $bound;
        }

        return $this->bounds->where('country', 'default')->first();
    }

    protected function fetchGeocoding(float $lat, float $lng)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        $query = [
            'key' => config('services.gmap.key'),
            'language' => 'zh-TW',
            'latlng' => $lat.','.$lng,
        ];

        $resource = $url . '?' . http_build_query($query);
        $resource = str_replace('%2C', ',', $resource);

        $data = HttpClient::getJson($resource)['data'];
        if ($data['status'] != "OK" || !count($data['results'])) {
            return false;
        }
        
        $result = $this->findAddrResult($data['results'], $lat, $lng);
        if ($result) {
            return $this->createGeometry($result);
        }

        return false;
    }

    protected function findAddrResult(array $results, float $lat, float $lng)
    {
        foreach ($results as $result) {
            $viewport = $result['geometry']['viewport'];
            $westlng  = $viewport['southwest']['lng'];
            $eastlng  = $viewport['northeast']['lng'];
            $northlat = $viewport['northeast']['lat'];
            $southlat = $viewport['southwest']['lat'];
            
            if ($lat <= $northlat && $lat >= $southlat
                && $lng >= $westlng && $lng <= $eastlng) {
                return $result;
            }
        }

        return false;
    }

    protected function createGeometry(array $result)
    {
        $geometry = new Geometry;

        foreach ($result['address_components'] as $compoment) {
            if (in_array('country', $compoment['types'])) {
                $geometry->country = $compoment['long_name'];
            }
            if (in_array('administrative_area_level_1', $compoment['types'])) {
                $geometry->level1 = $compoment['long_name'];
            }
            if (in_array('administrative_area_level_2', $compoment['types'])) {
                $geometry->level2 = $compoment['long_name'];
            }
            if (in_array('administrative_area_level_3', $compoment['types'])) {
                $geometry->level3 = $compoment['long_name'];
            }
            if (in_array('administrative_area_level_4', $compoment['types'])) {
                $geometry->level4 = $compoment['long_name'];
            }
        }

        if (!$geometry->level1 && !$geometry->level2 
            && !$geometry->level3 && !$geometry->level4) {
            return false;
        }

        $viewport = $result['geometry']['viewport'];
        $geometry->westlng = $viewport['southwest']['lng'];
        $geometry->eastlng = $viewport['northeast']['lng'];
        $geometry->northlat = $viewport['northeast']['lat'];
        $geometry->southlat = $viewport['southwest']['lat'];

        $geometry->save();

        return $geometry;
    }
}
