<?php

namespace App\Http\Repositories;

use App\Enums\ButtonStatus;
use App\Enums\LightStatus;
use App\Events\RoomAlertStatusChanged;
use App\Http\Resources\AdminDashboardResource;
use App\Http\Resources\HospitalDetailsResource;
use App\Http\Resources\NurseDashboardResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\RoomResource2;
use App\Http\Resources\SensorDashboardResource;
use App\Http\Resources\SensorResource;
use App\Models\Room;
use App\Models\Sensor;
use App\Models\Staff;
use Carbon\Carbon;

class RoomRepository
{

    function updateRoomGroups($room, $validatedData)
    {


        if (isset($validatedData['groups'])) {
            $room->groups()->sync($validatedData['groups']);
        } else {
            $room->groups()->sync([]);
        }
        $room->save();
        $hospital =  $room->ward->hospital;

        return new AdminDashboardResource($hospital);
    }

    function dismiss_room_aert(Room $room)
    {

        $room->sensors->each(function ($sensor) {
            $sensor->required_light_status = LightStatus::unoccupied;
            $sensor->required_button_status = ButtonStatus::unpressed;
            $sensor->light_status = LightStatus::unoccupied;
            $sensor->button_status = ButtonStatus::unpressed;

            $sensor->save();
        });
        $room->is_dismissed = Carbon::now();
        $room->save();
        $data =  auth()->user()->groups()->with('rooms')->get();

        $rooms =  $data->map(function ($item) {

            return $item->rooms;
        });

        $flattened = $rooms->flatten()->unique('id');

        // $flattened = $flattened->all();

        $unique = $flattened->all();

        // return NurseDashboardResource::collection($flattened);


        // $data =  auth()->user()->headNurseRooms()->with('sensors')->get();

        // $sensors =  $data->map(function ($item) {

        //     return $item->sensors;
        // });
        // $flattened = $sensors->flatten()->unique();

        // $flattened->all();



        RoomAlertStatusChanged::dispatch(NurseDashboardResource::collection($flattened), auth()->user(), $room);
        //TODO raise event to update the room alert status
    }
    // function dismiss_room_aert(Room $room)
    // {
    //     $room->sensors->each(function ($sensor) {
    //         $sensor->required_light_status = 3;
    //         $sensor->required_button_status = 1;
    //         $sensor->save();
    //     });



    //     $data =  auth()->user()->headNurseRooms()->with('sensors')->get();

    //     $sensors =  $data->map(function ($item) {

    //         return $item->sensors;
    //     });
    //     $flattened = $sensors->flatten()->unique();

    //     $flattened->all();



     //     //TODO raise event to update the room alert status
    // }
    function show_sensor_logs(Room $room)
    {
        return new RoomResource($room->load('sensors'));
    }
    function show_sensor_logs2(Room $room)
    {
        return new RoomResource2($room->load('sensors'));
    }
    // function update_room_panic_button(Room $room, $validatedData)
    // {
    //     $sensors = $room->sensors;
    //     if ($validatedData['action'] == 1) {
    //         $sensors->each(function ($sensor) use ($validatedData) {
    //             $sensor->buttonId = null;
    //             $sensor->save();
    //         });
    //     } else {
    //         $sensors->each(function ($sensor) use ($validatedData) {
    //             $sensor->buttonId = $validatedData['sensorId'];
    //             $sensor->save();
    //         });
    //     }
    //     $room->refresh();
    //     return  new RoomResource($room->load('sensors'));
    // }
    // function update_room_light(Room $room, $validatedData)
    // {
    //     $sensors = $room->sensors;
    //     if ($validatedData['action'] == 1) {
    //         $sensors->each(function ($sensor) use ($validatedData) {
    //             $sensor->lightId = null;
    //             $sensor->save();
    //         });
    //     } else {
    //         $sensors->each(function ($sensor) use ($validatedData) {
    //             $sensor->lightId = $validatedData['sensorId'];
    //             $sensor->save();
    //         });
    //     }
    //     $room->refresh();
    //     return  new RoomResource($room->load('sensors'));
    // }

    // function update_room_communication_channel(Room $room, $validatedData)
    // {
    //     $sensors = $room->sensors;
    //     $sensors->each(function ($sensor) use ($validatedData) {
    //         $sensor->communication_channel = $validatedData['channel'];
    //         $sensor->save();
    //     });
    //     $room->refresh();
    //     return  new RoomResource($room->load('sensors'));
    // }
    function update_room_devices(Room $room, $validatedData)
    {

        $sensors  = Sensor::whereIn('dui', $validatedData['sensorIds'])->get();
        $lightID = $room->sensors()->where('lightId', '!=', null)->first()->lightId ?? null;
        $buttonId = $room->sensors()->where('buttonId', '!=', null)->first()->lightId ?? null;
        $channel = $room->sensors()->where('communication_channel', '!=', null)->first()->communication_channel ?? null;

        if ($validatedData['action'] == 1) {
            $sensors->each(function ($sensor) use ($room) {
                $sensor->room()->dissociate($room);
                $sensor->save();
            });
        } else {
            $sensors->each(function ($sensor) use ($room, $lightID, $buttonId, $channel) {
                if (!$sensor->room()->exists()) {

                    $sensor->room()->associate($room);
                    $sensor->lightId = $lightID;
                    $sensor->buttonId = $buttonId;
                    $sensor->communication_channel = $channel;

                    $sensor->save();
                }
            });
        }
        return  new RoomResource($room->load('sensors'));
    }
}
