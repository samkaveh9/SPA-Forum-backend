<?php

namespace App\Http\Controllers\API\V01\Auth;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required']
        ]);

        resolve(UserRepository::class)->create($request);

        return response()->json([
            'message' => 'User created successfuly'
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(Auth::user(), 200);
        }

        throw ValidationException::withMessages([
            "email" => 'incorrect credentials'
        ]);
    }

    public function user()
    {
        return response()->json(Auth::user(), 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'logged out successfuly'
        ], 200);
    }
}
