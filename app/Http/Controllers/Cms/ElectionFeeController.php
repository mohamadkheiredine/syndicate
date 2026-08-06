<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\SyndicateUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ElectionFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:election_fees-view', ['only' => ['index']]);
        $this->middleware('permission:election_fees-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:election_fees-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:election_fees-delete', ['only' => ['destroy']]);
        $this->middleware('permission:election_fees-export', ['only' => ['exportCsv', 'exportExcel', 'exportPdf']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Election Fees',
            'link' => 'election-fees'
        ];
        return $page_info;
    }

    private function listQueryConfig()
    {
        return [
            'columns' => ['id', 'user_id', 'amount', 'created_at'],
            'searchable' => [],
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

        $rows = PaginationHelper::paginateData(Election::class, $config['columns'], $config['searchable']);
        $rows->getCollection()->load('user');

        return view('cms.pages.' . $page_info['link'] . '.index', compact('page_info', 'rows'));
    }

    /**
     * Show the form for creating a new row
     *
     */
    public function create()
    {
        $page_info = $this->page_info();

        $users = SyndicateUser::select(['id', 'first_name', 'fathers_name', 'last_name', 'email'])
            ->orderBy('first_name')
            ->get();

        return view('cms.pages.' . $page_info['link'] . '.create', compact('page_info', 'users'));
    }

    /**
     * Store a newly created row in the database
     *
     */
    public function store(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'user_id' => 'required|exists:syndicate_user,id',
            'amount' => 'required|numeric',
        ]);

        Election::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Election fee successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = Election::findOrFail($id);

        $users = SyndicateUser::select(['id', 'first_name', 'fathers_name', 'last_name', 'email'])
            ->orderBy('first_name')
            ->get();

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row', 'users'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = Election::findOrFail($id);

        $this->validate($request, [
            'user_id' => 'required|exists:syndicate_user,id',
            'amount' => 'required|numeric',
        ]);

        $row->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
        ]);

        return redirect()->back()->withStatus('Election fee successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        Election::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Election fee successfully deleted.');
    }

    /**
     * Rows for export: every row matching the current search, flattened to human-readable columns.
     *
     */
    private function exportRows()
    {
        $config = $this->listQueryConfig();

        $rows = PaginationHelper::filteredQuery(Election::class, $config['columns'], $config['searchable'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return $rows->map(function ($row) {
            return [
                'Full Name' => $row->user ? trim($row->user->first_name . ' ' . $row->user->fathers_name . ' ' . $row->user->last_name) : '',
                'Email' => $row->user ? $row->user->email : '',
                'Company' => $row->user ? $row->user->company : '',
                'Amount' => $row->amount,
                'Date Added' => $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
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
            'Content-Disposition' => 'attachment; filename="election-fees.csv"',
        ]);
    }

    /**
     * Export the listing as Excel (HTML table served as .xls, opens natively in Excel)
     *
     */
    public function exportExcel()
    {
        $rows = $this->exportRows();

        $html = view('cms.pages.election-fees.export-table', compact('rows'))->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="election-fees.xls"',
        ]);
    }

    /**
     * Export the listing as PDF
     *
     */
    public function exportPdf()
    {
        $rows = $this->exportRows();

        $pdf = Pdf::loadView('cms.pages.election-fees.export-table', compact('rows'))->setPaper('a4', 'landscape');

        return $pdf->download('election-fees.pdf');
    }

}
