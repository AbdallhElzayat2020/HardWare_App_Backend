<?php

namespace App\Http\Resources;

use App\Models\Staff;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalDetailsResource extends JsonResource
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
            'id' => (string) $this->id,
            'name' => $this->name,
            'admin' => new StaffResource($this->admin),
            'wards' =>   WardResource::collection($this->wards),
            'rooms' =>    RoomResource::collection($this->rooms),
            'nurses' =>    StaffResource::collection($this->nurses),
            'groups' =>    HospitalGroupResource::collection($this->groups),
            'headNurses' =>    StaffResource::collection($this->headNurses),


        ];
    }
}
