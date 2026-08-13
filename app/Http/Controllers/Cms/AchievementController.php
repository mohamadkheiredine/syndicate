<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:achievements-view', ['only' => ['index']]);
        $this->middleware('permission:achievements-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:achievements-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:achievements-delete', ['only' => ['destroy']]);
        $this->middleware('permission:achievements-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Achievements',
            'link' => 'achievements'
        ];
        return $page_info;
    }

    /**
     * Display a listing of the Table
     *
     */
    public function index()
    {
        $page_info = $this->page_info();

        $columns = ['id', 'title', 'number', 'status', 'publish_status'];

        $searchableColumns = ['title'];

        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(Achievement::class, $columns, $searchableColumns);

        return view('cms.pages.' . $page_info['link'] . '.index', compact('page_info', 'rows'));
    }

    /**
     * Show the form for creating a new row
     *
     */
    public function create()
    {
        $page_info = $this->page_info();

        return view('cms.pages.' . $page_info['link'] . '.create', compact('page_info'));
    }

    /**
     * Store a newly created row in the database
     *
     */
    public function store(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'number' => 'required|integer',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, not automatic. Anyone else's
        // addition always stays pending until someone with
        // achievements-publish approves it later from the list. Note: this
        // table has no admin_id column, same as Home Sliders.
        $canPublish = Auth::guard('admin')->user()->can('achievements-publish');
        $published = $canPublish && $request->boolean('publish_status');

        Achievement::create([
            'title' => $request->title,
            'number' => $request->number,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Achievement successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = Achievement::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = Achievement::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'number' => 'required|integer',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, same as create. Anyone else's edit
        // always goes back to pending, even if it was already live, until
        // someone with achievements-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('achievements-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $row->update([
            'title' => $request->title,
            'number' => $request->number,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Achievement successfully updated.');
    }

    /**
     * Flip the published/pending state of an achievement - only reachable
     * by accounts with the achievements-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = Achievement::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? '0' : '1',
        ]);

        return redirect()->back()->withStatus('Achievement ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        Achievement::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Achievement successfully deleted.');
    }

}
