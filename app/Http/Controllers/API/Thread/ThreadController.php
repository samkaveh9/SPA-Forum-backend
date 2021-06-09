<?php

namespace App\Http\Controllers\API\Thread;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ThreadRepository;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = resolve(ThreadRepository::class)->getAllAvailableThreads();
        return response()->json($threads, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'channel_id' => 'required'
        ]);

        resolve(ThreadRepository::class)->store($request);

        return response()->json([
            'message' => 'thread created successfuly'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $thread = resolve(ThreadRepository::class)->getThreadBySlug($slug);
        return response()->json($thread, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        $request->has('best_answer_id')
            ?
            $request->validate([
                'best_answer_id' => 'required',
            ])

            : $request->validate([
                'title' => 'required',
                'content' => 'required',
                'channel_id' => 'required'
            ]);

        if (Gate::forUser(auth()->user())->allows('user-thread',$thread)) {
            resolve(ThreadRepository::class)->update($request, $thread);
            return response()->json([
                'message' => 'thread updated successfuly'
            ], Response::HTTP_OK);
        }
        
        return response()->json([
            'message' => 'access denied'
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        if (Gate::forUser(auth()->user())->allows('user-thread', $thread)) {
            resolve(ThreadRepository::class)->destroy($thread);
            return response()->json([
                'message' => 'thread deleted successfuly'
            ], Response::HTTP_OK);
        }
        
        return response()->json([
            'message' => 'access denied'
        ], Response::HTTP_FORBIDDEN);
    }
}
    