<?php

namespace App\Http\Resources;

use App\Models\HospitalGroup;
use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return

            [
                'id' => (string) $this->id,
                'name' => $this->name,
                // 'admin' => new StaffResource($this->admin),
                'wards' =>   WardResource::collection($this->wards),
                'rooms' =>    RoomResource::collection($this->rooms),
                'nurses' =>    StaffResource::collection($this->staff),
                'groups' =>    HospitalGroupResource::collection($this->groups),

            ];
    }
}
