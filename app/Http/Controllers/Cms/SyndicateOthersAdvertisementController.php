<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateOthersAdvertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyndicateOthersAdvertisementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:syndicate_others_advertisement-view', ['only' => ['index']]);
        $this->middleware('permission:syndicate_others_advertisement-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:syndicate_others_advertisement-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:syndicate_others_advertisement-delete', ['only' => ['destroy']]);
        $this->middleware('permission:syndicate_others_advertisement-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Others Advertisement Manager',
            'link' => 'syndicate-others-advertisement'
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

        $columns = ['id', 'main_image', 'position', 'admin_id', 'status', 'publish_status'];

        // No searchable text columns on this table (just an image + a
        // location dropdown), so the search box is left with nothing to
        // match against - consistent with how other image-only listings
        // handle this.
        $searchableColumns = [];

        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(SyndicateOthersAdvertisement::class, $columns, $searchableColumns);

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
            'main_image' => 'required|image|max:512',
            'position' => 'required|integer|in:1,2,3',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, not automatic. Anyone else's
        // addition always stays pending until someone with
        // syndicate_others_advertisement-publish approves it later from the list.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_others_advertisement-publish');
        $published = $canPublish && $request->boolean('publish_status');

        SyndicateOthersAdvertisement::create([
            'admin_id' => Auth::guard('admin')->user()->id,
            'main_image' => FilesHelper::storeFile('others_advertisement', $request->file('main_image')),
            'position' => $request->position,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Advertisement successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateOthersAdvertisement::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SyndicateOthersAdvertisement::findOrFail($id);

        $this->validate($request, [
            'main_image' => 'nullable|image|max:512',
            'position' => 'required|integer|in:1,2,3',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, same as create. Anyone else's edit
        // always goes back to pending, even if it was already live, until
        // someone with syndicate_others_advertisement-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_others_advertisement-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if ($request->hasFile('main_image')) {
            if ($image) {
                FilesHelper::deleteFileByName('others_advertisement', $image);
            }
            $image = FilesHelper::storeFile('others_advertisement', $request->file('main_image'));
        }

        $row->update([
            'main_image' => $image,
            'position' => $request->position,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Advertisement successfully updated.');
    }

    /**
     * Flip the published/pending state of an advertisement - only
     * reachable by accounts with the syndicate_others_advertisement-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = SyndicateOthersAdvertisement::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? '0' : '1',
        ]);

        return redirect()->back()->withStatus('Advertisement ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = SyndicateOthersAdvertisement::findOrFail($id);

        if ($row->getAttributes()['main_image']) {
            FilesHelper::deleteFileByName('others_advertisement', $row->getAttributes()['main_image']);
        }

        $row->delete();

        return redirect()->back()->withStatus('Advertisement successfully deleted.');
    }

}
