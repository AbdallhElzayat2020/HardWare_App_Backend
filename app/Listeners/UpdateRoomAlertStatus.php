<?php

namespace App\Listeners;

use App\Enums\ButtonStatus;
use App\Enums\LightStatus;
use App\Events\RoomAlertStatusChanged;
use App\Notifications\roomAlert;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateRoomAlertStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RoomAlertStatusChanged  $event
     * @return void
     */
    public function handle(RoomAlertStatusChanged $event)
    {


        $room = $event->room;
        if ($room->light_status == LightStatus::fall || $room->button_status == ButtonStatus::pressed) {
            $headNurs = $room->headNurse();
            $staff = $room->groups()->with('staff')->get()->pluck('staff')->flatten()->unique('id');

            // dd($staff );

            try {
                if ( !isset($headNurs->last_notification) || (Carbon::create($headNurs->last_notification))->isBefore(Carbon::now()->subSeconds(env('PUSH_LIMIT')))) {

                    $headNurs->notify(new roomAlert($event->room));
                    $headNurs->last_notification = Carbon::now();
                    $headNurs->save();
                }
            } catch (\Exception $e) {
                Log::error($e);
            }
            $staff->each(function ($staff) use ($event) {
                try {
                    if  (  !isset($staff->last_notification) || (Carbon::create($staff->last_notification))->isBefore(Carbon::now()->subSeconds(env('PUSH_LIMIT')))) {
                        $staff->notify(new roomAlert($event->room));
                        $staff->last_notification = Carbon::now();
                        $staff->save();
                    }
                } catch (\Exception $e) {
                    Log::error($e);
                }
            });
        }
    }
}
