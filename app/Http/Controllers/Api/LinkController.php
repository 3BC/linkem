<?php

namespace App\Http\Controllers\Api;

use App\Link;
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
    public function index()
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditLink $request, $id)
    {
      $link = Link::where('id', $id)->firstOrFail();
      $data = $request->all();

      if($data['name'] && isset($data['name'])) {$link->name = $data['name']; }
      $link->url = $data['url'];
      $link->description = $data['description'];

      $link->save();

      return $link;
    }
}
