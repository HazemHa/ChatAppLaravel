<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomUsers extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id','room_id'];

      public function userInfo()
      {
          return $this->belongsTo('App\User', 'user_id','id');
      }

      public function roomInfo()
      {
          return $this->belongsTo('App\Room','room_id','id');
      }
}
