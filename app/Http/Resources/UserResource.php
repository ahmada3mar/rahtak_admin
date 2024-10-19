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
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullName' =>  $this->name,
            'username' =>  $this->email,
            'email' =>  $this->email,
            'permissions' => $this->getAllPermissions()->map(function ($value) {
                return [
                    'name' => $value->name,
                    'group' => $value->group,
                ];
            }),
            'fav'=> $this->fav
        ];
    }
}
