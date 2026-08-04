<?php

namespace App\Http\Controllers\Cms;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:faqs-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:faqs-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:faqs-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:faqs-delete', ['only' => ['destroy']]);
        $this->middleware('permission:faqs-publish', ['only' => ['publish']]);
        $this->middleware('permission:faqs-order', ['only' => ['order', 'orderSubmit']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'FAQs',
            'link' => 'faqs'
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

        // $rows = Faq::select([
        //     'id',
        //     'title',
        //     'publish'
        // ])->get();

        // columns to select
        $columns = ['id', 'title', 'publish'];

        // columns to apply search on
        $searchableColumns = ['title', 'publish'];

        $filterableColumns = ['Title' => ['title', 'text'], 'Publish' => ['publish', 'boolean']];

        // get paginated Data from PaginationHelper
        $rows = PaginationHelper::paginateData(Faq::class, $columns, $searchableColumns);

        // return view('cms.pages.'.$page_info['link'].'.index', compact('page_info', 'rows'));
        return view('cms.pages.'.$page_info['link'].'.index', compact('page_info', 'rows', 'filterableColumns'));
    }

    /**
     * Display a listing of the specified row
     *
     */
    public function show($id)
    {
        $page_info = $this->page_info();

        $row = Faq::findOrFail($id);

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
            'title' => 'required|string|max:255',
            'text' => 'required'
        ]);

        Faq::create([
            'title' => $request->title,
            'text' => $request->text,
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

        $row = Faq::findOrFail($id);

        return view('cms.pages.'.$page_info['link'].'.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = Faq::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'text' => 'required'
        ]);

        $row->update([
            'title' => $request->title,
            'text' => $request->text,
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
        Faq::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Record successfully deleted.');
    }

    /**
     * Publish a specified row
     *
     */
    public function publish(Request $request)
    {
        $id = $request['id'];
        $row = Faq::findOrFail($id);

        $row->update([
            'publish' => !$row->publish
        ]);
    }

    /**
     * Show the form for ordering all rows
     *
     */
    public function order()
    {
        $page_info = $this->page_info();

        $rows = Faq::select([
            'id',
            'title'
        ])->get();

        return view('cms.pages.'.$page_info['link'].'.order', compact('page_info', 'rows'));
    }

    /**
     * Update the order for all rows in the database
     *
     */
    public function orderSubmit(Request $request)
    {
        foreach($request->id as $key => $id) {
            $row = Faq::findOrFail($id);
            $row->update([
                'pos' => $request->pos[$key]
            ]);
        }

        return redirect()->back()->withStatus('Records successfully ordered.');
    }

}
