<?php

namespace App\Http\Resources;

use App\Enums\ButtonStatus;
use App\Enums\LightStatus;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SensorLogResource extends JsonResource
{
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
        if ($status == null) {
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
        if ($status == null) {
            return "N/A";
        }
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
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return  [
            'id' =>   (string) $this->id,
            'sensorId' =>   (string) $this->sensor->id,
            'buttonStatus' => (string) $this->button_status,
            'timestamp' => Carbon::create($this->created_at)->getPreciseTimestamp(3),
            'fall' =>  (float) $this->fall,
            'lightStatus' => (string) $this->lightIndex($this->light_status),
            'motion' => ($this->motion > 0) ? true : false,
            'stand' =>    (float) $this->stand,
            'unoccupied' =>    (float) $this->unoccupied,
            // 'timestamp' => Carbon::create($this->created_at)->format('d/m/Y H:i:s A') ?? '',

            'buttonStatusText' => $this->buttonStatus($this->button_status),
            'lightStatusText' => $this->lightStatus($this->light_status),
            'trigger'=> $this->trigger,

        ];
    }
}
