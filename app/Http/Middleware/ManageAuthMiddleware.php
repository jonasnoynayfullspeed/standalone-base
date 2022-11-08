<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManageAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = 'admin')
    {
        $authService = new AuthService;
        if ($authService->checkAuth($request, $guard)) {
            return $next($request);
        }

        if ($request->route()->getPrefix() != 'api') {
            return redirect('/signIn')->withErrors(['error' => 'Unauthenticated!']);
        } else {
            return response(['error' => 'Unauthorized!'], 401);
        }
    }
}
