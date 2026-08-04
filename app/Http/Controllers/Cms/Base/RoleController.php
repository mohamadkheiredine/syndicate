<?php

namespace App\Http\Controllers\Cms\Base;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:roles-view', ['only' => ['index']]);
        $this->middleware('permission:roles-create', ['only' => ['create','store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Roles',
            'link' => 'roles',
            'table_name' => 'roles'
        ];
        return $page_info;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $page_info = $this->page_info();

        // columns to select / show on the data table
        $columns = ['id', 'name'];

        // columns that are searchable (direct columns on the model)
        $searchableColumns = ['name'];

        // Filterable Columns to display on filter
        $filterableColumns = [
            'Name' => ['name', 'text']
        ];

        $rows = PaginationHelper::paginateData(Role::class, $columns, $searchableColumns);

        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'rows', 'filterableColumns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_info = $this->page_info();

        $permission_db = Permission::orderBy('name', 'asc')->get();

        $permission = [];
        foreach($permission_db as $key => $permission_db_row) {
            $tag = explode('-', $permission_db_row['name'])[0];
            $permission[$tag][] = $permission_db_row;
        }

        return view('cms.base.roles.create', compact('page_info', 'permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'name' => 'required|unique:'.$page_info['table_name'].',name'
        ]);

        $role = Role::create(['guard_name' => 'admin', 'name' => $request->input('name')]);

        // Permission IDs arrive as strings from the form; syncPermissions requires real ints or it treats them as names.
        $role->syncPermissions(array_map('intval', $request->input('permission', [])));

        return redirect()->back()->withStatus('Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_info = $this->page_info();

        $role = Role::find($id);

        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)
        ->get();

        return view('cms.base.roles.show',compact('page_info', 'role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $role = Role::find($id);

        $permission_db = Permission::orderBy('name', 'asc')->get();

        $permission = [];
        foreach($permission_db as $key => $permission_db_row) {
            $tag = explode('-', $permission_db_row['name'])[0];
            $permission[$tag][] = $permission_db_row;
        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();

        return view('cms.base.roles.edit',compact('page_info', 'role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        // Permission IDs arrive as strings from the form; syncPermissions requires real ints or it treats them as names.
        $role->syncPermissions(array_map('intval', $request->input('permission', [])));

        return redirect()->back()->withStatus('Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();

        return redirect()->back()->withStatus('Role deleted successfully');
    }
}