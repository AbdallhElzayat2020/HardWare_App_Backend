<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    public function hospital()
    {
        return $this->ward()->hospital;
    }
    public function ward()
    {
        return $this->belongsTo('App\Models\Ward')->orderBy("created_at", "asc");;
    }
    public function sensors()
    {

        return $this->hasMany('App\Models\Sensor')->orderBy("created_at", "asc");;
    }

    public function firstSensors()
    {

        return $this->hasMany('App\Models\Sensor')->orderBy("created_at", "asc")->first();;
    }
    public function lastlog()
    {

        return $this->hasManyThrough('App\Models\SensorLog', 'App\Models\Sensor',)->orderBy("created_at", "desc")->first();;
    }
    public function groups()
    {

        return $this->belongsToMany('App\Models\HospitalGroup', 'hospital_group_room')->orderBy("created_at", "asc");;
    }

    public function headNurse()
    {
        return $this->ward->headNurse;
    }

    // public function staff()
    // {

    //     return $this->hasManyThrough('App\Models\HospitalGroup', 'App\Models\Staff')->orderBy("created_at", "asc");;
    // }

    public function delete()
    {

        $this->groups()->detach();
        $sensors = $this->sensors();
        $sensors->each(function ($sensor) {
            $sensor->room_id = null;
            $sensor->save();
        });
        return parent::delete();
    }
}
