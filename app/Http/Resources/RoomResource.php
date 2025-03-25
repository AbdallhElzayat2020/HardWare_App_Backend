<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
            'headNurse' => new StaffResource($this->headNurse()),
            'ward' => new WardResource($this->ward),
            'sensors' =>   $this->whenLoaded('sensors', function () {
                return  SensorResource::collection($this->sensors);
            },),
            'groups' =>   HospitalGroupResource::collection($this->groups)



        ];
    }
}

