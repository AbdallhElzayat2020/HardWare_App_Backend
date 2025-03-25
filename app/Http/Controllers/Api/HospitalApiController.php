<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\HospitalRepository;
use App\Http\Resources\HospitalDetailsResource;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\AdminDashboardResource;
use App\Models\Hospital;
use App\Models\HospitalGroup;
use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Http\Request;
use PHPUnit\TextUI\XmlConfiguration\Group;

class HospitalApiController extends Controller
{
    public function __construct(HospitalRepository $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
        // $this->authorizeResource(Hospital::class, 'hospital');
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
    public function index2()
    {
        //

        return HospitalResource::collection($this->hospitalRepository->showAll2());
    }
    public function admin_dashboard()
    {
        //
        
        if (auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->hospitalRepository->admin_dashboard(auth()->user());
    }
    public function admin_dashboard2(Request $request)
    {
        //
        // if (auth()->user()->isSuperAdmin()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
      return AdminDashboardResource::collection($this->hospitalRepository->admin_dashboard2($request->hospital_id))->where('id',$request->hospital_id)->first();
    //   return auth()->user()->hospit
    }
    public function delete(Hospital $hospital)
    {

        return $this->hospitalRepository->delete($hospital);
    }

    public function delete2(Hospital $hospital)
    {

        return $this->hospitalRepository->delete2($hospital);
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

        $validatedData = request()->validate(
            [
                'name' => ["required", "string"],
                'mobile' => ['required', 'string', 'min:10'],
                'role' => ["required", 'integer', 'between:0,3']
            ]
        );

        return $this->hospitalRepository->updateOrCreateAdmin($hospital, $validatedData);
    }
    public function updateHospital(Request $request)
    {
         $this->authorize('create',   [Hospital::class, auth()->user()]);

        $validatedData = request()->validate(
            [
                'name' => ["required", "string"],
                'user_id' => ["nullable"],
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
}
