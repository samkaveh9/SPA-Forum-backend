<?php

namespace App\Http\Repositories;

use App\Thread;
use Illuminate\Http\Request;

class ThreadRepository
{

    public function getAllAvailableThreads()
    {
        return Thread::whereFlag(1)->latest()->get();
    }

    public function getThreadBySlug($slug)
    {
        return Thread::whereFlag(1)->whereSlug($slug)->first();
    }

    public function store(Request $request)
    {
        Thread::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'channel_id' => $request->input('channel_id'),
            'user_id' => auth()->user()->id
        ]);
    }

    public function update(Request $request, $thread)
    {

        if (!$request->has('best_answer_id')) {
            $thread->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'channel_id' => $request->input('channel_id'),
            ]);
        } else {
            $thread->update([
                'best_answer_id' => $request->input('best_answer_id')
            ]);
        }
    }

    public function destroy(Thread $thread)
    {
       $thread->delete();
    }
}
