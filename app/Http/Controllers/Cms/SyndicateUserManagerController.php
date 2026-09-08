<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Standalone port of mobilesyndicate-master's "Syndicate User Manager"
 * admin section (admin/list-user.php, create-user.php, edit-user.php).
 *
 * This is intentionally a SEPARATE section from the existing "Syndicate
 * Users" module (SyndicateUserController) - it was explicitly requested
 * as its own section. Both read/write the same syndicate_user table.
 *
 * Matches old's form fields exactly: first_name, fathers_name, last_name,
 * mothers_name, dob, mobile_number, home_number, email, password,
 * company, blood_type, profile_link (facebook/linkedin), photo (required),
 * any_file (optional). No activate toggle, reset-password page or exports
 * - old's Syndicate User Manager didn't have those.
 *
 * One deliberate difference from old: delete is a soft delete here (the
 * syndicate_user model uses SoftDeletes and the sibling module soft
 * deletes too), where old ran a hard SQL DELETE. The photo/any_file
 * files are still removed from storage on delete, same as old.
 */
class SyndicateUserManagerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:syndicate_user_manager-view', ['only' => ['index']]);
        $this->middleware('permission:syndicate_user_manager-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:syndicate_user_manager-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:syndicate_user_manager-delete', ['only' => ['destroy']]);
        $this->middleware('permission:syndicate_user_manager-activate', ['only' => ['activate']]);
    }

    public function page_info()
    {
        return [
            'title' => 'Syndicate User Manager',
            'link' => 'syndicate-user-manager',
            'table_name' => 'syndicate_user',
        ];
    }

    private function parseDate($value)
    {
        return $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    /**
     * Old stored one of facebook/linkedin depending on the profile_link
     * choice: FB -> facebook only, LINKEDIN -> linkedin only, BOTH -> both.
     */
    private function socialLinks(Request $request)
    {
        $choice = $request->profile_link;

        return [
            'profile_link' => $choice ?? '',
            'facebook' => in_array($choice, ['FB', 'BOTH']) ? ($request->facebook ?? '') : '',
            'linkedin' => in_array($choice, ['LINKEDIN', 'BOTH']) ? ($request->linkedin ?? '') : '',
        ];
    }

    public function index()
    {
        $page_info = $this->page_info();

        $columns = ['id', 'first_name', 'last_name', 'email', 'mobile_number', 'activation_code', 'created_at'];
        $searchableColumns = ['first_name', 'last_name', 'email', 'mobile_number'];

        $rows = PaginationHelper::paginateData(SyndicateUser::class, $columns, $searchableColumns);

        return view('cms.pages.' . $page_info['link'] . '.index', compact('page_info', 'rows'));
    }

    public function create()
    {
        $page_info = $this->page_info();

        return view('cms.pages.' . $page_info['link'] . '.create', compact('page_info'));
    }

    public function store(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'fathers_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'dob' => 'required',
            'mobile_number' => 'required|string|max:255',
            'home_number' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:' . $page_info['table_name'] . ',email',
            'password' => 'required|min:6',
            'company' => 'required|in:Alfa,Touch',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'profile_link' => 'required|in:FB,LINKEDIN,BOTH',
            'photo' => 'required|mimes:png,jpg,jpeg|max:2048',
            'any_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = array_merge([
            'first_name' => $request->first_name,
            'fathers_name' => $request->fathers_name,
            'last_name' => $request->last_name,
            'mothers_name' => $request->mothers_name,
            'dob' => $this->parseDate($request->dob),
            'mobile_number' => $request->mobile_number,
            'home_number' => $request->home_number,
            'email' => $request->email,
            'password' => $request->password,
            'company' => $request->company,
            'blood_type' => $request->blood_type,
            'photo' => FilesHelper::storeFile('user', $request->file('photo')),
            'any_file' => $request->hasFile('any_file') ? FilesHelper::storeFile('upload_file', $request->file('any_file')) : '',
            'lang' => 'en',
        ], $this->socialLinks($request));

        SyndicateUser::create($data);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Syndicate user successfully created.');
    }

    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateUser::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    public function update(Request $request, $id)
    {
        $page_info = $this->page_info();

        $row = SyndicateUser::findOrFail($id);

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'fathers_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'dob' => 'required',
            'mobile_number' => 'required|string|max:255',
            'home_number' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:' . $page_info['table_name'] . ',email,' . $row->id,
            'password' => 'nullable|min:6',
            'company' => 'required|in:Alfa,Touch',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'profile_link' => 'required|in:FB,LINKEDIN,BOTH',
            'photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'any_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $photo = $row->getAttributes()['photo'] ?? '';
        if ($request->hasFile('photo')) {
            if ($photo) {
                FilesHelper::deleteFileByName('user', $photo);
            }
            $photo = FilesHelper::storeFile('user', $request->file('photo'));
        }

        $anyFile = $row->getAttributes()['any_file'] ?? '';
        if ($request->hasFile('any_file')) {
            if ($anyFile) {
                FilesHelper::deleteFileByName('upload_file', $anyFile);
            }
            $anyFile = FilesHelper::storeFile('upload_file', $request->file('any_file'));
        }

        $data = array_merge([
            'first_name' => $request->first_name,
            'fathers_name' => $request->fathers_name,
            'last_name' => $request->last_name,
            'mothers_name' => $request->mothers_name,
            'dob' => $this->parseDate($request->dob),
            'mobile_number' => $request->mobile_number,
            'home_number' => $request->home_number,
            'email' => $request->email,
            'company' => $request->company,
            'blood_type' => $request->blood_type,
            'photo' => $photo,
            'any_file' => $anyFile,
        ], $this->socialLinks($request));

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $row->update($data);

        return redirect()->back()->withStatus('Syndicate user successfully updated.');
    }

    public function destroy($id)
    {
        $row = SyndicateUser::findOrFail($id);

        if ($photo = $row->getAttributes()['photo'] ?? '') {
            FilesHelper::deleteFileByName('user', $photo);
        }
        if ($anyFile = $row->getAttributes()['any_file'] ?? '') {
            FilesHelper::deleteFileByName('upload_file', $anyFile);
        }

        $row->delete();

        return redirect()->back()->withStatus('Syndicate user successfully deleted.');
    }

    /**
     * Toggle the member's status between activated and pending. Old's
     * Syndicate User Manager had no activate action (status was purely a
     * display), so this is a deliberate addition, gated by its own
     * syndicate_user_manager-activate permission.
     */
    public function activate(Request $request)
    {
        $row = SyndicateUser::findOrFail($request->id);

        $row->update([
            'activation_code' => ($row->getAttributes()['activation_code'] ?? '') === 'activated' ? '' : 'activated',
        ]);

        return redirect()->back()->withStatus('Syndicate user status successfully updated.');
    }
}
