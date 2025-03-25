<?php

namespace App\Http\Controllers;

use App\Http\Repositories\HospitalRepository;
use App\Http\Resources\HospitalDetailsResource;
use App\Http\Resources\HospitalResource;
use App\Models\Hospital;
use App\Models\HospitalGroup;
use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Http\Request;
use PHPUnit\TextUI\XmlConfiguration\Group;

class HospitalController extends Controller
{
    public function __construct(HospitalRepository $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
        $this->authorizeResource(Hospital::class, 'hospital');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return HospitalResource::collection($this->hospitalRepository->showAll());
    }
    public function admin_dashboard()
    {
        //
        if (auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->hospitalRepository->admin_dashboard(auth()->user());
    }
   
    public function delete(Hospital $hospital)
    {

        return $this->hospitalRepository->delete($hospital);
    }
    public function deleteGroup(HospitalGroup $group)
    {
        $this->authorize('delete',   Hospital::class);

        return $this->hospitalRepository->deleteGroup($group);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Hospital $hospital)
    {


        return new HospitalDetailsResource($hospital);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hospital $hospital)
    {
    }
    public function avaialble_devices(Request $request)
    {

         $this->authorize('viewAny',   [Hospital::class, auth()->user()]);

        return $this->hospitalRepository->avaialble_devices();
    }
    public function updateAdmin(Request $request, Hospital $hospital)
    {
        $this->authorize('create',   [Hospital::class, auth()->user()]);
        $validatedData1 = request()->validate(
            [
                'name' => ["required", "string"],
                'hospitalId' => ["exists:hospitals,id"],

            ]
        );
       $hos= $this->hospitalRepository->updateHospital($validatedData1);
// echo $hos->id;
        $validatedData = request()->validate(
            [
                'name' => ["required", "string"],
                'mobile' => ['required', 'string', 'min:10'],
                'role' => ["required", 'integer', 'between:0,3']
            ]
        );

        return $hos;
        // return $this->hospitalRepository->updateOrCreateAdmin($hospital, $validatedData);
    }

    public function updateAdmin2(Request $request)
    {
       
        $this->authorize('create',   [Hospital::class, auth()->user()]);
       
        $validatedData1 = request()->validate(
            [
                'name' => ["required", "string"],
                'hospitalId' => ["exists:hospitals,id"],

            ]
        );
       $hospital= $this->hospitalRepository->updateHospital2($validatedData1);
// echo $hospital;
        $validatedData = request()->validate(
            [
                'admin_name' => ["required", "string"],
                'mobile' => ['required', 'string', 'min:10'],
                'role' => ["required", 'integer', 'between:0,3']
            ]
        );

       
        return $this->hospitalRepository->updateOrCreateAdmin2($hospital, $validatedData);
    }
    public function updateHospital(Request $request)
    {
         $this->authorize('create',   [Hospital::class, auth()->user()]);

        $validatedData = request()->validate(
            [
                'name' => ["required", "string"],
                'hospitalId' => ["exists:hospitals,id"],

            ]
        );

        return $this->hospitalRepository->updateHospital($validatedData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getـstatistics(Request $request)
    {
        // echo($request->user());
        // $user=Staff::find(1);
        // echo($user);
        if (auth()->user()->isSuperAdmin()) {
            return $this->hospitalRepository->superadmin_statistics(auth()->user(),$request->h);
            // return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->hospitalRepository->admin_statistics(auth()->user());
    }
}
