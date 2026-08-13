<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:banners-view', ['only' => ['index']]);
        $this->middleware('permission:banners-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:banners-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:banners-delete', ['only' => ['destroy']]);
        $this->middleware('permission:banners-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Banner Manager',
            'link' => 'banners'
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

        $columns = ['id', 'status', 'main_image', 'publish_status'];

        // Newest first by default (no created_at column on this table, so id
        // is the reliable stand-in) - a user can still click a sortable
        // column header to change it.
        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(SyndicateBanner::class, $columns, []);

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
            'main_image' => 'required|image|max:2048',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, not automatic. Anyone else's upload always
        // stays pending until someone with banners-publish approves it later
        // from the list.
        $canPublish = Auth::guard('admin')->user()->can('banners-publish');
        $published = $canPublish && $request->boolean('publish_status');

        SyndicateBanner::create([
            'main_image' => FilesHelper::storeFile('banners', $request->file('main_image')),
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? 1 : 0,
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Banner successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateBanner::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SyndicateBanner::findOrFail($id);

        $this->validate($request, [
            'main_image' => 'nullable|image|max:2048',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, same as create. Anyone else's edit always
        // goes back to pending, even if the banner was already live, until
        // someone with banners-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('banners-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if($request->hasFile('main_image')){
            if($image){
                FilesHelper::deleteFileByName('banners', $image);
            }
            $image = FilesHelper::storeFile('banners', $request->file('main_image'));
        }

        $row->update([
            'main_image' => $image,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? 1 : 0,
        ]);

        return redirect()->back()->withStatus('Banner successfully updated.');
    }

    /**
     * Flip the published/pending state of a banner - only reachable by
     * accounts with the banners-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = SyndicateBanner::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? 0 : 1,
        ]);

        return redirect()->back()->withStatus('Banner ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = SyndicateBanner::findOrFail($id);

        if($row->getAttributes()['main_image']){
            FilesHelper::deleteFileByName('banners', $row->getAttributes()['main_image']);
        }

        $row->delete();

        return redirect()->back()->withStatus('Banner successfully deleted.');
    }

}
