<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicatePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class YearlyPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:yearly_payment-view', ['only' => ['index']]);
        $this->middleware('permission:yearly_payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:yearly_payment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:yearly_payment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:yearly_payment-export', ['only' => ['exportCsv', 'exportExcel', 'exportPdf']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Syndicate Yearly Payment',
            'link' => 'yearly-payment',
            'table_name' => 'syndicate_payments'
        ];
        return $page_info;
    }

    private function listQueryConfig()
    {
        return [
            'columns' => ['id', 'payment_year', 'payment_amount', 'created_at'],
            'searchable' => ['payment_year', 'payment_amount'],
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

        $rows = PaginationHelper::paginateData(SyndicatePayment::class, $config['columns'], $config['searchable']);

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
            'payment_year' => 'required|integer|unique:' . $page_info['table_name'] . ',payment_year',
            'payment_amount' => 'required|numeric',
        ]);

        SyndicatePayment::create([
            'payment_year' => $request->payment_year,
            'payment_amount' => $request->payment_amount,
        ]);

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Yearly payment successfully created.');
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit($id)
    {
        $page_info = $this->page_info();

        $row = SyndicatePayment::findOrFail($id);

        return view('cms.pages.' . $page_info['link'] . '.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request, $id)
    {
        $page_info = $this->page_info();

        $row = SyndicatePayment::findOrFail($id);

        $this->validate($request, [
            'payment_year' => 'required|integer|unique:' . $page_info['table_name'] . ',payment_year,' . $row->id,
            'payment_amount' => 'required|numeric',
        ]);

        $row->update([
            'payment_year' => $request->payment_year,
            'payment_amount' => $request->payment_amount,
        ]);

        return redirect()->back()->withStatus('Yearly payment successfully updated.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        SyndicatePayment::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Yearly payment successfully deleted.');
    }

    /**
     * Rows for export: every row matching the current search, flattened to human-readable columns.
     *
     */
    private function exportRows()
    {
        $config = $this->listQueryConfig();

        $rows = PaginationHelper::filteredQuery(SyndicatePayment::class, $config['columns'], $config['searchable'])
            ->orderBy('payment_year', 'desc')
            ->get();

        return $rows->map(function ($row) {
            return [
                'Year' => $row->payment_year,
                'Amount' => $row->payment_amount,
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
            'Content-Disposition' => 'attachment; filename="yearly-payment.csv"',
        ]);
    }

    /**
     * Export the listing as Excel (HTML table served as .xls, opens natively in Excel)
     *
     */
    public function exportExcel()
    {
        $rows = $this->exportRows();

        $html = view('cms.pages.yearly-payment.export-table', compact('rows'))->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="yearly-payment.xls"',
        ]);
    }

    /**
     * Export the listing as PDF
     *
     */
    public function exportPdf()
    {
        $rows = $this->exportRows();

        $pdf = Pdf::loadView('cms.pages.yearly-payment.export-table', compact('rows'))->setPaper('a4', 'landscape');

        return $pdf->download('yearly-payment.pdf');
    }

}
