<?php

namespace App\Http\Controllers\Cms;
use App\Http\Controllers\Controller;

use App\Models\Support;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:support-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:support-delete', ['only' => ['destroy']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Support',
            'link' => 'support'
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

        $rows = Support::select([
            'id',
            'user_id',
            'support_category_id',
            'text'
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

        $row = Support::findOrFail($id);

        return view('cms.pages.'.$page_info['link'].'.show', compact('page_info', 'row'));
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        Support::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Record successfully deleted.');
    }

}
