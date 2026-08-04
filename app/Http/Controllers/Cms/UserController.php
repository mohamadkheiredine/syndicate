<?php

namespace App\Http\Controllers\Cms;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:users-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:users-blocked', ['only' => ['blocked']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Users',
            'link' => 'users'
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

        $rows = User::select([
            'id',
            'image',
            'first_name',
            'last_name',
            'full_name',
            'email',
            'country_code',
            'mobile_number',
            'dob',
            'age'
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

        $row = User::findOrFail($id);

        return view('cms.pages.'.$page_info['link'].'.show', compact('page_info', 'row'));
    }

    /**
     * Block a specified row
     *
     */
    public function blocked(Request $request)
    {
        // Blocked functionality removed as field doesn't exist in new schema
        return redirect()->back()->with('error', 'Block functionality is not available.');
    }

    /**
     * Show the Users Addresses
     *
     */
    public function addresses($id)
    {
        $page_info = $this->page_info();

        $rows = UserAddress::select([
            'id',
            'user_id',
            'label',
            'country_code',
            'mobile_number',
            'city',
            'street',
            'building',
            'apartment',
            'instructions',
            'lat',
            'lng',
            'google_map',
            'is_default'
        ])->where('user_id', $id)
        ->get();

        return view('cms.pages.'.$page_info['link'].'.addresses', compact('page_info', 'rows'));
    }

}
