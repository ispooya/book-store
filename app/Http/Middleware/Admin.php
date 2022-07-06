<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()) {
            // find user from database
            $user = User::find($request->user()->id);

            // invalid request user
            if (!$user) return response('', 401);

            if ($user->is_admin) {
                // user is admin
                return $next($request);
            } else {
                // user is not admin
                return response('', 403);
            }
        } else {
            // guest user
            return response('', 401);
        }
    }
}
