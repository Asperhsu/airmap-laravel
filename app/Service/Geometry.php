<?php

namespace App\Service;

use Geometry\Polygon;
use Illuminate\Support\Collection;

class Geometry
{
    protected $features;
    protected $geoJsonPath = 'assets/json/town.json';

    public function __construct()
    {
        $this->loadGeojson();
    }

    public function loadGeojson()
    {
        $path = resource_path($this->geoJsonPath);

        if (!file_exists($path)) {
            throw new \RuntimeException($this->geoJsonPath . ' is not found.');
        }

        $json = json_decode(file_get_contents($path), true);

        if (!isset($json['features'])) {
            throw new \RuntimeException('features is not found.');
        }

        $this->features = $json['features'];
    }

    public function findFeature(float $lat, float $lng)
    {
        $filterFeatures = array_filter($this->features, function ($feature) use ($lat, $lng) {
            $coordinates = $feature['geometry']['coordinates'][0] ?? null;
            if (!$coordinates) {
                return false;
            }

            return $this->isInPolygon($coordinates, $lat, $lng);
        });

        return count($filterFeatures) ? array_shift($filterFeatures) : null;
    }

    public function isInPolygon(array $coordinates, float $lat, float $lng)
    {
        $poly = new Polygon($coordinates);

        return $poly->pip($lng, $lat);
    }

    public static function boxplot(Collection $records)
    {
        $values = $records->values()->toArray();
        $quartiles  = \MathPHP\Statistics\Descriptive::quartiles($values);
        $outlinerMin = $quartiles['Q1'] - $quartiles['IQR'] * 1.5;  // Q1-1.5ΔQ
        $outlinerMax = $quartiles['Q3'] + $quartiles['IQR'] * 1.5;  // Q3+1.5ΔQ

        $valids = collect();
        $outliners = collect();
        $validValues = [];
        $records->map(function ($value, $key) use ($outlinerMin, $outlinerMax, &$valids, &$outliners, &$validValues) {
            // valid value should be: Q1-1.5ΔQ < value < Q3+1.5ΔQ
            $isOutliner = ($value < $outlinerMin) || ($value > $outlinerMax);

            if ($isOutliner) {
                $outliners->push($key);
            } else {
                $valids->push($key);
                $validValues[] = $value;
            }
        });

        return [
            'mean' => \MathPHP\Statistics\Average::mean($validValues),
            'valids' => $valids,
            'outliners' => $outliners,
        ];
    }
}
