<?php


namespace App\Http\Repositories;

use App\Http\Resources\StatisticResource;
use App\Http\Resources\StatisticResourceHeadNurse;
use App\Http\Resources\AdminDashboardResource;
use App\Http\Resources\HospitalDetailsResource;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\NurseDashboardResource;
use App\Http\Resources\SensorDashboardResource;
use App\Http\Resources\SensorResource;
use App\Models\Hospital;
use App\Models\HospitalGroup;
use App\Models\Room;
use App\Models\Sensor;
use App\Models\Staff;
use App\Models\UserInterface;
use App\Models\Ward;
use PHPUnit\TextUI\XmlConfiguration\Group;

class HospitalRepository
{
    public function showAll()
    {
        $hospitals = Hospital::orderBy("created_at", "asc")->get();;
        return $hospitals;
    }

    public function showAll2()
    {
        $hospitals = auth()->user()->hospitals;
        return $hospitals;
    }

    function admin_dashboard(UserInterface $user)
    {

        $hospital = $user->hospital;
        // $rooms =   $hospital->rooms;
        // $groups =   $hospital->groups;
        // $nurses =   $hospital->staff;
        // $wards =   $hospital->wards;
        // // dd($nurses);
        // $allItems = collect(["groups" => $groups,"rooms" => $groups, "nurses" => $nurses, "wards" =>  $wards]);


        return new AdminDashboardResource($hospital);
    }

    function superadmin_statistics(UserInterface $user, $hospital_id)
    {

        if (isset($hospital_id)) {
            $hospital = Hospital::find($hospital_id);
            return new StatisticResource($hospital);
        } else {
            echo "Soon: statistics for all hospital togither";
        }

         $hospital =  $user->hospitals;
         echo $hospital;
         $rooms =   $hospital->rooms;
         $groups =   $hospital->groups;
         $nurses =   $hospital->staff;
         $wards =   $hospital->wards;
         // dd($nurses);
         $allItems = collect(["groups" => $groups,"rooms" => $groups, "nurses" => $nurses, "wards" =>  $wards]);


         return new StatisticResource($hospital);
//
    }

    function admin_statistics(UserInterface $user)
    {

        $hospital = $user->hospital;
        if ($user->role == 1) {
            $headNurse = Staff::with('ward.rooms')->find($user->id);

            // if ($headNurse) {
            //     $roomCount = $headNurse->ward->flatMap->rooms->count();
            //     echo "Total rooms under the supervision of this head nurse: $roomCount";
            // } else {
            //     echo "Head nurse not found!";
            // }
            return new StatisticResourceHeadNurse($headNurse);
        } else {
            return new StatisticResource($hospital);
        }

// echo $hospital;
        // echo $hospital->admin;
        // $rooms =   $hospital->rooms;
        // $groups =   $hospital->groups;
        // $nurses =   $hospital->staff;
        // $wards =   $hospital->wards;
        // // dd($nurses);
        // $allItems = collect(["groups" => $groups,"rooms" => $groups, "nurses" => $nurses, "wards" =>  $wards]);


        // return new StatisticResource($hospital);
    }

    function admin_dashboard2($hospital_id)
    {

        // $hospital =  $user->hospital;
        $hospital = auth()->user()->hospitals;
        // $rooms =   $hospital->rooms;
        // $groups =   $hospital->groups;
        // $nurses =   $hospital->staff;
        // $wards =   $hospital->wards;
        // // dd($nurses);
        // $allItems = collect(["groups" => $groups,"rooms" => $groups, "nurses" => $nurses, "wards" =>  $wards]);


        return new AdminDashboardResource($hospital);
    }

    function update_head_nurse(UserInterface $user, $validatedData, $ward)
    {

        $hospital = $user->hospital;
        if ($ward->headNurse != null) {
            if (count($ward->headNurse->ward) < 2) {
                $ward->headNurse->role = 2;
                $ward->headNurse->save();
            }
            // } else {
            $headNurse = Staff::findOrFail($validatedData['headNurseId']);
            $headNurse->role = 1;
            $headNurse->save();
        }
        $ward->head_nurse_id = $validatedData['headNurseId'];

        $ward->save();
        $hospital->refresh();
        return new AdminDashboardResource($hospital);
    }
    // function headNurseSensorsWithLastLog(UserInterface $user)
    // {

