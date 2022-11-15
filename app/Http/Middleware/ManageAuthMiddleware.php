<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Illuminate\Http\Request;

class ManageAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, \Closure $next, $guard = 'admin')
    {
        $authService = new AuthService();
        if ($authService->checkAuth($request, $guard)) {
            return $next($request);
        }

        if ('api' != $request->route()->getPrefix()) {
            return redirect('/signIn')->withErrors(['error' => 'Unauthenticated!']);
        } else {
            return response(['error' => 'Unauthorized!'], 401);
        }
    }
}
