<?php

namespace App\Http\Controllers\Cms\Base;
use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\FixedSection;
use Illuminate\Http\Request;

class FixedSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:fixed_sections-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:fixed_sections-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:fixed_sections-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:fixed_sections-delete', ['only' => ['destroy']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Fixed Sections',
            'link' => 'fixed-sections',
            'table_name' => 'fixed_sections'
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

        $columns = ['id', 'slug', 'title'];

        $searchableColumns = ['slug', 'title'];

        $filterableColumns = [
            'Slug' => ['slug', 'text'],
            'Title' => ['title', 'text']
        ];

        $rows = PaginationHelper::paginateData(FixedSection::class, $columns, $searchableColumns);

        // return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'rows'));
        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'rows', 'filterableColumns'));
    }

    /**
     * Display a listing of the specified row
     *
     */
    public function show($id)
    {
        $page_info = $this->page_info();

        $row = FixedSection::findOrFail($id);

        return view('cms.base.'.$page_info['link'].'.show', compact('page_info', 'row'));
    }

    /**
     * Show the form for creating a new row
     *
     */
    public function create()
    {
        $page_info = $this->page_info();

        return view('cms.base.'.$page_info['link'].'.create', compact('page_info'));
    }

    /**
     * Store a newly created row in the database
     *
     */
    public function store(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'slug' => 'required|unique:'.$page_info['table_name'],
            'image' => 'nullable|mimes:png,jpg,jpeg',
            'title' => 'required|string|max:255',
            'text' => 'required'
        ]);

        $image_path = null;
        if($request->image){
            $image_path = FilesHelper::storeFile('fixed-sections', $request->image);
        }

        FixedSection::create([
            'slug' => $request->slug,
            'image' => $image_path,
            'title' => $request->title,
            'text' => $request->text
        ]);

        return redirect()->route('admin.'.$page_info['link'].'.index')->withStatus('Record successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = FixedSection::findOrFail($id);

        return view('cms.base.'.$page_info['link'].'.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = FixedSection::findOrFail($id);

        $this->validate($request, [
            'image' => 'nullable|mimes:png,jpg,jpeg',
            'title' => 'required|string|max:255',
            'text' => 'required'
        ]);

        $image_path = $row->getAttributes()['image'];
        if($request->image){
            $image_path = FilesHelper::storeFile('fixed-sections', $request->image);
        }

        $row->update([
            'image' => $image_path,
            'title' => $request->title,
            'text' => $request->text
        ]);

        return redirect()->back()->withStatus('Record successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        FixedSection::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Record successfully deleted.');
    }

}
