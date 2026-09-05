<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateOffer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyndicateOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:syndicate_offers-view', ['only' => ['index']]);
        $this->middleware('permission:syndicate_offers-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:syndicate_offers-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:syndicate_offers-delete', ['only' => ['destroy']]);
        $this->middleware('permission:syndicate_offers-publish', ['only' => ['togglePublish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Offers Specials Manager',
            'link' => 'syndicate-offers'
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

        $rows = PaginationHelper::paginateData(SyndicateOffer::class, $columns, $searchableColumns);

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
            'main_image' => 'nullable|image|max:5120',
            'new_image' => 'nullable|image|max:5120',
            'pdf' => 'nullable|mimes:pdf|max:5120',
            'start_date' => 'required',
            'end_date' => 'required',
            'place' => 'required|string|max:255',
            'offers' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
        ]);

        $startDate = $this->parseDate($request->start_date);
        $endDate = $this->parseDate($request->end_date);

        if($startDate && $endDate && $startDate > $endDate){
            return redirect()->back()->withInput()->withErrors(['end_date' => 'End Date cannot be before Start Date.']);
        }

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, not automatic. Anyone else's offer always
        // stays pending until someone with syndicate_offers-publish approves
        // it later from the list.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_offers-publish');
        $published = $canPublish && $request->boolean('publish_status');

        SyndicateOffer::create([
            'admin_id' => Auth::guard('admin')->user()->id,
            'title' => $request->title,
            'main_image' => $request->hasFile('main_image') ? FilesHelper::storeFile('offers', $request->file('main_image')) : '',
            'new_image' => $request->hasFile('new_image') ? FilesHelper::storeFile('offers', $request->file('new_image')) : '',
            'pdf' => $request->hasFile('pdf') ? FilesHelper::storeFile('offers', $request->file('pdf')) : '',
            'start_date' => $startDate ?? now()->format('Y-m-d'),
            'end_date' => $endDate ?? now()->format('Y-m-d'),
            'place' => $request->place,
            'offers' => $request->offers,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Offer successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateOffer::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SyndicateOffer::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'main_image' => 'nullable|image|max:5120',
            'new_image' => 'nullable|image|max:5120',
            'pdf' => 'nullable|mimes:pdf|max:5120',
            'start_date' => 'required',
            'end_date' => 'required',
            'place' => 'required|string|max:255',
            'offers' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
        ]);

        $startDate = $this->parseDate($request->start_date);
        $endDate = $this->parseDate($request->end_date);

        if($startDate && $endDate && $startDate > $endDate){
            return redirect()->back()->withInput()->withErrors(['end_date' => 'End Date cannot be before Start Date.']);
        }

        // Only a publisher gets a say in this at all - and even then it's their
        // choice via the checkbox, same as create. Anyone else's edit always
        // goes back to pending, even if the offer was already live, until
        // someone with syndicate_offers-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('syndicate_offers-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $image = $row->getAttributes()['main_image'];
        if($request->hasFile('main_image')){
            if($image){
                FilesHelper::deleteFileByName('offers', $image);
            }
            $image = FilesHelper::storeFile('offers', $request->file('main_image'));
        }

        $newImage = $row->getAttributes()['new_image'];
        if($request->hasFile('new_image')){
            if($newImage){
                FilesHelper::deleteFileByName('offers', $newImage);
            }
            $newImage = FilesHelper::storeFile('offers', $request->file('new_image'));
        }

        $pdf = $row->getAttributes()['pdf'];
        if($request->hasFile('pdf')){
            if($pdf){
                FilesHelper::deleteFileByName('offers', $pdf);
            }
            $pdf = FilesHelper::storeFile('offers', $request->file('pdf'));
        }

        $row->update([
            'title' => $request->title,
            'main_image' => $image,
            'new_image' => $newImage,
            'pdf' => $pdf,
            'start_date' => $startDate ?? $row->getAttributes()['start_date'],
            'end_date' => $endDate ?? $row->getAttributes()['end_date'],
            'place' => $request->place,
            'offers' => $request->offers,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $published ? '1' : '0',
            'publish_status' => $published ? '1' : '0',
        ]);

        return redirect()->back()->withStatus('Offer successfully updated.');
    }

    private function parseDate($value)
    {
        return $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    /**
     * Flip the published/pending state of an offer - only reachable by
     * accounts with the syndicate_offers-publish permission.
     *
     */
    public function togglePublish($id)
    {
        $row = SyndicateOffer::findOrFail($id);

        $published = (int) $row->getAttributes()['publish_status'] === 1;

        $row->update([
            'status' => $published ? '0' : '1',
            'publish_status' => $published ? '0' : '1',
        ]);

        return redirect()->back()->withStatus('Offer ' . ($published ? 'unpublished' : 'published') . '.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = SyndicateOffer::findOrFail($id);

        if($row->getAttributes()['main_image']){
            FilesHelper::deleteFileByName('offers', $row->getAttributes()['main_image']);
        }

        if($row->getAttributes()['new_image']){
            FilesHelper::deleteFileByName('offers', $row->getAttributes()['new_image']);
        }

        if($row->getAttributes()['pdf']){
            FilesHelper::deleteFileByName('offers', $row->getAttributes()['pdf']);
        }

        $row->delete();

        return redirect()->back()->withStatus('Offer successfully deleted.');
    }

}
