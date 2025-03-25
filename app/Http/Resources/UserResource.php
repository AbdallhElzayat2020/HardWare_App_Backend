<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static $wrap = null;

    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'uid' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'mobile' => $this->mobile,
            'isSuperAdmin' => $this->isSuperAdmin(),
            'isAdmin' => $this->role === 0,
            'isHeadNurse' =>  $this->role === 1,
            'isNurse' =>  $this->role === 2,
        ];
    }
}
