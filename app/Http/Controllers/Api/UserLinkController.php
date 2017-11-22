<?php

namespace App\Http\Controllers\Api;
use App\Link;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLink;
use App\Http\Controllers\Controller;

class UserLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = $request->user();
        $links = $user->links()->get();

        return $links;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLink $request)
    {
        //
        $link = Link::firstOrCreate(
            [
                'url' => $request->url
            ],
            $request->all()
        );

        $link->users()->attach($request->user()->id);

        return $link;
    }


    public function show(Request $request, $id)
    {
      $user = $request->user();
      $link = Link::where('id', $id)->firstOrFail();

      return $link;

    }

}
