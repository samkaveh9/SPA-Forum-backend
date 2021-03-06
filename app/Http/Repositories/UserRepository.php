<?php

namespace App\Http\Repositories;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{

    public function create(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function leaderboards()
    {
       return User::query()->orderByDesc('score')->paginate(20);
    }

    public function isBlock() :bool
    {
       return (bool) auth()->user()->is_block;
    }

}