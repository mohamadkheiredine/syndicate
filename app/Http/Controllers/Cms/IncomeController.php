<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Income;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:income-view', ['only' => ['index']]);
        $this->middleware('permission:income-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:income-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:income-delete', ['only' => ['destroy']]);
        $this->middleware('permission:income-export', ['only' => ['exportCsv', 'exportExcel', 'exportPdf']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Income',
            'link' => 'income'
        ];
        return $page_info;
    }

    private function listQueryConfig()
    {
        return [
            'columns' => ['id', 'title', 'description', 'payment_date', 'amount', 'created_at'],
            'searchable' => ['title', 'description'],
        ];
    }

    /**
     * The date input component submits dates as d/m/Y; convert to a real datetime for storage.
     *
     */
    private function parseDate($value)
    {
        return $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Display a listing of the Table
     *
     */
    public function index()
    {
        $page_info = $this->page_info();
        $config = $this->listQueryConfig();

        $rows = PaginationHelper::paginateData(Income::class, $config['columns'], $config['searchable']);

        return view('cms.pages.' . $page_info['link'] . '.index', compact('page_info', 'rows'));
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'payment_date' => 'required',
            'amount' => 'required|numeric',
        ]);

        Income::create([
            'title' => $request->title,
            'description' => $request->description,
            'payment_date' => $this->parseDate($request->payment_date),
            'amount' => $request->amount,
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Income payment successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = Income::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $row = Income::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'payment_date' => 'required',
            'amount' => 'required|numeric',
        ]);

        $row->update([
            'title' => $request->title,
            'description' => $request->description,
            'payment_date' => $this->parseDate($request->payment_date),
            'amount' => $request->amount,
        ]);

        return redirect()->back()->withStatus('Income payment successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        Income::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Income payment successfully deleted.');
    }

    /**
     * Rows for export: every row matching the current search, flattened to human-readable columns.
     *
     */
    private function exportRows()
    {
        $config = $this->listQueryConfig();

        $rows = PaginationHelper::filteredQuery(Income::class, $config['columns'], $config['searchable'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $rows->map(function ($row) {
            return [
                'Title' => $row->title,
                'Description' => $row->description,
                'Payment Date' => $row->payment_date ? $row->payment_date->format('Y-m-d H:i:s') : '',
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
            'Content-Disposition' => 'attachment; filename="income.csv"',
        ]);
    }

    /**
     * Export the listing as Excel (HTML table served as .xls, opens natively in Excel)
     *
     */
    public function exportExcel()
    {
        $rows = $this->exportRows();

        $html = view('cms.pages.income.export-table', compact('rows'))->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="income.xls"',
        ]);
    }

    /**
     * Export the listing as PDF
     *
     */
    public function exportPdf()
    {
        $rows = $this->exportRows();

        $pdf = Pdf::loadView('cms.pages.income.export-table', compact('rows'))->setPaper('a4', 'landscape');

        return $pdf->download('income.pdf');
    }

}
