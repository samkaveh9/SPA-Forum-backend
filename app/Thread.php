<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Channel;
use App\Answer;

class Thread extends Model
{
    
    public function channel(){
        return $this->belongsTo(Channel::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }

}
