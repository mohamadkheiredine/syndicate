<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\SubscriptionsHelper;
use App\Http\Controllers\Controller;
use App\Models\MemberPayment;
use App\Models\SyndicatePayment;
use App\Models\SyndicateUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:members_payment-view', ['only' => ['index']]);
        $this->middleware('permission:members_payment-create', ['only' => ['create', 'store', 'outstandingYears']]);
        $this->middleware('permission:members_payment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:members_payment-export', ['only' => ['exportCsv', 'exportExcel', 'exportPdf']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Members Payment',
            'link' => 'members-payment',
            'table_name' => 'user_expences'
        ];
        return $page_info;
    }

    /**
     * Total amount a syndicate user still owes: unpaid years (at the configured rate) plus
     * any partially-paid year's shortfall.
     *
     */
    private function calculateDueAmount($userId)
    {
        $user = SyndicateUser::find($userId);
        if (!$user) {
            return null;
        }

        $creationYear = (int) $user->created_at->format('Y');
        $currentYear = (int) date('Y');

        $paidByYear = MemberPayment::where('user_id', $userId)
            ->where('ue_year', '>=', $creationYear)
            ->selectRaw('ue_year, SUM(amount) as total')
            ->groupBy('ue_year')
            ->pluck('total', 'ue_year');

        $rates = SyndicatePayment::whereBetween('payment_year', [$creationYear, $currentYear])
            ->pluck('payment_amount', 'payment_year');

        $due = 0;
        for ($year = $creationYear; $year <= $currentYear; $year++) {
            $expected = $rates[$year] ?? 0;
            $paid = $paidByYear[$year] ?? 0;
            $due += max($expected - $paid, 0);
        }

        return $due;
    }

    /**
     * Query for the table/export: every payment matching the current user/year filters.
     * Not executed here — index() paginates it (only ~50 rows ever hit the browser),
     * exports call ->get() on it (the full matching set, for a real export file).
     *
     */
    private function filteredQuery(Request $request)
    {
        $query = MemberPayment::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('year')) {
            $query->where('ue_year', $request->year);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Display a listing of the Table
     *
     */
    public function index(Request $request)
    {
        $page_info = $this->page_info();

        $entries_per_page = $request->get('entries_per_page', 10);
        $rows = $this->filteredQuery($request)->paginate($entries_per_page)->withQueryString();

        $users = SyndicateUser::select(['id', 'first_name', 'fathers_name', 'last_name', 'email'])
            ->orderBy('first_name')
            ->get();

        $years = SyndicatePayment::orderBy('payment_year', 'desc')->pluck('payment_year');

        $selected_user = $request->user_id;
        $selected_year = $request->year;

        $total_due_amount = $request->filled('user_id') ? $this->calculateDueAmount($request->user_id) : null;

        return view('cms.pages.' . $page_info['link'] . '.index', compact(
            'page_info', 'rows', 'users', 'years', 'selected_user', 'selected_year', 'total_due_amount'
        ));
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
     * Outstanding years (and their rate) for a syndicate user — powers the "Payment due"
     * multi-select on the Add Payment form via AJAX.
     *
     */
    public function outstandingYears($user_id)
    {
        $user = SyndicateUser::findOrFail($user_id);

        return response()->json(SubscriptionsHelper::outstandingYearsFor($user));
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
            'receipt' => 'required|string|unique:' . $page_info['table_name'] . ',receipt',
            'years' => 'required|array|min:1',
        ]);

        $user = SyndicateUser::findOrFail($request->user_id);

        $outstandingYears = array_keys(SubscriptionsHelper::outstandingYearsFor($user));
        $joinYear = (int) $user->created_at->format('Y');
        $max = max($request->years);
        foreach ($outstandingYears as $year) {
            if (!in_array($year, $request->years) && $year < $max && $year != $joinYear) {
                return redirect()->back()->withInput()->with('error', 'You need to pay the past years first.');
            }
        }

        foreach ($request->years as $year) {
            $rate = SyndicatePayment::where('payment_year', $year)->first();

            MemberPayment::create([
                'user_id' => $user->id,
                'admin_id' => Auth::guard('admin')->id(),
                'amount' => $rate ? $rate->payment_amount : 0,
                'ue_year' => $year,
                'status' => 1,
                'publish_status' => 1,
                'receipt' => $request->receipt,
            ]);
        }

        return redirect()->route('admin.' . $page_info['link'] . '.index')->withStatus('Payment successfully created.');
    }

    /**
     * Remove the specified row from the database
     *
     */
    public function destroy($id)
    {
        MemberPayment::findOrFail($id)->delete();

        return redirect()->back()->withStatus('Payment successfully deleted.');
    }

    /**
     * Rows for export, flattened to human-readable columns, respecting the current filters.
     *
     */
    private function exportRows(Request $request)
    {
        return $this->filteredQuery($request)->get()->map(function ($row) {
            return [
                'Full Name' => $row->user ? trim($row->user->first_name . ' ' . $row->user->fathers_name . ' ' . $row->user->last_name) : '',
                'Email' => $row->user ? $row->user->email : '',
                'Company' => $row->user ? $row->user->company : '',
                'Amount' => $row->amount,
                'Year' => $row->ue_year,
                'Receipt Number' => $row->receipt,
                'Date Added' => $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
            ];
        });
    }

    /**
     * Export the listing as CSV
     *
     */
    public function exportCsv(Request $request)
    {
        $rows = $this->exportRows($request);

        return new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $rows->isEmpty() ? [] : array_keys($rows->first()));
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="members-payment.csv"',
        ]);
    }

    /**
     * Export the listing as Excel (HTML table served as .xls, opens natively in Excel)
     *
     */
    public function exportExcel(Request $request)
    {
        $rows = $this->exportRows($request);

        $html = view('cms.pages.members-payment.export-table', compact('rows'))->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="members-payment.xls"',
        ]);
    }

    /**
     * Export the listing as PDF
     *
     */
    public function exportPdf(Request $request)
    {
        $rows = $this->exportRows($request);

        $pdf = Pdf::loadView('cms.pages.members-payment.export-table', compact('rows'))->setPaper('a4', 'landscape');

        return $pdf->download('members-payment.pdf');
    }

}
