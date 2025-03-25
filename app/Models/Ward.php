<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Ward extends Model
{
    // use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital')->orderBy("created_at", "asc");;
    }
    public function headNurse()
    {
        return $this->belongsTo('App\Models\Staff')->orderBy("created_at", "asc");;
    }

    public function rooms()
    {

        return $this->hasMany('App\Models\Room')->orderBy("created_at", "asc");;
    }
}
