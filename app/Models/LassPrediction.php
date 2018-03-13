<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LassPrediction extends Model
{
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'published_at'
    ];

    protected $casts = [
        'now' => 'float',
        'add1h' => 'float',
        'add2h' => 'float',
        'add3h' => 'float',
        'add4h' => 'float',
        'add5h' => 'float',
    ];
}
