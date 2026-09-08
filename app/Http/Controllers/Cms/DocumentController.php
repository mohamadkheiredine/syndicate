<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:documents-view', ['only' => ['index']]);
        $this->middleware('permission:documents-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:documents-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:documents-delete', ['only' => ['destroy']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Uploaded Documents',
            'link' => 'documents'
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

        $columns = ['id', 'title', 'file', 'created_at'];

        $searchableColumns = ['title'];

        $rows = PaginationHelper::paginateData(Document::class, $columns, $searchableColumns);

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
            'file' => 'required|file|max:10240',
        ]);

        Document::create([
            'title' => $request->title,
            'file' => FilesHelper::storeFile('documents', $request->file('file')),
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Document successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = Document::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = Document::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240',
        ]);

        $file = $row->getAttributes()['file'];
        if ($request->hasFile('file')) {
            if ($file) {
                FilesHelper::deleteFileByName('documents', $file);
            }
            $file = FilesHelper::storeFile('documents', $request->file('file'));
        }

        $row->update([
            'title' => $request->title,
            'file' => $file,
        ]);

        return redirect()->back()->withStatus('Document successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = Document::findOrFail($id);

        if ($row->getAttributes()['file']) {
            FilesHelper::deleteFileByName('documents', $row->getAttributes()['file']);
        }

        $row->delete();

        return redirect()->back()->withStatus('Document successfully deleted.');
    }

}
