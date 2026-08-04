<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;

use Closure;
use Illuminate\Support\Facades\Auth;


class CheckUserApp extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();

            if($user->blocked){
                $user->tokens()->delete();
                // 403 Forbidden
                return parent::return_error('User Blocked', 403, 'Your account has been suspended please contact the support team!');
            }
        } else {
            // 401 Unauthurized
            return parent::return_error('Expired Access Token', 401, 'Authentication invalid');
        }

        return $next($request);
    }
}
