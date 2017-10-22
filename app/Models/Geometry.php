<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geometry extends Model
{
    protected $casts = [
        'westlng' => 'float',
        'eastlng' => 'float',
        'northlat' => 'float',
        'southlat' => 'float',
    ];

    public function records()
    {
        $this->belongsToMany(Record::class);
    }
}
