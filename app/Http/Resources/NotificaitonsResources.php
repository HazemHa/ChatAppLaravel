<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserJson;
class NotificaitonsResources extends JsonResource
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
            'notify_id'=>$this->id,
            'senderInfo'=> new UserJson($this->senderInfo),
        //    'recevierInfo'=>$this->whenLoaded('new UserJson($this->receiverInfo)'),
            'content'=>$this->content,
            'seen'=>$this->seen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
