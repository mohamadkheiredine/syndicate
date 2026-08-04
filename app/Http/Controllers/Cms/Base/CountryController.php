<?php

namespace App\Http\Controllers\Cms\Base;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:countries-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:countries-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:countries-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:countries-delete', ['only' => ['destroy']]);
        $this->middleware('permission:countries-publish', ['only' => ['publish']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Countries',
            'link' => 'countries'
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

        // columns to select
        $columns = ['id', 'phone', 'code', 'name', 'currency', 'publish'];

        // columns to apply search on
        $searchableColumns = ['phone', 'code', 'name', 'currency'];

        $filterableColumns = [
            'Phone' => ['phone', 'text'],
            'Code' => ['code', 'text'],
            'Name' => ['name', 'text'],
            'Currency' => ['currency', 'text'],
        ];

        // get paginated Data from PaginationHelper
        $rows = PaginationHelper::paginateData(Country::class, $columns, $searchableColumns);

        // server side pagination view
        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'rows', 'filterableColumns'));

        // $rows = Country::select($columns)->get();
        // return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'rows'));
    }

    /**
     * Display a listing of the specified row
     *
     */
    public function show($id)
    {
        $page_info = $this->page_info();

        $row = Country::findOrFail($id);

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
            'phone' => 'required|unique:countries,phone',
            'code' => 'required|unique:countries,code|string|max:2',
            'name' => 'required|unique:countries,name|string|max:80',
            'currency' => 'required|unique:countries,currency|string|max:3'
        ]);

        Country::create([
            'phone' => $request->phone,
            'code' => $request->code,
            'name' => $request->name,
            'currency' => $request->currency,
            'publish' => $request->publish ? 1 : 0
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

        $row = Country::findOrFail($id);

        return view('cms.base.'.$page_info['link'].'.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $page_info = $this->page_info();

        $row = Country::findOrFail($id);

        $this->validate($request, [
            'phone' => 'required|unique:countries,phone,'.$row->id,
            'code' => 'required|string|max:2|unique:countries,code,'.$row->id,
            'name' => 'required|string|max:80|unique:countries,name,'.$row->id,
            'currency' => 'required|string|max:3|unique:countries,currency,'.$row->id
        ]);

        $row->update([
            'phone' => $request->phone,
            'code' => $request->code,
            'name' => $request->name,
            'currency' => $request->currency,
            'publish' => $request->publish ? 1 : 0
        ]);

        return redirect()->back()->withStatus('Record successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = Country::findOrFail($id);
        $row->delete();

        return redirect()->back()->withStatus('Record successfully deleted.');
    }

    /**
     * Publish a specified row
     *
     */
    public function publish(Request $request)
    {
        $id = $request['id'];
        $row = Country::findOrFail($id);

        $row->update([
            'publish' => !$row->publish
        ]);
    }

}
