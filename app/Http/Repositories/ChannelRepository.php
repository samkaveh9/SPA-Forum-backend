<?php

namespace App\Http\Repositories;

use App\Channel;

class ChannelRepository
{
    public function all()
    {
        return Channel::all();
    }

    public function create($request)
    {
        Channel::create([
            'name' => $request
        ]);
    }

    public function update($id, $name)
    {
        Channel::find($id)->update([
            'name' => $name
        ]);
    }

    public function delete($id)
    {
        Channel::destroy($id);
    }

}