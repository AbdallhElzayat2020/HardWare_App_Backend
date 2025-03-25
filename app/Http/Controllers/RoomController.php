<?php

namespace App\Http\Controllers;

use App\Http\Repositories\HospitalRepository;
use App\Http\Repositories\RoomRepository;
use App\Models\Hospital;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{


    public function __construct(HospitalRepository $hospitalRepository, RoomRepository $roomRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
        $this->roomRepository = $roomRepository;
        $this->authorizeResource(Room::class, 'room');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function updateRoomGroups(Request $request, Room $room)
    {
        $validatedData = request()->validate(
            [

                'groups' => ['array', 'exists:hospital_groups,id'],
            ]
        );

        return $this->roomRepository->updateRoomGroups($room, $validatedData);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function dismiss_room_aert(Request $request, Room $room)
    {

        $this->authorize('dismiss_room_aert',   [Room::class, $room]);

        return $this->roomRepository->dismiss_room_aert($room);
    }
    public function show_sensor_logs(Request $request, Room $room)
    {
        $this->authorize('show_sensor_logs',   [Room::class, $room]);

        return $this->roomRepository->show_sensor_logs($room);
    }

    public function show_sensor_logs2(Request $request, Room $room)
    {
        $this->authorize('show_sensor_logs',   [Room::class, $room]);

        return $this->roomRepository->show_sensor_logs2($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */

    public function updateRoom(Request $request, Hospital $hospital)
    {
        $this->authorize('updateRoom',   [Room::class]);

        $validatedData = request()->validate(
            [
                'roomId' => ["string", "nullable", 'exists:rooms,id'],
                'name' => ["required", "string"],
                'ward' => ['required', 'string', 'exists:wards,id'],
            ]
        );

        return $this->hospitalRepository->updateOrCreateRoom($hospital, $validatedData);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update_room_devices(Request $request, Room $room)
    {
        $validatedData = request()->validate(
            [

                'action' => ['required', Rule::in([0, 1]),],
                'sensorIds' => ['required', 'array', 'exists:sensors,dui'],
            ]
        );

        return $this->roomRepository->update_room_devices($room, $validatedData);
    }
    // public function update_room_light(Request $request, Room $room)
    // {
    //     $validatedData = request()->validate(
    //         [

    //             'action' => ['required', Rule::in([0, 1]),],
    //             'sensorId' =>    ['required_unless:action,1'],
    //         ]
    //     );
    //     return $this->roomRepository->update_room_light($room, $validatedData);
    // }
    // public function update_room_panic_button(Request $request, Room $room)
    // {
    //     $validatedData = request()->validate(
    //         [

    //             'action' => ['required', Rule::in([0, 1]),],
    //             'sensorId' =>    ['required_unless:action,1'],
    //         ]
    //     );
    //     return $this->roomRepository->update_room_panic_button($room, $validatedData);
    // }
    // public function update_room_communication_channel(Request $request, Room $room)
    // {
    //     $validatedData = request()->validate(
    //         [

    //             'channel' =>    ['required', 'between:0,25'],
    //         ]
    //     );
    //     return $this->roomRepository->update_room_communication_channel($room, $validatedData);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function updateGroup(Request $request, Hospital $hospital)
    {
        $validatedData = request()->validate(
            [
                'groupId' => ["string", "nullable", "exists:hospital_groups,id"],
                'name' => ["required", "string"],
            ]
        );

        return $this->hospitalRepository->updateOrCreateGroup($hospital, $validatedData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        return $this->hospitalRepository->destroyRoom($room);
    }

   

}
