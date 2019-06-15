<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = ['sender_id', 'message_receiver_id', 'message_receiver_type','body','image','file'];


    public function message_receiver()
    {
        return $this->morphTo();
    }
     public function sender()
     {
         return $this->belongsTo('App\User', 'sender_id', 'id');
     }

     public function rooms()
     {
         return $this->belongsTo('App\Room','room_id','id');
     }
}
