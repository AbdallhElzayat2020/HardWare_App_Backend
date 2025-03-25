<?php

namespace App\Http\Controllers;

use App\Http\Repositories\HospitalRepository;
use App\Models\Hospital;
use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{

    public function __construct(HospitalRepository $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
        $this->authorizeResource(Ward::class, 'ward');
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
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function show(Ward $ward)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function edit(Ward $ward)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\Response
     */

    public function updateHospitalWard(Request $request, Ward $ward)
    {


        $validatedData = request()->validate(
            [
                'headNurseId' => ["string", "nullable", "exists:staff,id"],
            ]
        );

        return $this->hospitalRepository->update_head_nurse(auth()->user(), $validatedData, $ward);
    }
    public function updateWard(Request $request, Hospital $hospital)
    {
        $validatedData = request()->validate(
            [
                'wardId' => ["string", "nullable", 'exists:wards,id'],
                'name' => ["required", "string"],
            ]
        );

        return $this->hospitalRepository->updateOrCreateWard($hospital, $validatedData);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ward $ward)
    {
        return $this->hospitalRepository->deleteWard($ward);
    }
}
