<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        // 'user_id',
    ];
    public function wards()
    {
        return $this->hasMany('App\Models\Ward')->orderBy("created_at", "asc");
    }
    public function admin()
    {
        return $this->hasOne('App\Models\Staff')->where('role', 0);
    }
    public function staff()
    {
        return $this->hasMany('App\Models\Staff')->whereIn('role', [1,2]);
    }

    public function nurses()
    {
        return $this->hasMany('App\Models\Staff')->where('role', 2);
    }
    public function groups()
    {

        return $this->hasMany('App\Models\HospitalGroup')->orderBy("created_at", "asc");
    }
    public function headNurses()
    {

        return $this->hasMany('App\Models\Staff')->where('role', 1);
    }
    public function rooms()
    {

        return $this->hasManyThrough('App\Models\Room', 'App\Models\Ward')->orderBy("created_at", "asc");
    }
    public function delete()
    {
        $this->wards()->delete();
        $this->groups()->delete();
        // $this->groups()->rooms()->delete();
        $this->staff()->delete();
        $this->admin()->delete();
        return parent::delete();
    }
}
