<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResources;
use App\Http\Resources\RoomUserResource as JsonRoomUser;
use App\Http\Resources\RoomResources as JsonRoom;
use App\Http\Requests\MessageRequet;
use App\Events\MessageSent;

class MessageController extends Controller
{


    private static $messageController = null;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct(){
         $this->middleware('auth');
     }


    static function instance()
    {
            if (null == self::$messageController) {
                self::$messageController = new MessageController();
            }
            return self::$messageController;
    }
    public function index()
    {
        //
        return MessageResources::collection(Message::all());
    }
    public function myMessage($to){

    }
    public function MessageUser($to,$type){
        $message_type;
        //return all saved messages
        if($type == 'room'){
            $message_type = 'App\Room';
            $data = Message::where([['message_receiver_type', $message_type], ['message_receiver_id', $to]]);
            $result = $data->orderBy('created_at', 'asc')->get();

        }else if($type == 'private'){
            $message_type = $type = 'App\User';
            $data = Message::where([['message_receiver_type', $message_type],  ['sender_id',\Auth::user()->id]]);
            $data2 = Message::where([['message_receiver_type', $message_type], ['sender_id', $to]]);
            $result = $data->union($data2)->orderBy('created_at', 'asc')->get();

        }
        //  $data = \Auth::user()->FriendRequest->union(\Auth::user()->FriendSended);
        return MessageResources::collection($result);
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MessageRequet $request)
    {
        // save message
        $request->validated();
        $message = new Message;
                if($request->type == 'room'){
            $message->message_receiver_type = 'App\Room';
                }else if($request->type == 'private'){
            $message->message_receiver_type = 'App\User';
                }
        $message->message_receiver_id = $request->to;
        $message->sender_id =\Auth::user()->id;
        $message->body = $request->content;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $message->image = $path;
        }
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/file');
            $message->file = $path;
        }

        $data = [
            'type' => $request->type,
            'sender' => $request->sender,
            'to' => $request->to,
            'content' => $request->content,
        ];
        $result = $message->save();
        if($result){
        broadcast(new MessageSent($data));
            return $this->ResponseJson($result, 'message send ');
        }
        
    }

   

 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy($message)
    {
        //
    //    $message = Message::destroy($message);
    }
}
