<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Thread;

class Answer extends Model
{
    protected $fillable = ['content','thread_id','user_id'];

    public function user(){
        return $this->belongsTo(User::class);   
    }

    public function thread(){
        return $this->belongsTo(Thread::class);
    }
}
