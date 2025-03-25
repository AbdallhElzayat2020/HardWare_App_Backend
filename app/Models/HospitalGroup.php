<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalGroup extends Model
{
    protected $table = 'hospital_groups';

    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital')->orderBy("created_at", "asc");;
    }
    public function staff()
    {

        return $this->belongsToMany('App\Models\Staff', 'hospital_group_staff',)->orderBy("created_at", "asc");;
    }

    public function rooms()
    {

        return $this->belongsToMany('App\Models\Room', 'hospital_group_room')->orderBy("created_at", "asc");;
    }
    // protected static function booted()
    // {

    //     static::deleting(function ($group) {

    //         $group->staff()->detach();
    //         $group->rooms()->detach();
    //         return parent::delete();
    //     });
    // }
    public function delete()
    {
        $this->staff()->detach();
        $this->rooms()->detach();
        return parent::delete();
    }
}
