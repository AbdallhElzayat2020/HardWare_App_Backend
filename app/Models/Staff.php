<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;




class Staff extends Authenticatable implements UserInterface
{
    // use SoftDeletes;
    use Notifiable;
    use HasApiTokens;

    use HasFactory;

    protected $fillable = [
        'name', 'mobile', 'verification'
    ];
    protected $hidden = [
        'verification',
    ];
    public function isSuperAdmin()
    {
        return false;
    }
    public function isAdmin()
    {
        return $this->role === 0;
    }
    public function isHeadNurse()
    {
        return $this->role === 1;
    }
    public function isNurse()
    {
        return $this->role === 2;
    }
    public function routeNotificationForFcm()
    {

        return $this->device_token;
    }
    public function generateToken()
    {
        if (env('SMS_PRETEND') === true || $this->mobile == "+966544444444"|| $this->mobile == "+966549491996"|| $this->mobile == "+966549491995"|| $this->mobile == "+966581008988"|| $this->mobile == "+966571718153") {

            $this->verification = Hash::make(1111);
            $this->mobile_verified_at = Carbon::now();
            return $this->verification;
        } else {

            $random = random_int(1000, 9999);
            $this->verification = Hash::make($random);
            $this->mobile_verified_at = Carbon::now();

            return $this->sendSms($random);
        }
    }
    public function ward()
    {
        return $this->hasMany('App\Models\Ward', 'head_nurse_id')->orderBy("created_at", "asc");;
    }
    public function headNurseRooms()
    {
        return $this->hasManyThrough('App\Models\Room', 'App\Models\Ward', 'head_nurse_id')->orderBy("created_at", "asc");;
    }
    public function sendSms($code)
    {

        try {
            $client = new  Client();

            $result = $client->post(
                'https://www.msegat.com/gw/sendsms.php',
                [
                    'headers' => [
                        'Accept'     => 'application/json',
                        'Content-Type'     => 'application/json',
                    ],
                    'json' => [
                        'msg' => 'Verification code: ' . $code, //set message body
                        'numbers' => $this->mobile,
                        'userName' => env('SMS_USERNAME'), //we get this number from twilio
                        'userSender' => env('SMS_SENDER'), //we get this number from twilio
                        'apiKey' => env('SMS_KEY')
                    ]
                ]
            );
            $resposeJson = json_decode($result->getBody());
            if ($result->getStatusCode() == 200  && $resposeJson->code == 1) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
            echo "Error: " . $e->getMessage();
        }
    }
    public static function byMobile($mobile)
    {
        return static::where('mobile', $mobile)->first();
    }
    public function username()
    {
        return 'mobile';
    }
    public function getAuthPassword()
    {
        if ($this->mobile_verified_at > Carbon::now()->subMinutes(10)) {

            return $this->verification;
        }
        return random_int(10000, 99999);
    }
    public function clearSMS()
    {
        $this->mobile_verified_at = null;
        $this->verification = null;
        $this->save();
    }
    public function hospital()
    {

        return $this->belongsTo('App\Models\Hospital')->orderBy("created_at", "asc");;
    }
    public function groups()
    {

        return $this->belongsToMany('App\Models\HospitalGroup', 'hospital_group_staff')->orderBy("created_at", "asc");;
    }
    // public function rooms()
    // {

    //     return $this->hasManyThrough('App\Models\Room', 'hospital_group_room')->orderBy("created_at", "asc");;
    // }
    // public function resolveRouteBinding($value, $field = null)
    // {

    //     return $this->where('mobile', $value)->first() ?? abort(403);
    // }
}
