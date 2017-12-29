<?php

namespace App\Http\Controllers\Api;

use App\Group;
use Illuminate\Http\Request;
use App\Http\Requests\EditGroup;
use App\Http\Requests\StoreGroup;
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
      return $request->user()->groups()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroup $request)
    {
        $group = Group::create([
          'name'=> $request->name,
          'description'=> $request->description,
        ]);

        $group->owners()->attach($request->user()->id);
        $request->user()->groups()->attach($group->id);

        return $group;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(EditGroup $request, Group $group)
    {
        $requested_group = Group::findOrFail($group->id);
        $group_owners = $requested_group->owners()->get();

        foreach ($group_owners as $owner) {
          if($owner->id == $request->user()->id)
          {
            $group->fill($request->all());
            $group->save();
            return $group;

          }
        }

        return response('User is not an owner of this group.', 403);
    }

    public function destroy(Request $request, Group $group)
    {
      $requested_group = Group::findOrFail($group->id);
      $group_owners = $requested_group->owners()->get();

      foreach ($group_owners as $owner) {
        if($owner->id == $request->user()->id)
        {
          $group->delete();
         return;
        }
      }
      return response('User is not an owner of this group.', 403);
    }
}
