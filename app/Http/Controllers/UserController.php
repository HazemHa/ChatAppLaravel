<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Room;
use App\Http\Resources\User as UserJson;
use App\Http\Resources\FriendJson;
use App\Notification;
use App\Http\Resources\NotificaitonsResources;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create new user
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $user = User::find($id);
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = \Hash::make($request['password']);
        if ($request->hasFile('avater')) {
            $path = $request->file('avater')->store('public/profiles');
            $user->avatar = $path;
        }

        $result = $user->save();
        return $this->ResponseJson($result, 'add user ');
        
    }
    
    public function mynotification(){
        $seen = \Auth::user()->myNotify->where('seen',1);
        $unseen = \Auth::user()->myNotify->where('seen', 0);
        // $unseen used just for count unseen notifications
        return ['seen'=>NotificaitonsResources::collection($seen),
                'unseen'=> NotificaitonsResources::collection($unseen)
        ]; 
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // update profile user
        $user = User::find($id);
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = \Hash::make($request['password']);
        if ($request->hasFile('avater')) {
            $path = $request->file('avater')->store('public/profiles');
            $afterEdit = str_replace('public/', '', $path);
            $user->avatar = $afterEdit;
        }
        $result = $user->save();
        return $this->ResponseJson($result, 'update profile ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::destroy($id);
        return $this->handlerResult($user);
    }
}
