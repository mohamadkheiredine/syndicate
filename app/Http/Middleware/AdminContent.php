<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use Closure;
use View;

class AdminContent extends Controller
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
        $cms_setting = CmsSetting::findOrFail(1);
        $cms_logo = $cms_setting->logo;
        $cms_primary_color = $cms_setting->primary_color;

        // Share variables
        View::share(compact('cms_logo', 'cms_primary_color'));

        return $next($request);
    }
}
