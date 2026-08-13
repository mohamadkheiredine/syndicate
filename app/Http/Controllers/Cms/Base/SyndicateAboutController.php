<?php

namespace App\Http\Controllers\Cms\Base;

use App\Http\Controllers\Controller;
use App\Models\SyndicateAbout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyndicateAboutController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:about_syndicate-edit', ['only' => ['edit', 'update']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'About The Syndicate',
            'link' => 'about-syndicate'
        ];
        return $page_info;
    }

    /**
     * Show the form for editing the row
     *
     */
    public function edit()
    {
        $page_info = $this->page_info();

        $row = SyndicateAbout::findOrFail(1);

        return view('cms.base.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the row in the database
     *
     */
    public function update(Request $request)
    {
        $page_info = $this->page_info();

        $row = SyndicateAbout::findOrFail(1);

        $this->validate($request, [
            'description' => 'required|string',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, not automatic. Anyone else's edit
        // always goes back to pending, even if it was already live, until
        // someone with about_syndicate-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('about_syndicate-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $row->update([
            'description' => $request->description,
            'publish_status' => $published ? 1 : 0,
        ]);

        return redirect()->back()->withStatus('About The Syndicate successfully updated.');
    }

}
