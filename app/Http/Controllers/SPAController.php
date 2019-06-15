<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\FriendRequest;
use App\Events\MessageSent;
use App\Events\NotificationRequest;
use App\Http\Resources\User as UserJson;
use App\Room;
use App\User;
use App\Http\Resources\RoomResources;
use App\Http\Requests\MessageRequet;
use App\Friend;
class SPAController extends Controller
{
    //

    public function Index(){

        return view('app');
    }

    public function sendMessage(MessageRequet $request){
        // check the type of chat not public to save message to data base
        // check the friend in private chat he is blocked ot not
        if (\Auth::check() && $request->type == 'private' || $request->type == 'room') {
            $data = Friend::where('friend_id', $request->to)->orWhere('user_id', $request->sender['id'])->first();
            if ($data != null) {
                if ($data->isBlock == 0) {
                    return MessageController::instance()->store($request);

                } else if ($data->isBlock == 1) {
                    return response()->json(['error', 'this user is blocked !!']);

                }
            }
        }

        $data = [
            'type' => $request->type,
            'sender'=>$request->sender,
            'to' => $request->to,
            'content' => $request->content,
        ];

        broadcast(new MessageSent($data));

        return response()->json(['data'=>$data]);
    }
/*
    public function notify(Request $request){
        $request->validate(
            [
                'to' => 'required',
            ]
        );
        $data = [
             // \Auth::user()->name . '  can we be a friends
            'to' => $request->to,
            'sender_id'=> \Auth::user()->id,
            'content' => $request->content
        ];

        $result = broadcast(new NotificationRequest($data))->toOthers();
        return response()->json(['success' => $result, 'data' => $data['to']]);



    }
    */
    public function login(Request $request)
    {
         // set access token for use
        $credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials)) {
            // Authentication passed...
            $token = \Auth::user()->createToken('remember_token')->accessToken;
            \Auth::user()->remember_token = $token;
            \Auth::user()->save();
            return response()->json(['authUser' => new UserJson(\Auth::user()), 'token' => $token]);

        }

        return response()->json(['error' => 'please confirm form credentials ']);

    }

    public function logout(Request $request)
    {
        \Auth::user()->remember_token = null;
        return $this->handlerResult(\Auth::user()->save());
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => \Hash::make($request['password']),
        ]);

        \Auth::loginUsingId($user->id);
        $token = \Auth::user()->createToken('remember_token')->accessToken;
        \Auth::user()->remember_token = $token;
        \Auth::user()->save();
        return response()->json(
            ['authUser' => new UserJson(\Auth::user()),
            'token'=> $token
            ]);
    }
}
