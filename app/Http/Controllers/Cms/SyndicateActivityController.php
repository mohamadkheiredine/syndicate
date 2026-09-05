<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateActivity;
use App\Models\SyndicateActivityGallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyndicateActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:syndicate_activities-view', ['only' => ['index']]);
        $this->middleware('permission:syndicate_activities-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:syndicate_activities-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:syndicate_activities-delete', ['only' => ['destroy']]);
        $this->middleware('permission:syndicate_activities-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Syndicate Activities Manager',
            'link' => 'syndicate-activities'
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

        $rows = PaginationHelper::paginateData(SyndicateActivity::class, $columns, $searchableColumns);

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
            'place' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            'any_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'short_description' => 'required|string',
            'description' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, not automatic. Anyone else's activity always
        // stays pending until someone with syndicate_activities-publish
        // approves it later from the list.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_activities-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $row = SyndicateActivity::create([
            'admin_id' => Auth::guard('admin')->user()->id,
            'title' => $request->title,
            'post_date' => $this->parseDate($request->post_date) ?? now()->format('Y-m-d'),
            'place' => $request->place ?? '',
            'main_image' => $request->hasFile('main_image') ? FilesHelper::storeFile('activities_main', $request->file('main_image')) : '',
            'any_file' => $request->hasFile('any_file') ? FilesHelper::storeFile('activities_file', $request->file('any_file')) : '',
            'short_description' => $request->short_description,
            'description' => $request->description ?? '',
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        $this->storeGalleryImages($row, $request);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Activity successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateActivity::with('gallery')->findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SyndicateActivity::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            'any_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'short_description' => 'required|string',
            'description' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
        ]);

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, same as create. Anyone else's edit always
        // goes back to pending, even if the activity was already live, until
        // someone with syndicate_activities-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_activities-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if($request->hasFile('main_image')){
            if($image){
                FilesHelper::deleteFileByName('activities_main', $image);
            }
            $image = FilesHelper::storeFile('activities_main', $request->file('main_image'));
        }

        $file = $row->getAttributes()['any_file'];
        if($request->hasFile('any_file')){
            if($file){
                FilesHelper::deleteFileByName('activities_file', $file);
            }
            $file = FilesHelper::storeFile('activities_file', $request->file('any_file'));
        }

        $row->update([
            'title' => $request->title,
            'post_date' => $this->parseDate($request->post_date) ?? $row->getAttributes()['post_date'],
            'place' => $request->place ?? '',
            'main_image' => $image,
            'any_file' => $file,
            'short_description' => $request->short_description,
            'description' => $request->description ?? '',
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        $this->removeGalleryImages($row, $request);
        $this->storeGalleryImages($row, $request);

        return redirect()->back()->withStatus('Activity successfully updated.');
    }

    private function parseDate($value)
    {
        return $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    /**
     * Store any uploaded gallery images against the given activity.
     *
     */
    private function storeGalleryImages(SyndicateActivity $row, Request $request)
    {
        if(!$request->hasFile('gallery_images')){
            return;
        }

        foreach($request->file('gallery_images') as $galleryImage){
            SyndicateActivityGallery::create([
                'activities_id' => $row->id,
                'gallery_image' => FilesHelper::storeFile('activity_gallery', $galleryImage),
            ]);
        }
    }

    /**
     * Delete whichever gallery images were staged for removal in the edit
     * form (marked client-side, only actually removed once the form saves).
     *
     */
    private function removeGalleryImages(SyndicateActivity $row, Request $request)
    {
        $galleryIds = $request->input('remove_gallery_images', []);

        if(empty($galleryIds)){
            return;
        }

        $galleryRows = $row->gallery()->whereIn('gallery_id', $galleryIds)->get();

        foreach($galleryRows as $galleryRow){
            if($galleryRow->getAttributes()['gallery_image']){
                FilesHelper::deleteFileByName('activity_gallery', $galleryRow->getAttributes()['gallery_image']);
            }
            $galleryRow->delete();
        }
    }

    /**
     * Flip the published/pending state of an activity - only reachable by
     * accounts with the syndicate_activities-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = SyndicateActivity::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? '0' : '1',
        ]);

        return redirect()->back()->withStatus('Activity ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = SyndicateActivity::with('gallery')->findOrFail($id);

        if($row->getAttributes()['main_image']){
            FilesHelper::deleteFileByName('activities_main', $row->getAttributes()['main_image']);
        }

        if($row->getAttributes()['any_file']){
            FilesHelper::deleteFileByName('activities_file', $row->getAttributes()['any_file']);
        }

        foreach($row->gallery as $galleryRow){
            if($galleryRow->getAttributes()['gallery_image']){
                FilesHelper::deleteFileByName('activity_gallery', $galleryRow->getAttributes()['gallery_image']);
            }
            $galleryRow->delete();
        }

        $row->delete();

        return redirect()->back()->withStatus('Activity successfully deleted.');
    }

}
