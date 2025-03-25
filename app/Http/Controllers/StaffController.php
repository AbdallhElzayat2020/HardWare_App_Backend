<?php

namespace App\Http\Controllers;

use App\Http\Repositories\StaffRepository;
use App\Models\Hospital;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
        $this->authorizeResource(Staff::class, 'staff');
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
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        dd($staff);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function updateHospitalNurse(Request $request, Hospital $hospital)
    {
        $this->authorize('updateHospitalNurse',   [Staff::class]);

        $validatedData = request()->validate(
            [
                'nurseId' => ["string", "nullable", 'exists:staff,id'],
                'hospitalId' => ["required", "string", 'exists:hospitals,id'],
                'name' => ["required", "string"],
                'mobile' => ['required', 'string',],
                'groups' => ['array', 'exists:hospital_groups,id'],
            ]
        );

        return $this->staffRepository->updateOrCreateNurse($hospital, $validatedData);
    }
    public function updateNurseGroups(Request $request, Staff $staff)
    {
        $validatedData = request()->validate(
            [

                'groups' => ['array', 'exists:hospital_groups,id'],
            ]
        );

        return $this->staffRepository->updateNurseGroups($staff, $validatedData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        //
    }
}
