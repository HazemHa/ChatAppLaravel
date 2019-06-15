<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;
use App\Http\Resources\RoomResources;
class RoomController extends Controller
{

    function __construct()
    {
       // Middleware only applied to these methods
        $this->middleware('auth', ['only' => [
            'store', 'update', 'destroy'
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return RoomResources::collection(Room::all());
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add new room
        $request->validated();
        $room = new Room;
        $room->name = $request->name;
        $room->number = $request->number;
        $room->description = $request->description;
        $result  = $room->save();
        return $this->ResponseJson($result, 'add room ');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        // update room info
        $request->validated();
        $room = Room::find($room);
        $room->name = $request->name;
        $room->number = $request->number;
        $room->description = $request->description;
        $result = $room->save();
        return $this->ResponseJson($result, 'update room ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy($room)
    {
        // delete room
        $result = Room::destroy($room);
        return $this->ResponseJson($result, 'update room ');
    }
}
