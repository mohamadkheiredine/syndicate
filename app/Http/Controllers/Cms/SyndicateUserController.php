<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\FilesHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SyndicateUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:syndicate_users-view', ['only' => ['index']]);
        $this->middleware('permission:syndicate_users-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:syndicate_users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:syndicate_users-delete', ['only' => ['destroy']]);
        $this->middleware('permission:syndicate_users-activate', ['only' => ['activate']]);
        $this->middleware('permission:syndicate_users-reset_password', ['only' => ['resetPassword', 'resetPasswordUpdate']]);
        $this->middleware('permission:syndicate_users-export', ['only' => ['exportCsv', 'exportExcel', 'exportPdf']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Syndicate Users',
            'link' => 'syndicate-users',
            'table_name' => 'syndicate_user'
        ];
        return $page_info;
    }

    /**
     * The date input component submits dates as d/m/Y; convert to Y-m-d for storage.
     *
     */
    private function parseDate($value)
    {
        return $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    /**
     * Columns/searchable-columns shared by the listing and the exports, so exports
     * always reflect the same data set and search/filter logic as the table.
     *
     */
    private function listQueryConfig()
    {
        return [
            'columns' => [
                'id', 'first_name', 'fathers_name', 'last_name', 'dob', 'email',
                'mobile_number', 'company', 'department', 'unit', 'date_employment',
                'blood_type', 'registration_date', 'registration_fees', 'kaza', 'city',
                'street', 'building', 'floor', 'has_id', 'photo', 'activation_code', 'created_at'
            ],
            'searchable' => ['first_name', 'last_name', 'email', 'mobile_number', 'company'],
        ];
    }

    /**
     * Display a listing of the Table
     *
     */
    public function index()
    {
        $page_info = $this->page_info();

        $config = $this->listQueryConfig();

        $filterableColumns = [
            'First Name' => ['first_name', 'text'],
            'Last Name' => ['last_name', 'text'],
            'Email' => ['email', 'text'],
            'Mobile Number' => ['mobile_number', 'text'],
            'Company' => ['company', 'text'],
        ];

        $rows = PaginationHelper::paginateData(SyndicateUser::class, $config['columns'], $config['searchable']);

        return view('cms.pages.' . $page_info['link'] . '.index', compact('page_info', 'rows', 'filterableColumns'));
    }

    /**
     * Rows for export: every row matching the current search/filter, not just the
     * current page, mapped to flat, human-readable columns.
     *
     */
    private function exportRows()
    {
        $config = $this->listQueryConfig();

        $rows = PaginationHelper::filteredQuery(SyndicateUser::class, $config['columns'], $config['searchable'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $rows->map(function ($row) {
            return [
                'Full Name' => trim($row->first_name . ' ' . $row->fathers_name . ' ' . $row->last_name),
                'DOB' => $row->dob ? $row->dob->format('Y-m-d') : '',
                'Email' => $row->email,
                'Mobile Number' => $row->mobile_number,
                'Company' => $row->company,
                'Department' => $row->department,
                'Unit' => $row->unit,
                'Date Employment' => $row->date_employment ? $row->date_employment->format('Y-m-d') : '',
                'Blood Type' => $row->blood_type,
                'Registration Date' => $row->registration_date ? $row->registration_date->format('Y-m-d') : '',
                'Registration Fees' => $row->registration_fees,
                'Kaza' => $row->kaza,
                'City' => $row->city,
                'Street' => $row->street,
                'Building' => $row->building,
                'Floor' => $row->floor,
                'Has ID Card' => $row->has_id ? 'YES' : 'NO',
                'Status' => $row->activation_code == 'activated' ? 'Activated' : 'Deactivated',
            ];
        });
    }

    /**
     * Export the listing as CSV
     *
     */
    public function exportCsv()
    {
        $rows = $this->exportRows();

        return new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $rows->isEmpty() ? [] : array_keys($rows->first()));
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="syndicate-users.csv"',
        ]);
    }

    /**
     * Export the listing as Excel (HTML table served as .xls, opens natively in Excel)
     *
     */
    public function exportExcel()
    {
        $rows = $this->exportRows();

        $html = view('cms.pages.syndicate-users.export-table', compact('rows'))->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="syndicate-users.xls"',
        ]);
    }

    /**
     * Export the listing as PDF
     *
     */
    public function exportPdf()
    {
        $rows = $this->exportRows();

        $pdf = Pdf::loadView('cms.pages.syndicate-users.export-table', compact('rows'))->setPaper('a4', 'landscape');

        return $pdf->download('syndicate-users.pdf');
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:' . $page_info['table_name'] . ',email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'blood_type' => 'required|string|max:10',
            'registration_fees' => 'nullable|numeric',
            'photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = FilesHelper::storeFile('syndicate-users', $request->file('photo'));
        }

        SyndicateUser::create([
            'first_name' => $request->first_name,
            'fathers_name' => $request->fathers_name,
            'last_name' => $request->last_name,
            'dob' => $this->parseDate($request->dob),
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => $request->password,
            'photo' => $photo,
            'has_id' => $request->has_id ? true : false,
            'blood_type' => $request->blood_type,
            'registration_date' => $this->parseDate($request->registration_date),
            'registration_fees' => $request->registration_fees,
            'kaza' => $request->kaza,
            'city' => $request->city,
            'street' => $request->street,
            'building' => $request->building,
            'floor' => $request->floor,
            'company' => $request->company,
            'department' => $request->department,
            'unit' => $request->unit,
            'date_employment' => $this->parseDate($request->date_employment),
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Syndicate user successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateUser::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $page_info = $this->page_info();

        $row = SyndicateUser::findOrFail($id);

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:' . $page_info['table_name'] . ',email,' . $row->id,
            'blood_type' => 'required|string|max:10',
            'registration_fees' => 'nullable|numeric',
            'photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        $photo = $row->getAttributes()['photo'];
        if ($request->hasFile('photo')) {
            $photo = FilesHelper::storeFile('syndicate-users', $request->file('photo'));
        }

        $row->update([
            'first_name' => $request->first_name,
            'fathers_name' => $request->fathers_name,
            'last_name' => $request->last_name,
            'dob' => $this->parseDate($request->dob),
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'photo' => $photo,
            'has_id' => $request->has_id ? true : false,
            'blood_type' => $request->blood_type,
            'registration_date' => $this->parseDate($request->registration_date),
            'registration_fees' => $request->registration_fees,
            'kaza' => $request->kaza,
            'city' => $request->city,
            'street' => $request->street,
            'building' => $request->building,
            'floor' => $request->floor,
            'company' => $request->company,
            'department' => $request->department,
            'unit' => $request->unit,
            'date_employment' => $this->parseDate($request->date_employment),
        ]);

        return redirect()->back()->withStatus('Syndicate user successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        SyndicateUser::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Syndicate user successfully deleted.');
    }

    /**
     * Remove the photo of the specified row
     *
     */
    public function imageRemove($id)
    {
        $row = SyndicateUser::findOrFail($id);

        $row->photo = null;
        $row->save();

        return redirect()->back()->withStatus('Photo successfully deleted.');
    }

    /**
     * Activate / Deactivate a specified row
     *
     */
    public function activate(Request $request)
    {
        $row = SyndicateUser::findOrFail($request->id);

        $row->update([
            'activation_code' => $row->activation_code == 'activated' ? '' : 'activated'
        ]);

        return redirect()->back()->withStatus('Syndicate user status successfully updated.');
    }

    /**
     * Show the reset password form
     *
     */
    public function resetPassword($id)
    {
        $page_info = $this->page_info();

        $row = SyndicateUser::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.reset-password', compact('page_info', 'row'));
    }

    /**
     * Reset the password of the specified row
     *
     */
    public function resetPasswordUpdate(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'user_id' => 'required|exists:' . $page_info['table_name'] . ',id',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        $row = SyndicateUser::findOrFail($request->user_id);
        $row->password = $request->password;
        $row->save();

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Password successfully reset.');
    }

}
