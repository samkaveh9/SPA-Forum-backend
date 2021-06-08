<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Channel;
use App\Answer;
use Cviebrock\EloquentSluggable\Sluggable;

class Thread extends Model
{
    use Sluggable;

    protected $fillable = [
    'title', 
    'slug', 
    'content', 
    'user_id', 
    'channel_id', 
    'best_answer_id', 
    'flag'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
