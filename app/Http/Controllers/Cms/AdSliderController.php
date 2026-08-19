<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\AdSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdSliderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ad_sliders-view', ['only' => ['index']]);
        $this->middleware('permission:ad_sliders-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ad_sliders-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:ad_sliders-delete', ['only' => ['destroy']]);
        $this->middleware('permission:ad_sliders-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Ad Sliders',
            'link' => 'ad-sliders'
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

        $columns = ['id', 'main_image', 'text', 'status', 'publish_status'];

        $searchableColumns = ['text'];

        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(AdSlider::class, $columns, $searchableColumns);

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
            'text' => 'required|string|max:255',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, not automatic. Anyone else's
        // addition always stays pending until someone with
        // ad_sliders-publish approves it later from the list.
        $canPublish = Auth::guard('admin')->user()->can('ad_sliders-publish');
        $published = $canPublish && $request->boolean('publish_status');

        AdSlider::create([
            'main_image' => FilesHelper::storeFile('ad-slider', $request->file('main_image')),
            'text' => $request->text,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Ad slider successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = AdSlider::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = AdSlider::findOrFail($id);

        $this->validate($request, [
            'main_image' => 'nullable|image|max:2048',
            'text' => 'required|string|max:255',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, same as create. Anyone else's edit
        // always goes back to pending, even if it was already live, until
        // someone with ad_sliders-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('ad_sliders-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if ($request->hasFile('main_image')) {
            if ($image) {
                FilesHelper::deleteFileByName('ad-slider', $image);
            }
            $image = FilesHelper::storeFile('ad-slider', $request->file('main_image'));
        }

        $row->update([
            'main_image' => $image,
            'text' => $request->text,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Ad slider successfully updated.');
    }

    /**
     * Flip the published/pending state of an ad slider - only reachable by
     * accounts with the ad_sliders-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = AdSlider::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? '0' : '1',
        ]);

        return redirect()->back()->withStatus('Ad slider ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = AdSlider::findOrFail($id);

        if ($row->getAttributes()['main_image']) {
            FilesHelper::deleteFileByName('ad-slider', $row->getAttributes()['main_image']);
        }

        $row->delete();

        return redirect()->back()->withStatus('Ad slider successfully deleted.');
    }

}
