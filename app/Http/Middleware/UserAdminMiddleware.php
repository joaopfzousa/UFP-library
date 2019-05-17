<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class UserAdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $user = $request->auth;
        
        if(!$user) {
            return response()->json([
                'error' => 'User not authenticated.'
            ], 401);
        }

        if(!$user->admin) {
            return response()->json([
                'error' => 'User not authorized.'
            ], 403);
        }

        return $next($request);
    }
}