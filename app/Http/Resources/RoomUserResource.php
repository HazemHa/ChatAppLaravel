<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RoomResources;
use App\Http\Resources\User as JsonUser;
class RoomUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'userInfo'=> new JsonUser ($this->userInfo),
            'roomInfo'=> new RoomResources ($this->roomInfo),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
