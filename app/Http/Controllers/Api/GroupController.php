<?php

namespace App\Http\Controllers\Api;

use App\Group;
use Illuminate\Http\Request;
use App\Http\Requests\EditRoom;
use App\Http\Requests\StoreRoom;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Group::where('owner_id', $request->user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoom $request)
    {
        $room = new Group;
        $room->name = $request->name;
        $room->description = $request->description;
        $room->save();

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
