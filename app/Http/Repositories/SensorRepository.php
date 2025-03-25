<?php

namespace App\Http\Repositories;

use App\Enums\ButtonStatus;
use App\Enums\LightStatus;
use App\Events\RoomAlertStatusChanged;
use App\Http\Resources\AdminDashboardResource;
use App\Http\Resources\HospitalDetailsResource;
use App\Http\Resources\NurseDashboardResource;
use App\Http\Resources\NurseWebSocketResource;
use App\Models\Sensor;
use App\Models\SensorLog;
use App\Models\Staff;
use Carbon\Carbon;
use OneSignal;

class SensorRepository
{
    function register_device($validatedData)
    {

        $device = Sensor::Where('dui', $validatedData['device_id'])->first();
        if ($device) {

            if ($device->room) {
                //     dd($device->room != null);
                // // }
                return response()->json(['message' => 'already registered', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'already registered', 'status' => false], 200);
            }
            // return response()->json(['message' => 'Device already registered'], 200);
        }
        $device = Sensor::create(
            [
                'dui' => $validatedData['device_id'],

            ]
        );
        return response()->json(['message' => 'Device has been registered', 'status' => false], 200);
    }
    function update_device_status($validatedData)
    {

        $device = Sensor::Where('dui', $validatedData['device_id'])->first();
        $last_status=$device->light_status;
        $sending =false;
        // echo $last_status;
        // echo $validatedData['light_status'];
        // echo $last_status==$validatedData['light_status'];
        if($last_status==$validatedData['light_status']){
            $sending =false;
        }else{
            $sending =true;
        }
    
        $room = $device->room;
        if ($room == null) {
            return response()->json(['message' => 'not assigned to any room', 'status' => false], 200);
        }
        // echo $validatedData['light_status'].''!=$last_status.'';
        if ($device) {
            // $data = json_decode($validatedData['status'], true);
            // $stand_ave = isset($validatedData['stand_ave']) ? (float) $validatedData['stand_ave']  : -1;
            // $fall_ave = isset($validatedData['fall_ave']) ? (float) $validatedData['fall_ave']  : -1;
            // $unoc = isset($validatedData['stand_ave']) ? (float) $validatedData['stand_ave']  : -1;
            $sensorLog  = SensorLog::make([
                'fall' => isset($validatedData['fall'])   ? $validatedData['fall'] : 0,
                'stand' => isset($validatedData['stand'])   ? $validatedData['stand'] : 0,
                'unoccupied' => isset($validatedData['unoccupied'])   ? $validatedData['unoccupied'] : 0,
                'light_status' => isset($validatedData['light_status'])   ? $validatedData['light_status'] : 0,
                'button_status' => isset($validatedData['button_status'])   ? $validatedData['button_status'] : ButtonStatus::unpressed,
                'motion' => isset($validatedData['motion'])   ? $validatedData['motion'] : 0,
                'fall_ave' => isset($validatedData['fall_ave']) ? $validatedData['fall_ave'] : 0,
                'stand_ave' => isset($validatedData['stand_ave']) ? $validatedData['stand_ave']   : 0,
                'unoccupied_ave' => isset($validatedData['unoccupied_ave']) ? $validatedData['unoccupied_ave']   : 0,
                'result' => isset($validatedData['result']) ? $validatedData['result']   : "N/A",
                // "trigger" => $sending ? 1 : 0,

            ]);


            $sensorLog->trigger= $sending ? 1 : 0;

            $sensorLog->sensor()->associate($device);

            $sensorLog->save();

            if (isset($validatedData['pb_id_battery'])) {
                if ($validatedData['pb_id_battery']>0) {
                $device->battery_level = $validatedData['pb_id_battery'];
                }
            }



            if ($sensorLog->light_status == 0) { //if isset($validatedData['light_status']) == false


                if ($sensorLog->fall_ave > LightStatus::MA_FALL) {
                    $device->light_status = LightStatus::fall;
                } else if ($sensorLog->stand_ave > LightStatus::MA_STAND) {
                    $device->light_status = LightStatus::occupied;

                    if ($sensorLog->motion == LightStatus::short_period_of_inactivity_flag) {
                        $device->light_status = LightStatus::occupied_warning;
                    } else if ($sensorLog->motion == LightStatus::long_period_of_inactivity_flag) {
                        $device->light_status = LightStatus::occupied_alerm;
                    }
                } else if ($sensorLog->unoccupied_ave > LightStatus::MA_UNOCCUPIED) {
                    $device->light_status = LightStatus::unoccupied;
                }
            } else {
                $device->light_status = $sensorLog->light_status;
            }
            $device->button_status = $sensorLog->button_status;
            $device->motion = $sensorLog->motion;
            $device->last_log_created_at = Carbon::now();

            $device->lightId   = isset($validatedData['light_id'])   ? $validatedData['light_id'] : null;
            $device->buttonId   = isset($validatedData['pb_id'])   ? $validatedData['pb_id'] : null;
            $device->communication_channel  = isset($validatedData['channel'])   ? $validatedData['channel'] : 0;


            $device->save();


        
            if ($room->sensors->where('light_status', LightStatus::fall)->count() > 0) {
                $room->light_status = LightStatus::fall;
            } else if ($room->sensors->where('light_status', LightStatus::occupied_warning)->count() > 0) {
                $room->light_status = LightStatus::occupied_warning;
            } else if ($room->sensors->where('light_status', LightStatus::occupied)->count() > 0) {
                $room->light_status = LightStatus::occupied;
            } else {
                $room->light_status = LightStatus::unoccupied;
            }

            if ($room->sensors->where('button_status', ButtonStatus::pressed)->count() > 0) {
                $room->button_status = ButtonStatus::pressed;
            } else {
                $room->button_status = ButtonStatus::unpressed;
            }

            // $d = ($room->sensors->max('last_log_created_at'));
            $room->last_log_created_at = ($room->sensors->max('last_log_created_at'));
            $room->motion = $room->sensors->max('motion');
            $room->save();
            $headNurse = $room->headNurse();
            $nurses = $room->groups()->with("staff")->get();
            // dump($nurses);
            $nurses =  $nurses->map(function ($item) {
                return $item->staff;
            });
            // dump($nurses);
            $nurses = $nurses->flatten()->unique('id');
            // dump($last_status==$validatedData['light_status']);

            if($sending){
            if($room->button_status == ButtonStatus::pressed || $room->light_status == LightStatus::fall || $room->light_status == LightStatus::occupied_warning)
            $this->sendNotificationByExternalId($nurses);
            }
           // $this->endNotificationByExternalId2();
            $data =  new NurseWebSocketResource($room);

            //2 -- get required data
            foreach ($nurses as $nurse) {
                RoomAlertStatusChanged::dispatch($data, $nurse, $room);
            }

            $headNurseData =  new NurseWebSocketResource($room);
            if ($headNurse != null) {
                RoomAlertStatusChanged::dispatch($headNurseData, $headNurse, $room);
            }

            // }


            $isDismissed = $room->is_dismissed != null && Carbon::parse($room->is_dismissed)->isAfter(Carbon::now()->subSeconds(30));
            // $is_not_configured = ($device->communication_channel != null && ($device->lightId != null || $device->buttonId != null) && (strcasecmp($light_id, $device->lightId) != 0 || strcasecmp($pb_id, $device->buttonId) != 0 || $device->communication_channel != $channel));
            // if ($is_not_configured) {
            //     $room->status = 1;
            //     $room->save();
            //     return response()->json([
            //         "status" => true,
            //         "light_status" => $device->required_light_status,
            //         "config" => true,
            //         "channel" => (int)$device->communication_channel,
            //         "light_id" => $device->lightId,
            //         "pb_id" => $device->buttonId,

            //     ], 200);
            // } else
            if ($isDismissed && ($sensorLog->button_status == ButtonStatus::pressed)) {
                $room->status = 2;
                $room->save();
                return response()->json([
                    "status" => true,
                    "dismiss" => $isDismissed,

                ], 200);
            }

            $room->status = 0;
            $room->save();
            return response()->json([
                "status" => true,

            ], 200);
        }
        // $device = Sensor::create(
        //     [
        //         'dui' => $validatedData['device_id'],

        //     ]
        // );
        // return response()->json("['message' => 'Device has been registered']", 200);
    }
    private function sendNotificationByExternalId($users){
        // $externalId= $request->externalId;
        $message="Fall Emergency"; 
        //  $users = Staff::where('hospital_id', 1)->get();
         $nurseIds = $users->pluck('id')->map(function ($id) {
             return (string) $id;
         })->toArray();
        //  dump($nurseIds);
        //  dump($users);
         if(count($nurseIds)>0)
         $response = OneSignal::sendNotificationCustom([
             'include_external_user_ids' => $nurseIds,
             'contents' => ["en" => $message],
             "ios_sound"=> "customsound.aiff",
             "android_sound"=> "custsom_sound2",
             // "sound"=>"custom_sound2.wav",
             "android_channel_id"=>"adc7a92e-47ac-4298-a7c8-496932977399"
             // Add more fields as needed
         ]);
//  echo response()->json($nurseIds, 200);
        //  return $response;
     }
     private function sendNotificationByExternalId2(){
        // $externalId= $request->externalId;
        // $message=$request->message;
         $user = Staff::all();
         $nurseIds = $user->pluck('id')->map(function ($id) {
             return (string) $id;
         })->toArray();
         $response = OneSignal::sendNotificationCustom([
             'include_external_user_ids' => $nurseIds,
             'contents' => ["en" => "text here"],
             // Add more fields as needed
         ]);
 
        //  return $response;
     }
}
