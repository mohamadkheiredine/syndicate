<?php

namespace App\Http\Controllers\Cms\Base;

use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Models\OurTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OurTeamController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:our_team-edit', ['only' => ['edit', 'update']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Our Team',
            'link' => 'our-team'
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

        $row = OurTeam::findOrFail(1);

        return view('cms.base.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the row in the database
     *
     */
    public function update(Request $request)
    {
        $page_info = $this->page_info();

        $row = OurTeam::findOrFail(1);

        $this->validate($request, [
            'main_image' => 'nullable|image|max:2048',
            'short_description' => 'required|string',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, not automatic. Anyone else's edit
        // always goes back to pending, even if it was already live, until
        // someone with our_team-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('our_team-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if($request->hasFile('main_image')){
            if($image){
                FilesHelper::deleteFileByName('our_team', $image);
            }
            $image = FilesHelper::storeFile('our_team', $request->file('main_image'));
        }

        $row->update([
            'main_image' => $image,
            'short_description' => $request->short_description,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Our Team successfully updated.');
    }

}
