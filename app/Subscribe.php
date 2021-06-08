<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Thread;

class Subscribe extends Model
{
    public function user(){
        return $this->belongsTo(User::class);   
    }

    public function thread(){
        return $this->belongsTo(Thread::class);
    }
}
