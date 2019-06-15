<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','created_at','updated_at', 'remember_token'
    ];

    public function messagesReceiver()
    {
        return $this->morphMany('App\Message', 'message_receiver');
    }

    public function messagesSended(){
        return $this->hasMany('App\Message','sender_id','id');
    }

    public function rooms(){
        return $this->hasMany('App\RoomUsers','user_id','id');
    }

    public function FriendRequest(){
        return $this->hasMany('App\Friend','user_id','id');
    }
    public function FriendSended(){
        return $this->hasMany('App\Friend', 'friend_id','id');
    }
    public function available_friends($data)
    {
        return $data->where('isPending', '=',0);
    }

    public function myNotify(){
        return $this->hasMany('App\Notification', 'recevier_id','id');
    }
}
