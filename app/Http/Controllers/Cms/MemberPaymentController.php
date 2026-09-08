<?php

namespace App\Http\Controllers\Cms;

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
    function __construct()
    {
        $this->middleware('permission:members_payment-view', ['only' => ['index']]);
        $this->middleware('permission:members_payment-create', ['only' => ['create', 'store', 'outstandingYears']]);
        $this->middleware('permission:members_payment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:members_payment-export', ['only' => ['exportCsv', 'exportExcel', 'exportPdf']]);
    }

    public function page_info()
    {
        return [
            'title' => 'Members Payment',
            'link' => 'members-payment',
            'table_name' => 'user_expences',
        ];
    }

    /**
     * Total amount a syndicate user still owes - ported from old's
     * MembersController@index.
     *
     * Old iterates $user->expences, a relation scoped `where('status', 1)`.
     * `status` is enum('0','1'); comparing it to the integer 1 matches the
     * enum INDEX (1 -> '0'), so it matches NO rows here - old's Total Due
     * therefore always treats the member as having paid nothing. Kept
     * exactly as old, including abs(rate - paid) rather than a floored
     * shortfall.
     */
    private function calculateDueAmount($userId)
    {
        $user = SyndicateUser::find($userId);
        if (!$user) {
            return null;
        }

        $creationYear = (int) $user->created_at->format('Y');
        $currentYear = (int) date('Y');

        $listYears = range($creationYear, $currentYear);

        $totalPaidPerYear = [];
        $paidYears = [];
        $expences = MemberPayment::where('user_id', $userId)
            ->where('status', 1)
            ->orderBy('ue_year')
            ->orderBy('created_at')
            ->get();

        foreach ($expences as $expence) {
            if ($expence->ue_year >= $creationYear) {
                $paidYears[] = $expence->ue_year;
                $totalPaidPerYear[$expence->ue_year] = ($totalPaidPerYear[$expence->ue_year] ?? 0) + $expence->amount;
            }
        }
        $paidYears = array_unique($paidYears);

        $rates = SyndicatePayment::pluck('payment_amount', 'payment_year');

        $amountDue = 0;
        foreach ($totalPaidPerYear as $year => $totalPaid) {
            $amountDue += abs(($rates[$year] ?? 0) - $totalPaid);
        }
        foreach ($listYears as $year) {
            if (!in_array($year, $paidYears) && isset($rates[$year])) {
                $amountDue += $rates[$year];
            }
        }

        return $amountDue;
    }

    private function filteredQuery(Request $request)
    {
        $query = MemberPayment::with('user');

        if ($request->filled('year')) {
            // Old: when a year is picked, show each member's LATEST payment
            // from that year onward - the member filter is ignored in this
            // branch, exactly as old's MembersController@index.
            $year = (int) $request->year;
            $query->whereRaw(
                'ue_year = (select max(ue_year) from user_expences as secondary '
                . 'where secondary.user_id = user_expences.user_id and secondary.ue_year >= ?)',
                [$year]
            );
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
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
     * Years (and their rate) offered on the Add Payment form's "Payment
     * due" multi-select - ported from old's MembersController@payments.
     *
     * Old builds "paid years" from $user->expences (the `where('status', 1)`
     * relation - 0 rows here), so it effectively lists EVERY rate-card year
     * from the member's join year onward, including ones already paid.
     */
    public function outstandingYears($user_id)
    {
        $user = SyndicateUser::findOrFail($user_id);

        $creationYear = (int) $user->created_at->format('Y');

        $paidYears = MemberPayment::where('user_id', $user->id)
            ->where('status', 1)
            ->pluck('ue_year')
            ->toArray();

        $payments = SyndicatePayment::where('payment_year', '>=', $creationYear)
            ->whereNotIn('payment_year', $paidYears)
            ->pluck('payment_amount', 'payment_year')
            ->toArray();

        ksort($payments);

        return response()->json($payments);
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
            'receipt' => 'required|string',
            'years' => 'required|array|min:1',
        ]);

        // Old: manual receipt-taken check, redirect back with this exact text.
        if (MemberPayment::where('receipt', $request->receipt)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Receipt Number already taken!');
        }

        $user = SyndicateUser::findOrFail($request->user_id);

        // Old: years from join year to the current year that have NO payment
        // row at all. The join year is exempt from the blocking check.
        $creationYear = (int) $user->created_at->format('Y');
        $currentYear = (int) date('Y');

        $yearsMustPay = [];
        for ($year = $creationYear; $year <= $currentYear; $year++) {
            if (!MemberPayment::where('ue_year', $year)->where('user_id', $user->id)->exists()) {
                $yearsMustPay[] = $year;
            }
        }

        $max = max($request->years);
        foreach ($yearsMustPay as $year) {
            if (!in_array($year, $request->years) && $year < $max && $year != $creationYear) {
                return redirect()->back()->withInput()->with('error', 'You need to pay the past years!');
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
