<?php

namespace App\Http\Resources;

use App\Models\HospitalGroup;
use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticResourceHeadNurse extends JsonResource
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
                'wards' =>   $this->ward->count(),
                'rooms' =>    $this->headNurseRooms->count(),
                'sensors'=>$this->headNurseRooms->flatMap->sensors->count(),
                // 'nurses' =>    $this->nurses->count(),
                'groups' =>    $this->groups->count(),

            ];
    }
}
