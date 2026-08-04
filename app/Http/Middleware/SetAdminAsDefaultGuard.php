<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;

use Closure;
use Illuminate\Support\Facades\Auth;


class SetAdminAsDefaultGuard extends Controller
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
        if(Auth::guard('admin')->check()){
            // Set Default Guard for Admin
            Auth::shouldUse('admin');

            $admin = Auth::guard('admin')->user();

            // If the user account is blocked by the admin
            if($admin->blocked) {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'Your account is blocked. Please contact your administrator!');
            }

        }
        return $next($request);
    }
}
