<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use OneSignal;

class NotificationController extends Controller
{
    //
    public function sendNotificationByExternalId(Request $request){
       $externalId= $request->externalId;
       $message=$request->message;
        $user = Staff::all();
        $nurseIds = $user->pluck('id')->map(function ($id) {
            return (string) $id;
        })->toArray();
        $response = OneSignal::sendNotificationCustom([
            'include_external_user_ids' => $nurseIds,
            'contents' => ["en" => $message],
            "ios_sound"=> "customsound.aiff",
            "android_sound"=> "custsom_sound2",
            // "sound"=>"custom_sound2.wav",
            "android_channel_id"=>"adc7a92e-47ac-4298-a7c8-496932977399"
            // Add more fields as needed
        ]);

        return $response;
    }
}
