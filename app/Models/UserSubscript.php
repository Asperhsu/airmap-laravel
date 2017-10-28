<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscript extends Model
{
    public function groups()
    {
        return $this->belongsTo(Group::class);
    }

    public function grometry()
    {
        return $this->belongsTo(Grometry::class);
    }

}
