<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserJson;
use App\Http\Resources\RoomResources;
class MessageResources extends JsonResource
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
         //   'id' => $this->id,
        //    'receiver_user' => $this->when($this->message_receiver_type == 'App\User',new UserJson($this->message_receiver)),
        //   'receiver_room' => $this->when($this->message_receiver_type == 'App\Room', new RoomResources($this->message_receiver)),
            'sender' => new UserJson($this->sender),
            'content' => $this->body,
            'image' => $this->image,
            'file' => $this->file,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
