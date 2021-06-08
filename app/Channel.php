<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Thread;

class Channel extends Model
{
    protected $fillable = [];

    public function threads(){
        return $this->hasMany(Thread::class);
    }
}
