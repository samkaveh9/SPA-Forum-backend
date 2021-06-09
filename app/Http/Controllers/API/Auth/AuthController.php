<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required']
        ]);

        $user = resolve(UserRepository::class)->create($request);
        
        $defaultSuperAdminEmail = config('permission.default_super_admin_email');
        
        $user->email == $defaultSuperAdminEmail ? $user->assignRole('super admin') : $user->assignRole('user');
        
        return response()->json([
            'message' => 'User created successfuly'
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(Auth::user(), Response::HTTP_OK);
        }

        throw ValidationException::withMessages([
            "email" => 'incorrect credentials'
        ]);
    }

    public function user()
    {
        return response()->json(Auth::user(), Response::HTTP_OK);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'logged out successfuly'
        ], Response::HTTP_OK);
    }
}
