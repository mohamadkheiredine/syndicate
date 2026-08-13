<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeSliderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:home_sliders-view', ['only' => ['index']]);
        $this->middleware('permission:home_sliders-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:home_sliders-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:home_sliders-delete', ['only' => ['destroy']]);
        $this->middleware('permission:home_sliders-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Home Sliders',
            'link' => 'home-sliders'
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

        $columns = ['id', 'main_image', 'title', 'subtitle', 'text', 'status', 'publish_status'];

        $searchableColumns = ['title', 'subtitle'];

        request()->mergeIfMissing(['sort' => 'id', 'order' => 'desc']);

        $rows = PaginationHelper::paginateData(HomeSlider::class, $columns, $searchableColumns);

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
            'mobile_image' => 'required|image|max:2048',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'text' => 'nullable|string',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, not automatic. Anyone else's
        // addition always stays pending until someone with
        // home_sliders-publish approves it later from the list. Note: this
        // table has no admin_id column, unlike every other module.
        $canPublish = Auth::guard('admin')->user()->can('home_sliders-publish');
        $published = $canPublish && $request->boolean('publish_status');

        HomeSlider::create([
            'main_image' => FilesHelper::storeFile('home-slider', $request->file('main_image')),
            'mobile_image' => FilesHelper::storeFile('home-slider', $request->file('mobile_image')),
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'text' => $request->text ?? '',
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Home slider successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = HomeSlider::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = HomeSlider::findOrFail($id);

        $this->validate($request, [
            'main_image' => 'nullable|image|max:2048',
            'mobile_image' => 'nullable|image|max:2048',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'text' => 'nullable|string',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, same as create. Anyone else's edit
        // always goes back to pending, even if it was already live, until
        // someone with home_sliders-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('home_sliders-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $mainImage = $row->getAttributes()['main_image'];
        if ($request->hasFile('main_image')) {
            if ($mainImage) {
                FilesHelper::deleteFileByName('home-slider', $mainImage);
            }
            $mainImage = FilesHelper::storeFile('home-slider', $request->file('main_image'));
        }

        $mobileImage = $row->getAttributes()['mobile_image'];
        if ($request->hasFile('mobile_image')) {
            if ($mobileImage) {
                FilesHelper::deleteFileByName('home-slider', $mobileImage);
            }
            $mobileImage = FilesHelper::storeFile('home-slider', $request->file('mobile_image'));
        }

        $row->update([
            'main_image' => $mainImage,
            'mobile_image' => $mobileImage,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'text' => $request->text ?? '',
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Home slider successfully updated.');
    }

    /**
     * Flip the published/pending state of a home slider - only reachable
     * by accounts with the home_sliders-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = HomeSlider::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? '0' : '1',
        ]);

        return redirect()->back()->withStatus('Home slider ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = HomeSlider::findOrFail($id);

        if ($row->getAttributes()['main_image']) {
            FilesHelper::deleteFileByName('home-slider', $row->getAttributes()['main_image']);
        }
        if ($row->getAttributes()['mobile_image']) {
            FilesHelper::deleteFileByName('home-slider', $row->getAttributes()['mobile_image']);
        }

        $row->delete();

        return redirect()->back()->withStatus('Home slider successfully deleted.');
    }

}
