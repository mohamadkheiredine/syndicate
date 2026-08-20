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
        } elseif($guards[0] == 'web_user'){
            if(!Auth::guard('web_user')->check()) return redirect()->route('web.home');
        } elseif($guards[0] == 'sanctum'){
            if(!Auth::guard('sanctum')->check()){
                return response()->json([
                    'error' => [
                        'debugger' => 'Unauthenticated',
                        'code' => 401,
                        'message' => 'messages.authentication_missing',
                    ],
                ])->setStatusCode(400);
            }
        }

        return $next($request);
    }
}
