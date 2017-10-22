<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fetch extends Model
{
    protected $fillable = [
        'group_id', 'transfer_ms', 'feeds'
    ];

    public function records()
    {
        $this->hasMany(Record::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
