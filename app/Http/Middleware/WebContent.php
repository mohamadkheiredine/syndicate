<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;
use App\Models\SyndicateFamily;
use App\Models\SyndicateLogo;
use App\Models\SyndicateSetting;
use Closure;
use Illuminate\Support\Facades\Auth;
use View;

class WebContent extends Controller
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
        $web_logo = SyndicateLogo::find(1);
        $web_settings = SyndicateSetting::find(1);

        // Past syndicate years, for the "Members Previous Years" nav dropdown
        $web_family_years = SyndicateFamily::where('syndicate_year', '<', date('Y'))
            ->groupBy('syndicate_year')
            ->pluck('syndicate_year');


        $is_logged_in = Auth::guard('web_user')->check();

        // Share variables
        View::share(compact('web_logo', 'web_settings', 'web_family_years', 'is_logged_in'));

        return $next($request);
    }
}
