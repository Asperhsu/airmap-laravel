<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thingspeak extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'active' => 'boolean',
        'fields_map' => 'collection',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeProbecube($query)
    {
        return $query->where('party', 'ProbeCube');
    }

    public function scopeIndependent($query)
    {
        return $query->where('party', '<>', 'ProbeCube');
    }

    /* Relationship */

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
