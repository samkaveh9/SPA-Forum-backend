<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Thread;
use Cviebrock\EloquentSluggable\Sluggable;

class Channel extends Model
{
    protected $fillable = ['name','slug'];

    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function threads(){
        return $this->hasMany(Thread::class);
    }
}
