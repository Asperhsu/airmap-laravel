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

    public function getFullTextAttribute($value)
    {
        $text = [];

        foreach (['country', 'level1', 'level2', 'level3', 'level4'] as $field) {
            if ($this->{$field}) {
                $text[] = $this->{$field};
            }
        }
        
        return implode('', $text);
    }

    public function records()
    {
        $this->belongsToMany(Record::class);
    }
}
