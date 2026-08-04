<?php

namespace App\Http\Controllers\Cms\Base;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;

class SimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:simulation-create', ['only' => ['index', 'store', 'destroy']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Simulation',
            'link' => 'simulation'
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

        $simulating_user = null;
        if(session()->has('simulation')){
            $simulating_user = User::select(['full_name'])->findOrFail(session('simulation'));
        }

        $users = User::GeneralScope()->select(['id', 'full_name'])->get();

        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'simulating_user', 'users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id'
        ]);

        session()->put('simulation', $request->user_id);

        return redirect()->back();
    }

    public function destroy()
    {
        session()->forget('simulation');

        return redirect()->back();
    }
}
