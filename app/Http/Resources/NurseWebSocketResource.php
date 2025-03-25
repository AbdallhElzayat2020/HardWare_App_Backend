<?php

namespace App\Http\Resources;

use App\Enums\ButtonStatus;
use App\Enums\LightStatus;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NurseWebSocketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function lightIndex($status){
        switch ($status) {
            case LightStatus::occupied_alerm:
            case LightStatus::fall:
                return 0;
                break;
            case LightStatus::occupied_warning:
                // if ($this->motion > 50) {
                // return "occupied - with motion detected";
                // } else {
                return 1;
                // }
                break;
            case LightStatus::occupied:
                // if ($this->motion > 50) {
                // return "occupied - with motion detected";
                // } else {
                return 1;
                // }
                break;

            case LightStatus::unoccupied:
                return 2;
                break;
            default:
                return $status;
                break;
        }
    }
    public function lightStatus($status)
    {
        if ($status === null) {
            return "N/A";
        }
        switch ($status) {
            case LightStatus::occupied_alerm:
            case LightStatus::fall:
                return "fall";
                break;
            case LightStatus::occupied_warning:
                // if ($this->motion > 50) {
                // return "occupied - with motion detected";
                // } else {
                return "occupied - no motion detected";
                // }
                break;
            case LightStatus::occupied:
                // if ($this->motion > 50) {
                // return "occupied - with motion detected";
                // } else {
                return "occupied";
                // }
                break;

            case LightStatus::unoccupied:
                return "unoccupied";
                break;
            default:
                return "N/A";
                break;
        }
    }
    public function buttonStatus($status)
    {
        if ($status === null) {
            return "N/A";
        }
        switch ($status) {
            case ButtonStatus::pressed:
                return "pressed";
                break;
            case ButtonStatus::unpressed:
                return "unpressed";
                break;

            default:
                return "N/A";
                break;
        }
    }

    public function toArray($request)
    {
        // $lastLog = $this->lastlog();
        $light_status =  $this->light_status;
        $button_status = $this->button_status;
        $lastlog = $this->lastlog()->created_at ?? null;
        if ($lastlog != null) {
            if (Carbon::create($lastlog)->isBefore(Carbon::now()->subMinutes(10))) {
                $lastlog = null;
            }
        }
        $battery_level = null;

        if ($this->sensors->first() !== null) {
            $battery_level = $this->sensors->first()->battery_level;
        }
        return  [
            'id' =>   (string) $this->id,
            'name' => $this->name,
            'status' => $this->status,
            // 'headNurse' => new StaffResource($this->headNurse()),
            // 'ward' => new WardResource($this->ward),
            // 'sensors' =>   $this->whenLoaded('sensors', function () {
            //     return  SensorResource::collection($this->sensors);
            // },),
            // 'groups' =>   HospitalGroupResource::collection($this->groups),


            'lastLogTimeStamp' => $lastlog != null ? Carbon::create($lastlog)->getPreciseTimestamp(3) : null,

            'buttonStatus' => (string) $button_status,
            'buttonStatusText' => $this->buttonStatus($button_status),
            'lightStatusText' => $this->lightStatus($light_status),
            'lightStatus' => (string) $this->lightIndex($light_status),
            'battery_level'=> $battery_level,


        ];
    }
}
