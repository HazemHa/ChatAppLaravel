<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserJson;
class FriendJson extends JsonResource
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
            'friendInfo'=>$this->friendInfo,
            'userInfo' => $this->userInfo,
            'isBlock'=>$this->isBlock,
            'isPending'=>$this->isPending,
        ];
    }
}
