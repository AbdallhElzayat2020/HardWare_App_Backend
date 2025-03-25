<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dui',
        'communication_channel',
        'light_status',
        'button_status',
        'motion',
    ];
    

    public function room()
    {
        return $this->belongsTo('App\Models\Room')->orderBy("created_at", "asc");;
    }
    public function logs()
    {

        return $this->hasMany('App\Models\SensorLog')->orderBy("created_at", "asc");;
    }
    public function last10logs()
    {

        return $this->hasMany('App\Models\SensorLog')->take(20)->orderBy("created_at", "desc");;
    }
    public function lastlog()
    {

        return $this->hasMany('App\Models\SensorLog')->orderBy("created_at", "desc")->first();
    }

    public function lastWeaklogs()
    {
// Get the current date and time
$now = Carbon::now();

// Calculate the date from one week ago
$oneWeekAgo = $now->subWeek();
        return $this->hasMany('App\Models\SensorLog')->orderBy("created_at", "desc")->where('created_at', '>=', $oneWeekAgo)->where('trigger', '=', 1);
    }
}