    //     $data =  $user->headNurseRooms()->with('sensors')->get();

    //     $sensors =  $data->map(function ($item) {

    //         return $item->sensors;
    //     });
    //     $flattened = $sensors->flatten()->unique();

    //     $flattened->all();
    //     // return ($flattened);
    //     return SensorDashboardResource::collection($flattened);
    // }
    function headNurseSensorsWithLastLog(UserInterface $user)
    {

        $data = $user->headNurseRooms;

        return NurseDashboardResource::collection($data);
    }

    function nurseDashboardLogs(UserInterface $user)
    {
        //TODO check this logic... !! user must see all sensors in his hospital

        $data = $user->groups()->with('rooms')->get();

        $rooms = $data->map(function ($item) {

            return $item->rooms;
        });

        $flattened = $rooms->flatten()->unique('id');

        // $flattened = $flattened->all();

        $unique = $flattened->all();

        return NurseDashboardResource::collection($flattened);
    }

    function nurseDashboardLogs2($hospital_id)
    {
        //TODO check this logic... !! user must see all sensors in his hospital

        $data = Room::whereRelation('ward', 'hospital_id', $hospital_id)->get()->load(['ward', 'groups']);


        return

            NurseDashboardResource::collection($data);
    }

    function userSensorsWithLastLog(UserInterface $user)
    {
        //TODO check this logic... !! user must see all sensors in his hospital
        if ($user->isAdmin()) {
            // $hospital = $user->hospital;
            $data = $user->hospital()->with('rooms.sensors')->get();
        } else {
            $data = $user->groups()->with('rooms.sensors')->get();
        }
        $sensors = $data->map(function ($item) {
            return $item->rooms->map(function ($room) {
                return $room->sensors;
            });
        });

        $flattened = $sensors->flatten()->unique();

        $flattened->all();

        return SensorDashboardResource::collection($flattened);
    }

    function allUserSensorLogs(UserInterface $user)
    {
        $data = $user->groups()->with('rooms.sensors')->get();
        $sensors = $data->map(function ($item) {
            return $item->rooms->map(function ($item) {
                return $item->sensors;
            });
        });
        $flattened = $sensors->flatten()->unique();

        $flattened->all();

        return SensorResource::collection($flattened);
    }

    function updateHospital($validatedData)
    {
        if (isset($validatedData['hospitalId'])) {
            $hospital = Hospital::find($validatedData['hospitalId']);
            $hospital->name = $validatedData['name'];

            $hospital->save();
        } else {
            $hospital = new Hospital();
            $hospital->name = $validatedData['name'];
            $hospital->user_id = isset($validatedData['user_id']) ? $validatedData['user_id'] : 0;
            $hospital->save();
        }
        $hospitals = Hospital::orderBy("created_at", "asc")->get();;
        return HospitalResource::collection($hospitals);
    }

    function updateHospital2($validatedData)
    {
        if (isset($validatedData['hospitalId'])) {
            $hospital = Hospital::find($validatedData['hospitalId']);
            $hospital->name = $validatedData['name'];

            $hospital->save();
        } else {
            $hospital = new Hospital();
            $hospital->name = $validatedData['name'];
            $hospital->user_id = isset($validatedData['user_id']) ? $validatedData['user_id'] : 0;
            $hospital->save();
        }
        // $hospitals = Hospital::orderBy("created_at", "asc")->get();;
        return $hospital;
    }

    function delete(Hospital $hospital)
    {


        // $hospital->wards()->delete();
        // $hospital->groups()->delete();

        // $hospital->nurses()->delete();
        $hospital->delete();

        $hospitals = Hospital::all();
        // $hospitals = Hospital::where('user_id', $user_id)->get();

        return HospitalResource::collection($hospitals);
    }

    function delete2(Hospital $hospital)
    {


        $user = auth()->user();
//   echo $user;
        // echo $hospital->user_id;
        // $hospital->wards()->delete();
        // $hospital->groups()->delete();

        // $hospital->nurses()->delete();
        $hospital->delete();

        // $hospitals = Hospital::all();
        $hospitals = Hospital::where('user_id', $user->id)->get();

        return HospitalResource::collection($hospitals);
    }

    function avaialble_devices()
    {
        $devices = Sensor::where('room_id', null)->get();
        return SensorResource::collection($devices);
    }

