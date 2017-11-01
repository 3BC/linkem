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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function show(Request $request, $id)
    {
      $user = $request->user();
      $link = $user->links()->where('id', $id)->first();

      if(count($link) > 0){
        return $link;
      }else{
        return response('Link Not Found', 404);
      }
=======
    public function show($id)
    {
        //
>>>>>>> Get all links tests completed and passing.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
