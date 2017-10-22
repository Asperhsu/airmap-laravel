<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LassAnalyse extends Model
{
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $casts = [
        'indoor' => 'boolean',
        'shortterm_pollution' => 'boolean',
        'longterm_pollution' => 'boolean',
    ];

    public function getDates()
    {
        return ['created_at', 'updated_at', 'malfunction_at', 'pollution_at', 'ranking_at'];
    }

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
