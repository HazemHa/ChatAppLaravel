<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\Friend as FriendRequest;
use App\Http\Resources\FriendJson;
use App\Events\NotificationRequest;
use App\Http\Resources\User as UserJson;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        //
        $data = \Auth::user()->FriendRequest->union(\Auth::user()->FriendSended);
        $result = \Auth::user()->available_friends($data);
        return FriendJson::collection($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function notifyRequestFriend(FriendRequest $request){
         // notify another user about your request friend
        $notify = new Notification;
        $contentNotify = \Auth::user()->name . '  send request friend to you ';
        $notify->sender_id = \Auth::user()->id;
        $notify->recevier_id = $request->friend_id;
        $notify->content = $contentNotify;
        $result = $notify->save();
        if($result){
        $data = [
            'to' => $request->friend_id,
            'content' => $contentNotify,
            'senderInfo' => new UserJson($notify->senderInfo),
        ];
        broadcast(new NotificationRequest($data))->toOthers();
        }
    return $this->ResponseJson($result, 'add friend ');
     }
     public function notifyAcceptFriend(FriendRequest $request){
         // update the requestFriend 
         // they are saved in same row
         // so we check form (friend_id,user_id) to return same row
         $isHere  = Friend::where([['user_id',\Auth::user()->id],['friend_id', $request->friend_id]])
              ->orWhere([['user_id', $request->friend_id], ['friend_id', \Auth::user()->id]])->exists();
        //check the record it is added before
        // mean if he send request or i send request => it is one row
        if (!$isHere) {
            $newFriend = new Friend;
            $notify = Notification::find($request->notiy_id);
            $notify->seen = 1;
            $notify->save();
            $newFriend->friend_id = $request->friend_id;
            $newFriend->user_id = \Auth::user()->id;
            $newFriend->isPending = 0;
            $result = $newFriend->save();
            $data = [
                'to' => $request->friend_id,
                'content' => \Auth::user()->name . '  accpet your request friend',
                'senderInfo' => new UserJson(\Auth::user()),
            ];
            broadcast(new NotificationRequest($data))->toOthers();

            return $this->ResponseJson($result, 'add friend ');
        }else return response()->json(['success'=>'you are already friend with him']);
    }
     /*
    public function store(FriendRequest $request)
    {
        //
        $request->validated();
        $newFriend = new Friend;
        $newFriend->user_id = \Auth::user()->id;
        $newFriend->friend_id = $request->friend_id;
        $result  = $newFriend->save();
        return $this->ResponseJson($result, 'add friend ');
    }
    */
    public function update(Request $request , $id){
        // update request friend
        $newFriend = Friend::find($id);
        $newFriend->isPending = 0;
        $result = $newFriend->save();

        if ($result) {
           
        }
        return $this->ResponseJson($result, 'accept friend ');
    }

    public function seenNotification(Request $request,$id){
        // update notificaiton to seen
        $notify = Notification::find($id);
        $notify->seen = 1;
        $result = $notify->save();

        return $this->ResponseJson($result, 'update Notification ');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function destroy($friend)
    {
        // remove friend
        $item = Friend::Where([['friend_id',$friend],['user_id',\Auth::user()->id]])->first();
        $result = Friend::destroy($item->id);
        return $this->ResponseJson($result,'remove friend ');
    }

    public function blockUser(Request $request){
        // block user
        $user = Friend::where('friend_id',$request->friend_id)->first();
            $user->isBlock = $request->block_type;
             $result = $user->save();
             if($user->isBlock == 1)
        return $this->ResponseJson($result,'block user ');

        return $this->ResponseJson($result, 'unblock user '); 

    }
    public function unBlockUser(Request $request){
        // remove block from user
        $user = Friend::find($request->friend_id);
        $result = $user->save();
        return $this->ResponseJson($result, 'block user ');
    }
 
}
