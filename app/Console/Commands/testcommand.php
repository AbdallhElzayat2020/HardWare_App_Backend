<?php

namespace App\Console\Commands;

use App\Enums\ButtonStatus;
use App\Enums\LightStatus;
use App\Events\RoomAlertStatusChanged;
use App\Http\Resources\NurseDashboardResource;
use App\Http\Resources\NurseWebSocketResource;
use App\Http\Resources\SensorDashboardResource;
use App\Models\Room;
use App\Models\Sensor;
use App\Models\SensorLog;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Console\Command;

class testcommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testcommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $room = Room::find(56);
        $motion = 1;
        $light_status = LightStatus::occupied;
        $button_status = ButtonStatus::pressed;
        $last_log_created_at = Carbon::now();
        $room->sensors->each(function ($sensor) use ($motion, $light_status, $button_status, $last_log_created_at) {

            $sensorLog  = SensorLog::make([
                'fall' => .02,
                'stand' => .02,
                'unoccupied' => .02,
                'light_status' => $light_status,
                'button_status' => $button_status,
                'motion' => $motion,
            ]);
            $sensorLog->sensor()->associate($sensor);
            $sensorLog->save();
            $sensor->required_light_status = $light_status;
            $sensor->required_button_status = $button_status;
            $sensor->light_status = $light_status;
            $sensor->button_status = $button_status;
            $sensor->motion = $motion;
            $sensor->last_log_created_at = $last_log_created_at;
            $sensor->save();
        });
        if ($room->sensors->where('light_status', LightStatus::fall)->count() > $room->sensors->count() * 0.8) {
            $room->light_status = LightStatus::fall;
        } else if ($room->sensors->where('light_status', LightStatus::occupied)->count() > $room->sensors->count() * 0.5) {
            $room->light_status = LightStatus::occupied;
        } else {
            $room->light_status = LightStatus::unoccupied;
        }
        if ($room->sensors->where('button_status', ButtonStatus::pressed)->count() > 0) {
            $room->button_status = ButtonStatus::pressed;
        } else {
            $room->button_status = ButtonStatus::unpressed;
        }
        // $d = ($room->sensors->max('last_log_created_at'));
        $room->last_log_created_at = ($room->sensors->max('last_log_created_at'));
        $room->motion = $room->sensors->max('motion');
        $room->save();


        //TODO get all users and send notifications
        //1 - get users
        $headNurse = $room->headNurse();

        //2 -- get required data
        $nurses = $room->groups()->with("staff")->get();
        $nurses =  $nurses->map(function ($item) {
            return $item->staff;
        });
        $nurses = $nurses->flatten()->unique('id');

        $data =  new NurseWebSocketResource($room);

        foreach ($nurses as $nurse) {
            RoomAlertStatusChanged::dispatch($data, $nurse, $room);
        }
        // RoomAlertStatusChanged::dispatch($data, $user, $room);

        // $user = Staff::find(23);




        // $data =  $user->groups()->with('rooms')->get();

        // $rooms =  $data->map(function ($item) {

        //     return $item->rooms;
        // });

        // $flattened = $rooms->flatten()->unique('id');




        $headNurseData =  new NurseWebSocketResource($room);
        if($headNurse != null ){
            RoomAlertStatusChanged::dispatch($headNurseData, $headNurse, $room);

        }        return 0;
    }
}
