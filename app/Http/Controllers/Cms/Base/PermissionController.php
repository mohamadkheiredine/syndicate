<?php

namespace App\Http\Controllers\Cms\Base;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:permissions-view', ['only' => ['index']]);
        $this->middleware('permission:permissions-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permissions-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permissions-delete', ['only' => ['destroy']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Permissions',
            'link' => 'permissions'
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

        $columns = ['id', 'name'];
        $searchableColumns = ['name'];

        $filterableColumns = ['Name' => ['name', 'text']];

        $rows = PaginationHelper::paginateData(Permission::class, $columns, $searchableColumns);

        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'rows', 'filterableColumns'));
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
            'name' => 'required|string|max:255'
        ]);

        $permissions = array_map('trim', explode(',', $request->name));

        foreach($permissions as $permission){
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        return redirect()->route('admin.'.$page_info['link'].'.index')->withStatus('Record successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = Permission::findOrFail($id);

        return view('cms.base.'.$page_info['link'].'.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = Permission::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255'
        ]);

        $row->update([
            'name' => $request->name,
            'guard_name' => 'admin'
        ]);

        return redirect()->back()->withStatus('Record successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        $row = Permission::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Record successfully deleted.');
    }

}
