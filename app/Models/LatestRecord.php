<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LatestRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [];

    protected $table = 'latest_records';

    protected $dates = [
        'published_at',
    ];
}
