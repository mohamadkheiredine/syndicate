<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\JoinConfirmation;
use App\Models\SyndicateAbout;
use App\Models\SyndicateFamily;
use App\Models\SyndicateJoin;
use App\Models\SyndicateOthersAdvertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AboutController extends Controller
{
    /**
     * The two ad slots shown in the sidebar on every page that uses it -
     * matches the old site's right-panel.php (positions 1 = Right Up,
     * 2 = Right Down).
     *
     */
    private function sidebarData()
    {
        return [
            'right_up_ads' => SyndicateOthersAdvertisement::where('position', 1)->where('status', '1')->get(),
            'right_down_ads' => SyndicateOthersAdvertisement::where('position', 2)->where('status', '1')->get(),
        ];
    }

    public function show()
    {
        $about = SyndicateAbout::find(1);
        $page_title = 'About Us';

        return view('web.pages.aboutus', array_merge(['about' => $about, 'page_title' => $page_title], $this->sidebarData()));
    }

    public function previousMembers($year)
    {
        $page_title = 'Members Previous Years';

        // Matches the old site's previous-members.php exactly: plain
        // main_image (no publish_main_image fallback), year must match
        // exactly. The old query's "AND status" (no comparison) is a no-op
        // against this column - confirmed against the live site, which
        // shows members regardless of published state on this page - so no
        // status filter is applied here either.
        $members = SyndicateFamily::where('syndicate_year', $year)->get();

        return view('web.pages.previous-members', array_merge([
            'prev_year' => $year,
            'members' => $members,
            'page_title' => $page_title,
        ], $this->sidebarData()));
    }

    /**
     * Handle the sidebar "Join syndicate's List!" form. Saves straight to
     * our own syndicate_join table (continuing its real, pre-existing
     * history) instead of the old site's dead 2014 MailChimp integration,
     * then emails the person who just signed up a confirmation - matching
     * what the old flow was trying to do, just self-hosted.
     *
     */
    public function join(Request $request)
    {
        $request->validate([
            'syndicate_name' => 'required|string|max:255',
            'syndicate_email' => 'required|email|max:255',
        ]);

        $alreadyJoined = SyndicateJoin::where('syndicate_email', $request->syndicate_email)->exists();

        if($alreadyJoined){
            return redirect()->back()->with('join_status', 'You are already joined with entered email.')->with('join_status_type', 'error');
        }

        SyndicateJoin::create([
            'syndicate_name' => $request->syndicate_name,
            'syndicate_email' => $request->syndicate_email,
        ]);

        // A missing/invalid mail configuration should never break the join
        // itself - the signup above already succeeded regardless.
        try {
            Mail::to($request->syndicate_email)->send(new JoinConfirmation($request->syndicate_name));
        } catch (\Throwable $e) {
            Log::warning('Join confirmation email failed to send: ' . $e->getMessage());
        }

        return redirect()->back()->with('join_status', 'You have been successfully joined.')->with('join_status_type', 'success');
    }
}
