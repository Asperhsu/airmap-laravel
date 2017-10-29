<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscript extends Model
{
    protected $casts = [
        'geometry_ids' => 'json',
    ];
    
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function grometry()
    {
        return $this->belongsTo(Grometry::class);
    }

}
