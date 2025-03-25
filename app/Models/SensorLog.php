<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'fall',
        'stand',
        'unoccupied',
        'light_status',
        'button_status',
        'motion',
        'fall_ave',
        'stand_ave',
        'unoccupied_ave',
        'result',
    ];

    public function sensor()
    {
        return $this->belongsTo('App\Models\Sensor')->orderBy("created_at", "asc");;
    }
}
