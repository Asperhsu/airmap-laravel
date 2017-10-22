<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'enable',
    ];

    protected $casts = [
        'enable' => 'boolean',
    ];

    public function records()
    {
        $this->hasMany(Record::class);
    }

    public function fetches()
    {
        $this->hasMany(Fetch::class);
    }

    public function thingspeaks()
    {
        $this->hasMany(Thingspeak::class);
    }
}
