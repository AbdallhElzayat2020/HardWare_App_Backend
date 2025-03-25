<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SensorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' =>   (string) $this->id,

            'roomId' => (string) $this->whenNotNull($this->room?->id, null),
            'roomName' => (string) $this->whenNotNull($this->room?->name, null),
            'dui' => $this->dui,
            'communicationChannel' => $this->communication_channel,
            'lightId' => $this->lightId,
            'buttonId' => $this->buttonId,
            'sensorLogs' =>  SensorLogResource::collection($this->last10logs),
            'battery_level'=>$this->battery_level,
        ];
    }
}

