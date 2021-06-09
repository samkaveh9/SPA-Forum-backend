<?php

namespace App\Http\Controllers\API\Thread;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Http\Repositories\AnswerRepository;
use App\Http\Repositories\SubscribeRepository;
use App\Http\Repositories\UserRepository;
use App\Notifications\NewReplaySubmitted;
use App\Subscribe;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends Controller
{

    public function __construct()
    {
        $this->middleware('isUserBlock')->except([
            'index'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = resolve(AnswerRepository::class)->getAllAnswers();
        return response()->json($answers, Response::HTTP_OK);
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
            'content' => 'required',
            'thread_id' => 'required'
        ]);

        resolve(AnswerRepository::class)->store($request);

        $notifiable_users_id = resolve(SubscribeRepository::class)->getNotifiableUsers($request->thread_id);
        $notifiable_users = resolve(UserRepository::class)->find($notifiable_users_id); 
        Notification::send($notifiable_users, new NewReplaySubmitted(Thread::find($request->thread_id)));   

       if (Thread::find($request->input('thread_id'))->user_id !== auth()->id()) {
            auth()->user()->increment('score', 10);
       }

        return response()->json([
            'message' => 'answer submitted successfuly'
        ], Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'content' => 'required'
        ]);
        if (Gate::forUser(auth()->user())->allows('user-answer', $answer)) {
            resolve(AnswerRepository::class)->update($request, $answer);

            return response()->json([
                'message' => 'answer updated successfuly'
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
    public function destroy(Answer $answer)
    {
        if (Gate::forUser(auth()->user())->allows('user-answer', $answer)) {
            resolve(AnswerRepository::class)->destroy($answer);
            return response()->json([
                'message' => 'answer deleted successfuly'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'access denied'
        ], Response::HTTP_FORBIDDEN);
    }
}
