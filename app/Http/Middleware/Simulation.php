<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Simulation
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
        if(session()->has('simulation')){
            $user = Auth::onceUsingID(session('simulation'));

            Auth::guard('user')->login($user);
        }

        return $next($request);
    }
}
