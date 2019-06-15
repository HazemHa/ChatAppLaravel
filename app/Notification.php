<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['sender_id', 'recevier_id','content'];

     public function senderInfo()
     {
         return $this->belongsTo('App\User','sender_id','id');
     }

     public function receiverInfo(){
         return $this->belongsTo('App\User', 'recevier_id','id');
     }
}
