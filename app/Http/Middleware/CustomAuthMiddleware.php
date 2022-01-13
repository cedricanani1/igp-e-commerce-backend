<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\UserService as User;

class CustomAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        if(User::get($token)->success == true) {
            return $next($request);
        };

        return response()->json(['error' => 'unauthenticated']);
    }
}
