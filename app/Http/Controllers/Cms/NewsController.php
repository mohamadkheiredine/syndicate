<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:news-view', ['only' => ['index']]);
        $this->middleware('permission:news-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:news-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:news-delete', ['only' => ['destroy']]);
        $this->middleware('permission:news-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Media up to date Manager',
            'link' => 'news'
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

        $columns = ['id', 'title', 'main_image', 'admin_id', 'status', 'publish_status'];

        $searchableColumns = ['title'];

        // Newest first by default (no created_at column on this table, so id
        // is the reliable stand-in) - a user can still click a sortable
        // column header to change it.
        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(SyndicateNews::class, $columns, $searchableColumns);

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
            'what_type' => 'required|string|max:255',
            'main_image' => 'required|image|max:2048',
            'short_description' => 'required|string',
            'description' => 'required|string',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, not automatic. Anyone else's article always
        // stays pending until someone with news-publish approves it later from
        // the list.
        $canPublish = Auth::guard('admin')->user()->can('news-publish');
        $published = $canPublish && $request->boolean('publish_status');

        SyndicateNews::create([
            'admin_id' => Auth::guard('admin')->user()->id,
            'title' => $request->title,
            'what_type' => $request->what_type,
            'main_image' => FilesHelper::storeFile('news', $request->file('main_image')),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? 1 : 0,
            'post_date' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('News successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateNews::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SyndicateNews::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'what_type' => 'required|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            'short_description' => 'required|string',
            'description' => 'required|string',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, same as create. Anyone else's edit always
        // goes back to pending, even if the article was already live, until
        // someone with news-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('news-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if($request->hasFile('main_image')){
            if($image){
                FilesHelper::deleteFileByName('news', $image);
            }
            $image = FilesHelper::storeFile('news', $request->file('main_image'));
        }

        $row->update([
            'title' => $request->title,
            'what_type' => $request->what_type,
            'main_image' => $image,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? 1 : 0,
        ]);

        return redirect()->back()->withStatus('News successfully updated.');
    }

    /**
     * Flip the published/pending state of a news article - only reachable by
     * accounts with the news-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = SyndicateNews::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? 0 : 1,
        ]);

        return redirect()->back()->withStatus('News ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = SyndicateNews::findOrFail($id);

        if($row->getAttributes()['main_image']){
            FilesHelper::deleteFileByName('news', $row->getAttributes()['main_image']);
        }

        $row->delete();

        return redirect()->back()->withStatus('News successfully deleted.');
    }

}
