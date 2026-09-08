<?php

namespace App\Http\Controllers\Cms\Base;

use App\Helpers\FilesHelper;
use App\Helpers\OneSignalHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateUser;
use App\Models\UsersPush;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Ports old's real, LIVE admin push-notification sender exactly:
 * Admin\PushNotificationNewController (push()/send_push()/send_single_push()).
 *
 * Old also has an Admin\PushNotificationController (index/bulk_push/single_push,
 * subject/message fields, PushInbox tracking) - but its routes are commented
 * out in old's real admin.php, so it's dead code, never reachable. An
 * earlier pass this session was accidentally modeled on that dead
 * controller; this rewrite matches the one old's site actually runs.
 *
 */
class PushNotificationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:push_notifications-create', ['only' => ['index', 'bulk_push', 'single_push']]);
    }

    public function page_info()
    {
        return [
            'title' => 'Push Notifications',
            'link' => 'push-notifications',
        ];
    }


    public function index()
    {
        $page_info = $this->page_info();

        $users = SyndicateUser::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email']);

        // Same 3 choices old's admin push screen offers - these are
        // OneSignal's own built-in segments, not something computed from
        // our DB, so no query needed to build this list.
        $segment_options = ['All', 'Active Users', 'Inactive Users'];

        return view('cms.base.' . $page_info['link'] . '.index', compact('page_info', 'users', 'segment_options'));
    }


    public function bulk_push(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
        ]);

        $info = [];
        $data = ['type' => 'bulk'];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $filename = FilesHelper::storeFile($page_info['link'], $request->file('image'));
            $image_url = FilesHelper::getImageFullUrl($page_info['link'] . '/' . $filename);

            $info['big_picture'] = $image_url;
            $data['image_url'] = $image_url;
        }

        // Ports old's real send_push(): a chosen segment (All/Active
        // Users/Inactive Users) goes straight to OneSignal as-is. Old's
        // only fallback for "no segment chosen" queried the wrong table
        // (user_push instead of users_push) and would error out - the
        // form here always submits a segment (defaults to "All"), so
        // that branch should never be hit, but if it somehow is, this
        // targets every registered device via the correct table instead
        // of reproducing old's bug.
        if ($request->filled('segments')) {
            $info['player_ids'] = [];
            $info['included_segments'] = [$request->segments];
        } else {
            $info['player_ids'] = UsersPush::pluck('player_id')->filter()->values()->all();
            $info['included_segments'] = [];

            if (empty($info['player_ids'])) {
                return redirect()->back()->withWarning('No Users Found');
            }
        }

        $info['contents'] = ['en' => trim($request->message)];
        $info['headings'] = ['en' => trim($request->title)];
        $info['data'] = $data;

        $result = OneSignalHelper::oneSignal($info);

        return $this->respondFromOneSignal($result, $page_info);
    }

    public function single_push(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'users' => 'required|array',
            'title' => 'required',
            'message' => 'required',
        ]);

        $info = [];
        $data = ['type' => 'bulk'];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $filename = FilesHelper::storeFile($page_info['link'], $request->file('image'));
            $image_url = FilesHelper::getImageFullUrl($page_info['link'] . '/' . $filename);

            $info['big_picture'] = $image_url;
            $data['image_url'] = $image_url;
        }

        $player_ids = UsersPush::whereIn('user_id', $request->users)
            ->pluck('player_id')
            ->values()
            ->all();

        if (empty($player_ids)) {
            return redirect()->back()->withWarning('No Users Found');
        }

        $info['contents'] = ['en' => trim($request->message)];
        $info['headings'] = ['en' => trim($request->title)];
        $info['data'] = $data;
        $info['player_ids'] = $player_ids;

        $result = OneSignalHelper::oneSignal($info);

        return $this->respondFromOneSignal($result, $page_info);
    }


    private function respondFromOneSignal($result, $page_info)
    {
        if (!empty($result['error'])) {
            return redirect()->back()->withError($result['error']);
        }

        $debugger = $result['debugger'];

        if ($result['status'] == 'OK') {
            return redirect()->back()->withSuccess($debugger);
        }

        if ($result['status'] == 'OK_WITH_ERRORS') {
            return redirect()->back()->withWarning($debugger);
        }

        return redirect()->back()->withError($debugger);
    }
}
