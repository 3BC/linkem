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
     * Store a newly created link in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLink $request)
    {
        /*
        // USER IS IS USED FOR TESTING ONLY.
        // ONCE WE IMPLMENT OAUTH THE USER ID VAR WILL NOT LONGER BE NEEDED
        // MAKE SURE TO REWRITE THE USER ID ALLOCATION WHEN OAUTH IS IMPLEMENTED
        */
        $user_id = '1';

        // Getting the request data
        $link_request = $request->all();

        // Performing a simple check on the url to make sure it is valid
        // Is this already being done in middleware?
        if(!filter_var($link_request['url'], FILTER_VALIDATE_URL)){
          return new Response('Invalid URL', 422);
        }

        //Perform link check. See if it already exists.
        // If the link already exists all we need to do is relate it to the user
        $link = Link::where('url', $request['url'])->first();

        if(count($link) == 0){

          // Add the link to the DB
          $created_link = Link::create($link_request);
          $created_link->users()->attach($user_id);
          return $created_link;
        }

        // Relate the link to the user.
        $link->users()->attach($user_id);
        return $link;
    }
}
