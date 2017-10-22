<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'group_id', 'fetch_id', 'created_at', 'updated_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'published_at'
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'pm25' => 'integer',
        'humidity' => 'float',
        'temperature' => 'float',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function fetch()
    {
        return $this->belongsTo(Fetch::class);
    }

    public function analyses()
    {
        return $this->hasMany(LassAnalyse::class);
    }

    public function rankings()
    {
        return $this->hasMany(LassRanking::class);
    }

    public function geometries()
    {
        return $this->belongsToMany(Geometry::class);
    }
}
