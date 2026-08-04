<?php

namespace App\Http\Controllers\Cms\Base;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:dashboard-view', ['only' => ['index']]);
    }

    public function index()
    {
        return view('cms.base.dashboard');
    }
    
}