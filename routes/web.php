<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WardController;
use App\Http\Resources\UserResource;
use App\Models\Hospital;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
})->name('home');

require __DIR__ . '/auth.php';


// gel all hospitals and their details
Route::get('/hospitals', [HospitalController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('hospitals');


// deleta a hospital
Route::post('/hospitals/{hospital}/delete', [HospitalController::class, 'delete'])
    ->middleware('auth:sanctum')
    ->name('hospitals');
// create or update a hospital
Route::post('/hospitals/update', [HospitalController::class, 'updateHospital'])
    ->middleware('auth:sanctum')
    ->name('updateHospitals');

// get a hospital by id
Route::get('/hospital/{hospital}', [HospitalController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('hospital');

// update or create admins within a hospital
Route::post('/hospital_admin/{hospital}', [HospitalController::class, 'updateAdmin'])
    ->middleware('auth:sanctum')
    ->name('updateAdminHospital');
    Route::post('/hospital_admins/update', [HospitalController::class, 'updateAdmin2'])
    ->middleware('auth:sanctum')
    ->name('updateAdminHospital2');

// update or create a new room within a hospital
Route::post('/room/{hospital}', [RoomController::class, 'updateRoom'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalRoom');
// update or create a new ward within a hospital
Route::post('/ward/{hospital}', [WardController::class, 'updateWard'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalWard');
// delete ward in a hospital
Route::post('/ward/{ward}/delete', [WardController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('deleteWard');
// delete group in a hospital
Route::post('/group/{group}/delete', [HospitalController::class, 'deleteGroup'])
    ->middleware('auth:sanctum')
    ->name('deleteGroup');
// delete room in a hospital
Route::post('/room/{room}/delete', [RoomController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('deleteRoom');
// update or create a new ward within a hospital
Route::post('/group/{hospital}', [RoomController::class, 'updateGroup'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalGroup');
// update or create a nurse in a hospital
Route::post('/nurse/{hospital}', [StaffController::class, 'updateHospitalNurse'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalNurse');


// update or create a nurse in a hospital
Route::get('/room/{room}/sensorlogs', [RoomController::class, 'show_sensor_logs'])
    ->middleware('auth:sanctum')
    ->name('sensorLogs');
// list all unassigned devices
Route::get('/avaialbleDevices', [HospitalController::class, 'avaialble_devices'])
    ->middleware('auth:sanctum')
    ->name('avaialbleDevices');

// add main sensor to a room
Route::post('/room/{room}/updateRoomDevices', [RoomController::class, 'update_room_devices'])
    ->middleware('auth:sanctum')
    ->name('updateRoomDevice');
// add/delete light device to a room
// Route::post('/room/{room}/updateRoomLight', [RoomController::class, 'update_room_light'])
//     ->middleware('auth:sanctum')
//     ->name('updateRoomLight');
// add/delete panic button to a room
// Route::post('/room/{room}/updateRoomPanicButton', [RoomController::class, 'update_room_panic_button'])
//     ->middleware('auth:sanctum')
//     ->name('updateRoomPanicButton');
// update room communication channel
// Route::post('/room/{room}/updateHospitalCommunicationChannel', [RoomController::class, 'update_room_communication_channel'])
//     ->middleware('auth:sanctum')
//     ->name('updateHospitalCommunicationChannel');

Route::group(
    [
        'middleware' => 'auth:sanctum',
        'prefix' => 'admin',
    ],

    function ($router) {

        // gel all sensors and their details for a specific user
        Route::get('/hospital/sensors', [SensorController::class, 'index'])
            ->name('userSensors');
        Route::get('/dashboard_settings', [HospitalController::class, 'admin_dashboard'])
            ->name('adminDashboard');
        Route::post('/update_head_nurse/{ward}', [WardController::class, 'updateHospitalWard'])
            ->name('update_head_nurse');
        Route::post('/update_nurse_group/{staff}', [StaffController::class, 'updateNurseGroups'])
            ->name('update_nurse_group');
        Route::post('/update_room_group/{room}', [RoomController::class, 'updateRoomGroups'])
            ->name('update_room_group');
    }
);
Route::group(
    [
        'middleware' => 'auth:sanctum',
        'prefix' => 'head_nurse',
    ],

    function ($router) {

        // gel all sensors and their details for a specific user
        Route::get('/hospital/sensors', [SensorController::class, 'head_nurse_index'])
            ->name('headNurseSensors');
        Route::post('/dimissRoomAlerts/{room}', [RoomController::class, 'dismiss_room_aert'])
            ->name('dimissRoomAlerts');
    }
);

Route::post('/send_mobile_verification', [AuthenticatedSessionController::class, 'mobile_verification_admin'])
->name('mobile_verification_admin');

Route::post('/getـstatistics', [HospitalController::class, 'getـstatistics'])
->middleware('auth:sanctum')
->name('getStatistics');


Route::get('/room/{room}/sensorlogs2', [RoomController::class, 'show_sensor_logs2'])
    ->middleware('auth:sanctum')
    ->name('sensorLogs2');
