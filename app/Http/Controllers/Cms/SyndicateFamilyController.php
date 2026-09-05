<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyndicateFamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:syndicate_family-view', ['only' => ['index']]);
        $this->middleware('permission:syndicate_family-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:syndicate_family-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:syndicate_family-delete', ['only' => ['destroy']]);
        $this->middleware('permission:syndicate_family-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Syndicate Family Manager',
            'link' => 'syndicate-family'
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

        $columns = ['id', 'name', 'main_image', 'designation', 'syndicate_year', 'admin_id', 'status', 'publish_status'];

        $searchableColumns = ['name'];

        // Newest first by default (no created_at column on this table, so id
        // is the reliable stand-in) - a user can still click a sortable
        // column header to change it.
        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(SyndicateFamily::class, $columns, $searchableColumns);

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
            'name' => 'required|string|max:255',
            'main_image' => 'required|image|max:2048',
            'designation' => 'required|string|max:255',
            'syndicate_year' => 'required|string|max:255',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, not automatic. Anyone else's addition
        // always stays pending until someone with syndicate_family-publish
        // approves it later from the list.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_family-publish');
        $published = $canPublish && $request->boolean('publish_status');

        SyndicateFamily::create([
            'admin_id' => Auth::guard('admin')->user()->id,
            'name' => $request->name,
            'main_image' => FilesHelper::storeFile('syndicate_family', $request->file('main_image')),
            'designation' => $request->designation,
            'syndicate_year' => $request->syndicate_year,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Syndicate family member successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateFamily::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SyndicateFamily::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            'designation' => 'required|string|max:255',
            'syndicate_year' => 'required|string|max:255',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, same as create. Anyone else's edit always
        // goes back to pending, even if it was already live, until someone
        // with syndicate_family-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_family-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if($request->hasFile('main_image')){
            if($image){
                FilesHelper::deleteFileByName('syndicate_family', $image);
            }
            $image = FilesHelper::storeFile('syndicate_family', $request->file('main_image'));
        }

        $row->update([
            'name' => $request->name,
            'main_image' => $image,
            'designation' => $request->designation,
            'syndicate_year' => $request->syndicate_year,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Syndicate family member successfully updated.');
    }

    /**
     * Flip the published/pending state of a syndicate family member - only
     * reachable by accounts with the syndicate_family-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = SyndicateFamily::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? '0' : '1',
        ]);

        return redirect()->back()->withStatus('Syndicate family member ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = SyndicateFamily::findOrFail($id);

        if($row->getAttributes()['main_image']){
            FilesHelper::deleteFileByName('syndicate_family', $row->getAttributes()['main_image']);
        }

        $row->delete();

        return redirect()->back()->withStatus('Syndicate family member successfully deleted.');
    }

}
