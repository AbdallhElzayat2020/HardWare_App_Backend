<?php

namespace App\Http\Repositories;

use App\Http\Resources\AdminDashboardResource;
use App\Http\Resources\HospitalDetailsResource;
use App\Models\Staff;

class StaffRepository
{
    function updateNurseGroups($staff, $validatedData)
    {


        if (isset($validatedData['groups'])) {
            $staff->groups()->sync($validatedData['groups']);
        } else {
            $staff->groups()->sync([]);
        }
        $staff->save();
        $hospital =  $staff->hospital;

        return new AdminDashboardResource($hospital);
    }

    function updateOrCreateNurse($hospital, $validatedData)
    {
        // $ward = Ward::findOrFail($validatedData['ward']);

        if (isset($validatedData['nurseId'])) {
            $nurse = Staff::find($validatedData['nurseId']);
            $nurse->name = $validatedData['name'];
            $nurse->role = 2;
            $nurse->mobile = $validatedData['mobile'];
            $nurse->hospital()->associate($hospital);
            $nurse->save();
        } else {
            $nurse = new Staff();
            $nurse->name = $validatedData['name'];
            $nurse->mobile = $validatedData['mobile'];
            $nurse->role = 2;
            $nurse->hospital()->associate($hospital);
            // $room->hospital()->associate($hospital);

            $nurse->save();
        }
        if (isset($validatedData['groups'])) {
            $nurse->groups()->sync($validatedData['groups']);
        }


        $hospital->refresh();

        return new HospitalDetailsResource($hospital);
    }
}
