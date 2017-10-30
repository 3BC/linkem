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
        $link = Link::firstOrCreate(
            [
                'url' => $request->url
            ],
            $request->all()
        );

        $link->users()->attach($request->user()->id);

        return $link;
    }
}
