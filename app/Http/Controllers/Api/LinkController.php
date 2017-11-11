<?php

namespace App\Http\Controllers\Api;

use App\Link;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLink;
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
    public function show($id)
    {
        $link = Link::where('id', $id)->first();

        if(count($link) > 0){
          return $link;
        }else{
          return response('Link Not Found', 404);
        }
    }

    /**
     * Store a newly created link in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }
}
