<?php

namespace App\Http\Controllers\Api;

use App\Link;
use App\Group;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLink;
use App\Http\Requests\EditLink;
use App\Http\Controllers\Controller;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $links = $user->groups()->with('links')->get();
        return $links;
    }

    public function fullIndex()
    {
        return Link::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
      $user = $request->user();
      $link = Link::where('id', $id)->firstOrFail();
      return $link;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLink $request)
    {

      $group = Group::findOrFail($request->group_id);

      $group_contributors = $group->contributors()->get();

      foreach($group_contributors as $contributor)
      {
        if($contributor->id == $request->user()->id)
        {
          $link = Link::create([
            'url' => $request->url,
            'name' => $request->name,
            'description' => $request->description,
            'group_id' => $request->group_id,
            'user_id' => $request->user()->id
          ]);

          return $link;

        }
      }

      return response('User is not a contributor of this group.', 403);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditLink $request, $id)
    {
      $link = Link::where('id', $id)->firstOrFail();
      $group = Group::findOrFail($link->group_id);
      $group_moderators = $group->moderators()->get();

      foreach($group_moderators as $moderator)
      {
        if($moderator->id == $request->user()->id)
        {
          $link->url = $request->url;
          $link->name = $request->name;
          $link->description = $request->description;

          $link->save();
          return $link;
        }
      }

      return response('User is not a moderator of this group.', 403);
    }

    public function destroy(Request $request, $id)
    {
      $link = Link::findOrFail($id);
      $group = Group::findOrFail($link->group_id);
      $group_moderators = $group->moderators()->get();

      foreach($group_moderators as $moderator)
      {
        if($moderator->id == $request->user()->id)
        {
          $link->delete();
          return;
        }
      }

      return response('User is not a moderator of this group.', 403);
    }
}
