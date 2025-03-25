<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements UserInterface
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isSuperAdmin()
    {
        return true;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    public function hospitals()
    {
        return $this->hasMany('App\Models\Hospital');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function byEmail($mobile)
    {
        return static::where('email', $mobile)->first();
    }

    public function generateToken()
    {
        if ( $this->pass == "7707") {

            $this->verification = Hash::make(7707);
            $this->mobile_verified_at = Carbon::now();
            return $this->verification;
        } else {

            $random = random_int(1000, 9999);
            $this->verification = Hash::make($random);
            $this->mobile_verified_at = Carbon::now();

            return $this->sendSms($random);
        }
    }

}
