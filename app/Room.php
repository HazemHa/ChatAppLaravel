<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['name', 'admin','number', 'description'];


    public function messages()
    {
        return $this->morphMany('App\Message', 'message_receiver');
    }
     public function adminInfo()
     {
         return $this->belongsTo('App\User','admin','id');
     }
}
