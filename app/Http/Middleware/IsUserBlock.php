<?php

namespace App\Http\Middleware;

use App\Http\Repositories\UserRepository;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class IsUserBlock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!resolve(UserRepository::class)->isBlock()) {
            return $next($request);
        }
        return response()->json([
            'message' => 'you are blocked'
        ], Response::HTTP_FORBIDDEN);
    }
}
