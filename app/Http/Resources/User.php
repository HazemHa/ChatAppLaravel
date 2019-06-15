<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MessageResources;
use App\Http\Resources\RoomResources;
use App\Http\Resources\FriendJson;
use App\Http\Resources\NotificaitonsResources;
class User extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar'=>$this->avatar,
            'messagesReceiver' => MessageResources::collection($this->whenLoaded('messagesReceiver')),
            'rooms' => RoomResources::collection($this->whenLoaded('rooms')),
            'friendInfo'=> FriendJson::collection($this->whenLoaded('friends')),
            'myNotify'=> NotificaitonsResources::collection($this->whenLoaded('myNotify')),
     //       'remember_token' => $this->remember_token,
     //       'created_at' => $this->created_at,
     //       'updated_at' => $this->updated_at,
        ];
    }
}
