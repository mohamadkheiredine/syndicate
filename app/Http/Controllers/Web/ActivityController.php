<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SyndicateActivity;
use App\Models\SyndicateOthersAdvertisement;

class ActivityController extends Controller
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
     * Matches the old site's activities.php exactly: every published
     * activity, newest first, no sidebar on this page.
     *
     */
    public function index()
    {
        $activities = SyndicateActivity::where('status', '1')->orderBy('id', 'desc')->get();
        $page_title = 'Activities';

        return view('web.pages.activities', compact('activities', 'page_title'));
    }

    /**
     * Matches the old site's activities-details.php: a published activity
     * by id, its gallery photos, and the linked document if any.
     *
     */
    public function show($id)
    {
        $activity = SyndicateActivity::where('id', $id)->where('status', '1')->first();
        $page_title = 'Activities Details';

        return view('web.pages.activities-details', array_merge([
            'activity' => $activity,
            'page_title' => $page_title,
        ], $this->sidebarData()));
    }
}
