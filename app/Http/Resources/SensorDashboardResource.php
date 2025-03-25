<?php

namespace App\Http\Resources;

use App\Enums\ButtonStatus;
use App\Enums\LightStatus;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SensorDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    
    public function lightStatus($status)
    {
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
    public function buttonStatus($status)
    {
        switch ($status) {
            case ButtonStatus::pressed:
                return "pressed";
                break;
            case ButtonStatus::unpressed:
                return "not pressed";
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
        return  [

            'id' =>   (string) $this->id,

            'roomId' => (string) $this->whenNotNull($this->room?->id, null),
            'roomName' => (string) $this->whenNotNull($this->room?->name, null),
            'dui' => $this->dui,
            'communicationChannel' => $this->communication_channel,
            'lightId' => $this->lightId,
            'buttonId' => $this->buttonId,
            // 'sensorLogs' =>  SensorLogResource::collection($this->last10logs),
            'logTimestamp' => Carbon::create($this->last_log_created_at)->format('d/m/Y H:i:s A') ?? '',
            'motion' => $this->motion,
            'lastLogTimeStamp' => $lastlog != null ? Carbon::create($lastlog)->getPreciseTimestamp(3) : null,

            'logButtonStatus' => (string) $button_status,
            'logButtonStatusText' => $this->buttonStatus($button_status),
            'logLightStatusText' => $this->lightStatus($light_status),
            'lightStatus' => (string) $this->lightIndex($light_status)



        ];
    }
}
