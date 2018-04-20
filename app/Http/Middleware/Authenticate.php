<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function __construct()
    {
        $this->auth = Auth::guard();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->auth->check()) {
            return redirect()->route('login')->withErrors(['message' => 'Unauthenticated.']);
        }
        view()->share('userLoggedIn', $this->auth->user());
        view()->share('currentRoute', $request->route()->getName());
        return $next($request);
    }
}
