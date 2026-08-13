<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\HomeSlider;
use App\Models\SyndicateNews;
use App\Models\SyndicateOffer;
use App\Models\OurTeam;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

	public function index()
	{
		$page_title = 'Home';

		// Also shared globally by the WebContent middleware (same flag the nav uses) -
		// read again here just to decide which extra queries are worth running.
		$is_logged_in = Auth::guard('web_user')->check();

		$home_sliders = HomeSlider::where('status', 1)->get();
		$latest_offer = $is_logged_in ? SyndicateOffer::where('status', 1)->orderByDesc('id')->first() : null;
		$our_team = OurTeam::find(1);
		$latest_news = $is_logged_in ? SyndicateNews::where('status', 1)->orderByDesc('id')->first() : null;
		$achievements = Achievement::where('status', 1)->get();

		return view('web.pages.home', compact(
			'page_title',
			'is_logged_in',
			'home_sliders',
			'latest_offer',
			'our_team',
			'latest_news',
			'achievements'
		));
	}

}
