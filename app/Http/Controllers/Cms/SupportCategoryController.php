<?php

namespace App\Http\Controllers\Cms;
use App\Http\Controllers\Controller;
use App\Models\SupportCategory;
use Illuminate\Http\Request;

class SupportCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:support_categories-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:support_categories-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:support_categories-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:support_categories-delete', ['only' => ['destroy']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Support Categories',
            'link' => 'support-categories'
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

        $rows = SupportCategory::select([
            'id',
            'title'
        ])->get();

        return view('cms.pages.'.$page_info['link'].'.index', compact('page_info', 'rows'));
    }

    /**
     * Display a listing of the specified row
     *
     */
    public function show($id)
    {
        $page_info = $this->page_info();

        $row = SupportCategory::findOrFail($id);

        return view('cms.pages.'.$page_info['link'].'.show', compact('page_info', 'row'));
    }

    /**
     * Show the form for creating a new row
     *
     */
    public function create()
    {
        $page_info = $this->page_info();

        return view('cms.pages.'.$page_info['link'].'.create', compact('page_info'));
    }

    /**
     * Store a newly created row in the database
     *
     */
    public function store(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'title' => 'required|string|max:255'
        ]);

        SupportCategory::create([
            'title' => $request->title
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

        $row = SupportCategory::findOrFail($id);

        return view('cms.pages.'.$page_info['link'].'.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = SupportCategory::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255'
        ]);

        $row->update([
            'title' => $request->title
        ]);

        return redirect()->back()->withStatus('Record successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        SupportCategory::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Record successfully deleted.');
    }

}
