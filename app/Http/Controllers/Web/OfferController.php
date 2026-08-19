<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SyndicateOffer;
use App\Models\SyndicateOthersAdvertisement;

class OfferController extends Controller
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
     * Matches the old site's offers.php exactly: every published offer,
     * newest first, no sidebar on this page. The old query's date-range
     * filter (only currently-active offers) is commented out there, so
     * every published offer shows regardless of expiry - matched here too.
     *
     */
    public function index()
    {
        $offers = SyndicateOffer::where('status', '1')->orderBy('id', 'desc')->get();
        $page_title = 'Offers';

        return view('web.pages.offers', compact('offers', 'page_title'));
    }

    /**
     * Matches the old site's offers-details.php: a published offer by id,
     * its main image, and a PDF download link if one was uploaded. No
     * gallery here - that part was already dead/commented out in the old
     * code too.
     *
     */
    public function show($id)
    {
        $offer = SyndicateOffer::where('id', $id)->where('status', '1')->first();
        $page_title = 'Offers Details';

        return view('web.pages.offers-details', array_merge([
            'offer' => $offer,
            'page_title' => $page_title,
        ], $this->sidebarData()));
    }
}
