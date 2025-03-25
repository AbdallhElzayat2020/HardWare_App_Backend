<?php

namespace App\Http\Controllers;

use App\Http\Repositories\HospitalRepository;
use App\Http\Repositories\SensorRepository;
use App\Models\Sensor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class SensorController extends Controller
{
    public function __construct(HospitalRepository $hospitalRepository, SensorRepository $sensorRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
        $this->sensorRepository = $sensorRepository;
        $this->authorizeResource(Sensor::class, 'sensor');
    }

    public function index()
    {
        //

        return $this->hospitalRepository->userSensorsWithLastLog(auth()->user());
    }
    public function nurseDashboardLogs()
    {
        //
        $this->authorize('nurse_dashboard_logs',   Sensor::class);

        return $this->hospitalRepository->nurseDashboardLogs(auth()->user());
    }
    public function nurseDashboardLogs2(Request $request)
    {
        //
        $this->authorize('nurse_dashboard_logs',   Sensor::class);

        return $this->hospitalRepository->nurseDashboardLogs2($request->hospital_id);
    }
//     public function nurseDashboardLogs2(Request $request)
//     {
//         //
//         // $this->authorize('nurse_dashboard_logs',   Sensor::class);
//         $token = $request->header('Authorization');
// $user = auth()->user();
// // $user=Auth::user();
// $user =new UserResource(Auth::user());
// // return json_encode($user);
//         return $this->hospitalRepository->nurseDashboardLogs($user);
//     }
    public function register_device()
    {
        $validatedData = request()->validate(
            [
                'device_id' => ["string", "required"],

            ]
        );

        return  $this->sensorRepository->register_device($validatedData);
    }
    public function device_status()
    {


        $validatedData = request()->validate(
            [
                // 'device_id' => [ "required","exists:sensors,dui"],
                'device_id' => ["string", "required", "exists:sensors,dui"],
                'stand' => ["numeric"],
                'fall' => ["numeric"],
                'unoccupied' => ["numeric"],
                'fall_ave' => ["numeric"],
                'stand_ave' => ["numeric"],
                'unoccupied_ave' => ["numeric"],
                'light_status' => ["numeric"],
                'button_status' => ["numeric"],
                'motion' => ["numeric"],
                'result' => ["string"],
                'light_id' => ["string"],
                'pb_id' => ["string"],
                'pb_id_battery' => ["numeric"],
                'channel' => ["numeric"],

            ]
        );

        // $bodyContent = request()->getContent();

        // dd("ddd")
        return  $this->sensorRepository->update_device_status($validatedData);
    }
    public function head_nurse_index()
    {
        $this->authorize('head_nurse_index',   Sensor::class);


        return $this->hospitalRepository->headNurseSensorsWithLastLog(auth()->user());
    }
    //
  
}
