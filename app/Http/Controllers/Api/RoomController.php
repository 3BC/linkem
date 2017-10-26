<?php

namespace App\Http\Controllers\Api;

use App\Room;
use Illuminate\Http\Request;
use App\Http\Requests\EditRoom;
use App\Http\Requests\StoreRoom;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoom $request)
    {
        $room = new Room;
        $room->name = $request->name;
        $room->description = $request->description;
        $room->private = $request->has('private');
        $room->owner()->associate($request->user());
        $room->save();

        $room->moderators()->attach($request->user()->id);
        $room->followers()->attach($request->user()->id);

        return $room;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(EditRoom $request, Room $room)
    {
        $room->fill($request->all());
        $room->save();

        return $room;
    }
}
