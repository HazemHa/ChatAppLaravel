<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'friends';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id','friend_id'];

     public function friendInfo()
     {
        return $this->belongsTo('App\User', 'friend_id', 'id');
     }
     public function userInfo(){
         return $this->belongsTo('App\User', 'user_id', 'id');
     }
          //return friends which they blocked
     public function isBlocked($to,$from){
         return $this->where('friend_id',$to)->orWhere('user_id',$from);
     }
}
