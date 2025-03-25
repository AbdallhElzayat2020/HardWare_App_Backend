<?php

namespace App\Http\Resources;

use App\Models\Ward;
use Illuminate\Http\Resources\Json\JsonResource;

class WardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' =>  (string)  $this->id,
            'name' => $this->name,
            // 'headNurse' => $this->headNurse,
            'headNurse' => new StaffResource($this->headNurse),


        ];
    }
}
