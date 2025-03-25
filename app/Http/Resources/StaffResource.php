<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'id' =>    (string)$this->id,
            'uid' =>    (string)$this->id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'groups' => HospitalGroupResource::collection($this->groups),

        ];
    }
}