    function updateOrCreateRoom($hospital, $validatedData)
    {
        $ward = Ward::findOrFail($validatedData['ward']);

        if (isset($validatedData['roomId'])) {
            $room = Room::find($validatedData['roomId']);
            $room->name = $validatedData['name'];
            $room->ward()->associate($ward);
            $room->save();
        } else {
            $room = new Room();
            $room->name = $validatedData['name'];
            $room->ward()->associate($ward);
            // $room->hospital()->associate($hospital);

            $room->save();
        }

        $hospital->refresh();

        return new HospitalDetailsResource($hospital);
    }

    function deleteWard($ward)
    {
        $hospital = $ward->hospital;
        $ward->delete();
        $hospital->refresh();

        return new HospitalDetailsResource($hospital);
    }

    function destroyRoom($room)
    {

        $hospital = $room->ward->hospital;
        $room->delete();
        $hospital->refresh();

        return new HospitalDetailsResource($hospital);
    }

    function deleteGroup($group)
    {
        $hospital = $group->hospital;
        $group->delete();
        $hospital->refresh();

        return new HospitalDetailsResource($hospital);
    }

    function updateOrCreateWard($hospital, $validatedData)
    {
        // $ward = Ward::findOrFail($validatedData['ward']);

        if (isset($validatedData['wardId'])) {
            $ward = Ward::find($validatedData['wardId']);
            $ward->name = $validatedData['name'];
            $ward->hospital()->associate($hospital);
            $ward->save();
        } else {
            $ward = new Ward();
            $ward->name = $validatedData['name'];
            $ward->hospital()->associate($hospital);
            // $room->hospital()->associate($hospital);

            $ward->save();
        }

        $hospital->refresh();

        return new HospitalDetailsResource($hospital);
    }

    function updateOrCreateGroup($hospital, $validatedData)
    {
        // $ward = Ward::findOrFail($validatedData['ward']);

        if (isset($validatedData['groupId'])) {
            $group = HospitalGroup::find($validatedData['groupId']);
            $group->name = $validatedData['name'];
            $group->hospital()->associate($hospital);
            $group->save();
        } else {
            $group = new HospitalGroup();
            $group->name = $validatedData['name'];
            $group->hospital()->associate($hospital);
            // $room->hospital()->associate($hospital);

            $group->save();
        }

        $hospital->refresh();

        return new HospitalDetailsResource($hospital);
    }

    function updateOrCreateAdmin($hospital, $validatedData)
    {
        // return $hospital;
        $staff = Staff::ByMobile($validatedData['mobile']);
        if ($staff == null) {
            $staff = Staff::make([
                'name' => $validatedData['name'],
                'mobile' => $validatedData['mobile'],

            ]);
            $staff->role = 0;
            if ($hospital->admin != null) {
                $hospital->admin->delete();
            }
            $staff->hospital()->associate($hospital);
            $staff->save();
            $hospital->refresh();
            return new HospitalDetailsResource($hospital);
        } else {
            if ($hospital->admin != null && $hospital->admin->id == $staff->id) {
                $staff->name = $validatedData['name'] ?? $staff->name;
                $staff->save();
                $hospital->refresh();
                return new HospitalDetailsResource($hospital);
            } else {
                return response()->json(['error' => 'Admin already exists in another hospital'], 400);
            }
        }
    }

    function updateOrCreateAdmin2($hospital, $validatedData)
    {
        // return $hospital;
        $staff = Staff::ByMobile($validatedData['mobile']);

        if ($staff == null) {
            $staff = Staff::make([
                'name' => $validatedData['admin_name'],
                'mobile' => $validatedData['mobile'],

            ]);
            $staff->role = 0;
            if ($hospital->admin != null) {
                $hospital->admin->delete();
            }
            $staff->hospital()->associate($hospital);
            $staff->save();
            $hospital->refresh();
            return new HospitalDetailsResource($hospital);
        } else {
            if ($hospital->admin != null && $hospital->admin->id == $staff->id) {
                $staff->name = $validatedData['admin_name'] ?? $staff->name;
                $staff->save();
                $hospital->refresh();
                return new HospitalDetailsResource($hospital);
            } else {
                return response()->json(['error' => 'Admin already exists in another hospital'], 400);
            }
        }
    }
}
