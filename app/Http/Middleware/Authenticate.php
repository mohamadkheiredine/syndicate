<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if(!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if($guards[0] == 'admin'){
            $admin = Auth::guard('admin')->user();
            if($guards[0] == 'admin' && !$admin) return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
