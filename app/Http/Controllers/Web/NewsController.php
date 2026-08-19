<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SyndicateNews;
use App\Models\SyndicateOthersAdvertisement;

class NewsController extends Controller
{
    /**
     * The two ad slots shown in the sidebar on every page that uses it -
     * matches the old site's right-panel.php (positions 1 = Right Up,
     * 2 = Right Down). Same as AboutController::sidebarData().
     *
     */
    private function sidebarData()
    {
        return [
            'right_up_ads' => SyndicateOthersAdvertisement::where('position', 1)->where('status', '1')->get(),
            'right_down_ads' => SyndicateOthersAdvertisement::where('position', 2)->where('status', '1')->get(),
        ];
    }

    /**
     * Real news only - the old site's news.php actually merges this with
     * published activities via a broken heuristic that misroutes most
     * clicks to the wrong detail page (confirmed against the real data:
     * only 2 published news rows exist versus 56 published activities, so
     * that merge was really just a broken activities feed with 2 real news
     * items mixed in). Per explicit instruction, this shows real news only,
     * with working links every time. Matches the old page's date sort
     * (newest first) and its 25-per-page pagination.
     *
     */
    public function index()
    {
        $news = SyndicateNews::where('status', '1')->orderBy('post_date', 'desc')->paginate(25);
        $page_title = 'News';

        return view('web.pages.news', array_merge(['news' => $news, 'page_title' => $page_title], $this->sidebarData()));
    }

    /**
     * Matches the old site's news-details.php: a published news item by id.
     *
     */
    public function show($id)
    {
        $newsItem = SyndicateNews::where('id', $id)->where('status', '1')->first();
        $page_title = 'News Details';

        return view('web.pages.news-details', array_merge([
            'newsItem' => $newsItem,
            'page_title' => $page_title,
        ], $this->sidebarData()));
    }
}
