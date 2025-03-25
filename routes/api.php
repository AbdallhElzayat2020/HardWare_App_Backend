<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Api\HospitalApiController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HospitalController;
use App\Http\Resources\UserResource;
use App\Http\Controllers\WardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Notifications\OneSignalNotification;
use App\Http\Controllers\NotificationController;

use App\Models\Staff;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
})->name('home');

Route::get('/hospitals', [HospitalApiController::class, 'index'])
    // ->middleware('auth:sanctum')
    ->name('hospitals');

Route::post('/hospitals2', [HospitalApiController::class, 'index2'])
    ->middleware('auth:sanctum');


Route::post('/login', [AuthenticatedSessionController::class, 'storeapi'])
    // ->middleware('guest')
    ->name('login');

Route::post('/create_admin', [AuthenticatedSessionController::class, 'create_admin'])
    // ->middleware('guest')
    ->name('create_admin');
// Route::post('/hospital_admin/{hospital}', [HospitalApiController::class, 'updateAdmin'])
// ->middleware('auth:sanctum')
// ->name('updateAdminHospital');
Route::post('/hospitals/update', [HospitalApiController::class, 'updateHospital'])
    ->middleware('auth:sanctum')
    ->name('updateHospitals');
// deleta a hospital
Route::post('/hospitals/{hospital}/delete', [HospitalApiController::class, 'delete2'])
    ->middleware('auth:sanctum')
    ->name('hospitals');


Route::post('/send_mobile_verification', [AuthenticatedSessionController::class, 'mobile_verification_admin'])
    ->name('mobile_verification_admin');

// Route::get('/users', [AuthenticatedSessionController::class, 'user'])
// ->middleware('auth:sanctum')
// ->name('user2');


Route::post('/device/register', [SensorController::class, 'register_device'])
    ->middleware('guest')
    ->name('register_device');
Route::post('/device/status', [SensorController::class, 'device_status'])
    ->middleware('guest')
    ->name('device_status');


Route::post('/mobile_login', [AuthenticatedSessionController::class, 'mobile_login_api'])
    ->name('mobile_login');
Route::post('/mobile_verification', [AuthenticatedSessionController::class, 'mobile_verification'])
    ->name('mobile_verification_api');
Route::get('/users', [AuthenticatedSessionController::class, 'user'])
    ->middleware('auth:sanctum')
    ->name('user2');
Route::post('/updatePushNotificationToken', [AuthenticatedSessionController::class, 'updatePushNotificationToken'])
    ->middleware('auth:sanctum')
    ->name('updatePushNotificationToken');
// Route::get('/dashboard_settings', [HospitalApiController::class, 'admin_dashboard'])
// ->name('adminDashboard');

Route::group(
    [
        'middleware' => 'auth:sanctum',
        'prefix' => 'nurse',
    ],

    function ($router) {

        // gel all sensors and their details for a specific user
        Route::get('/hospital/sensors', [SensorController::class, 'nurseDashboardLogs'])
            ->name('nurseDashboardLogs');
        Route::get('/hospital/sensors2', [SensorController::class, 'nurseDashboardLogs2'])
            ->name('nurseDashboardLogs2');

        // Route::post('/update_head_nurse/{ward}', [WardController::class, 'updateHospitalWard'])
        //     ->name('update_head_nurse');
        // Route::post('/update_nurse_group/{staff}', [StaffController::class, 'updateNurseGroups'])
        //     ->name('update_nurse_group');
        // Route::post('/update_room_group/{room}', [RoomController::class, 'updateRoomGroups'])
        //     ->name('update_room_group');
    }
);
Route::post('/dashboard_settings', [HospitalApiController::class, 'admin_dashboard2'])->middleware('auth:sanctum');
Route::post('/ward/{hospital}', [WardController::class, 'updateWard'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalWard');
Route::post('/ward/{ward}/delete', [WardController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('deleteWard');

// update or create a new room within a hospital
Route::post('/room/{hospital}', [RoomController::class, 'updateRoom'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalRoom');

// delete room in a hospital
Route::post('/room/{room}/delete', [RoomController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('deleteRoom');

// get a hospital by id
Route::get('/hospital/{hospital}', [HospitalApiController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('hospital');

// update or create a new ward within a hospital
Route::post('/group/{hospital}', [RoomController::class, 'updateGroup'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalGroup');
// delete group in a hospital
Route::post('/group/{group}/delete', [HospitalApiController::class, 'deleteGroup'])
    ->middleware('auth:sanctum')
    ->name('deleteGroup');

// update or create a nurse in a hospital
Route::post('/nurse/{hospital}', [StaffController::class, 'updateHospitalNurse'])
    ->middleware('auth:sanctum')
    ->name('updateHospitalNurse');

Route::get('/room/{room}/sensorlogs', [RoomController::class, 'show_sensor_logs'])
    ->middleware('auth:sanctum')
    ->name('sensorLogs');

// list all unassigned devices
Route::get('/avaialbleDevices', [HospitalApiController::class, 'avaialble_devices'])
    ->middleware('auth:sanctum')
    ->name('avaialbleDevices');

// add main sensor to a room
Route::post('/room/{room}/updateRoomDevices', [RoomController::class, 'update_room_devices'])
    ->middleware('auth:sanctum')
    ->name('updateRoomDevice');


Route::group(
    [
        'middleware' => 'auth:sanctum',
        'prefix' => 'admin',
    ],

    function ($router) {

        // gel all sensors and their details for a specific user
        Route::get('/hospital/sensors', [SensorController::class, 'index'])
            ->name('userSensors');

        Route::get('/dashboard_settings', [HospitalApiController::class, 'admin_dashboard'])
            ->name('adminDashboard');

        Route::post('/update_head_nurse/{ward}', [WardController::class, 'updateHospitalWard'])
            ->name('update_head_nurse');

        Route::post('/update_nurse_group/{staff}', [StaffController::class, 'updateNurseGroups'])
            ->name('update_nurse_group');

        Route::post('/update_room_group/{room}', [RoomController::class, 'updateRoomGroups'])
            ->name('update_room_group');
    }
);


Route::get('/send-notification', [NotificationController::class, 'sendNotificationByExternalId']);


// Route::get('/send-notification', function () {
//     $externalUserId = 31;// get the external user ID
// // $user = Staff::where('id', $externalUserId)->first();
// $user =Staff::find(1);
// if ($user) {
//     $user->notify(new OneSignalNotification());
// } else {
//     echo "no user";
//     // Handle the case where the user with the specified external ID is not found.
// }
//     return "Notification sent!";
// });
