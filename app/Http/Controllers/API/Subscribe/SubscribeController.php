<?php

namespace App\Http\Controllers\API\Subscribe;

use App\Http\Controllers\Controller;
use App\Subscribe;
use App\Thread;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{

    public function __construct()
    {
        $this->middleware('isUserBlock');
    }
    
    public function subscribe(Thread $thread)
    {
        auth()->user()->subscribes()->create([
            'thread_id' => $thread->id
        ]);
        
        return response()->json([
            'message' => 'user subscribed successfuly' 
        ], Response::HTTP_OK);
    }

    public function unSubscribe(Thread $thread)
    {
       Subscribe::query()->where([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
       ])->delete();
        
        return response()->json([
            'message' => 'user unsubscribed successfuly' 
        ], Response::HTTP_OK);
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
    public function show($id)
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
