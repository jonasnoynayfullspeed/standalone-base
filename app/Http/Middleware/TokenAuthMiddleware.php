<?php

namespace App\Http\Middleware;

use App\Services\FirebaseService;
use Illuminate\Http\Request;

class TokenAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, \Closure $next)
    {
        $firebaseService = new FirebaseService();
        if ($firebaseService->verifyIdToken($request->input('apiToken'))) {
            return $next($request);
        }

        return response(['error' => 'Unauthorized!'], 401);
    }
}
