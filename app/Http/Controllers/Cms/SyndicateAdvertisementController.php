<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateAdvertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyndicateAdvertisementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:syndicate_advertisement-view', ['only' => ['index']]);
        $this->middleware('permission:syndicate_advertisement-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:syndicate_advertisement-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:syndicate_advertisement-delete', ['only' => ['destroy']]);
        $this->middleware('permission:syndicate_advertisement-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Get Involved Advertise Manager',
            'link' => 'syndicate-advertisement'
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

        $columns = ['id', 'advertise_type', 'title', 'dimension', 'what_type', 'price', 'duration', 'admin_id', 'status', 'publish_status'];

        $searchableColumns = ['title', 'advertise_type'];

        // Newest first by default (no created_at column on this table, so id
        // is the reliable stand-in) - a user can still click a sortable
        // column header to change it.
        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(SyndicateAdvertisement::class, $columns, $searchableColumns);

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
            'advertise_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'dimension' => 'required|string|max:255',
            'what_type' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, not automatic. Anyone else's addition
        // always stays pending until someone with syndicate_advertisement-publish
        // approves it later from the list.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_advertisement-publish');
        $published = $canPublish && $request->boolean('publish_status');

        SyndicateAdvertisement::create([
            'admin_id' => Auth::guard('admin')->user()->id,
            'advertise_type' => $request->advertise_type,
            'title' => $request->title,
            'dimension' => $request->dimension,
            'what_type' => $request->what_type,
            'price' => $request->price,
            'duration' => $request->duration,
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

        $row = SyndicateAdvertisement::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SyndicateAdvertisement::findOrFail($id);

        $this->validate($request, [
            'advertise_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'dimension' => 'required|string|max:255',
            'what_type' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, same as create. Anyone else's edit always
        // goes back to pending, even if it was already live, until someone
        // with syndicate_advertisement-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_advertisement-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $row->update([
            'advertise_type' => $request->advertise_type,
            'title' => $request->title,
            'dimension' => $request->dimension,
            'what_type' => $request->what_type,
            'price' => $request->price,
            'duration' => $request->duration,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Advertisement successfully updated.');
    }

    /**
     * Flip the published/pending state of an advertisement - only
     * reachable by accounts with the syndicate_advertisement-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = SyndicateAdvertisement::findOrFail($id);

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
        $row = SyndicateAdvertisement::findOrFail($id);

        $row->delete();

        return redirect()->back()->withStatus('Advertisement successfully deleted.');
    }

}
