<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    
    public function sendNotifications()
    {
        return response()->json(auth()->user()->unReadNotifications() , Response::HTTP_OK);
    }

    public function leaderborads()
    {
        return resolve(UserRepository::class)->leaderborads();
    }

}
