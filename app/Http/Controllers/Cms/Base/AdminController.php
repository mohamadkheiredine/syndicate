<?php

namespace App\Http\Controllers\Cms\Base;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:admins-view', ['only' => ['index']]);
        $this->middleware('permission:admins-create', ['only' => ['create','store']]);
        $this->middleware('permission:admins-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:admins-delete', ['only' => ['destroy']]);
        $this->middleware('permission:admins-block', ['only' => ['block']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Admins Management',
            'link' => 'admins',
            'table_name' => 'admins'
        ];
        return $page_info;
    }

    /**
     * Summary of update_timezone
     * @return
     */
    public function update_timezone(Request $request) {

        $current_user = $request->user();

        $current_user->timezone = $request->timezone;

        $current_user->save();

        return redirect()->back();
    }

    /**
     * Display a listing of the Table
     *
     */
    public function index()
    {
        $page_info = $this->page_info();

        $columns = ['id', 'first_name', 'last_name', 'email', 'blocked','created_at'];

        $searchableColumns = ['first_name', 'last_name', 'email', 'created_at'];

        $relationalColumns = ['roles' => ['name']];

        $filterableColumns = [
            'First Name' => ['first_name', 'text'],
            'Last Name' => ['last_name', 'text'],
            'Email' => ['email', 'text'],
            'Created At' => ['created_at', 'date'],
            'Roles' => ['roles', 'text'],
        ];

        $current_user = Auth::user();

        $rows = PaginationHelper::paginateData(Admin::class, $columns, $searchableColumns, $relationalColumns);

        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'rows', 'filterableColumns'));

    }

    /**
     * Show the form for creating a new row
     *
     */
    public function create()
    {
        $page_info = $this->page_info();

        $roles = Role::pluck('name','name')->all();

        return view('cms.base.'.$page_info['link'].'.create', compact('page_info', 'roles'));
    }

    /**
     * Store a newly created row in the database
     *
     */
    public function store(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:'.$page_info['table_name'],
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'roles' => 'required|array'
        ]);

        $row = Admin::create($request->merge([
			'password' => Hash::make($request->password)
		])->all());

        $row->assignRole($request->roles);

        return redirect()->route('admin.'.$page_info['link'].'.index')->withStatus('Admin successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = Admin::select([
            'id',
            'first_name',
            'last_name',
            'email'
        ])->findOrFail($id);

        $roles = Role::pluck('name','name')->all();
        $userRole = $row->roles->pluck('name','name')->all();

        return view('cms.base.'.$page_info['link'].'.edit', compact('page_info', 'row', 'roles', 'userRole'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $page_info = $this->page_info();

        $row = Admin::findOrFail($id);

        $this->validate($request, [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:'.$page_info['table_name'].',email,'.$row->id,
            'roles' => 'required|array'
        ]);

        if($request->password){
            $this->validate($request, [
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password'
            ]);
            $password = Hash::make($request->password);
        }

        $row->update($request->merge([
			'password' => isset($password) && $password ? $password : $row->password
		])->all());

        DB::table('model_has_roles')->where('model_id', $row->id)->delete();
        $row->assignRole($request->roles);

        return redirect()->back()->withStatus('Admin successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Admin successfully deleted.');
    }

    /**
     * Block a specified admin
     *
     */
    public function block(Request $request)
    {
        $id = $request['id'];
        $row = Admin::findOrFail($id);

        $row->update([
            'blocked' => !$row->blocked
        ]);
    }

}
