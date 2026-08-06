<?php

namespace App\Http\Controllers\Cms\Base;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\MemberPayment;
use App\Models\SyndicatePayment;
use App\Models\SyndicateUser;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:reports-view', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $total_income = Accounting::where('type', 'income')->sum('amount');
        $total_expense = Accounting::where('type', 'expenses')->sum('amount');
        $total_unpaid_amount = $this->calculateTotalUnpaid();

        $selected_year = $request->get('year', 'all');

        $years = MemberPayment::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $paymentsQuery = MemberPayment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('year', 'month');

        if ($request->filled('year')) {
            $paymentsQuery->whereYear('created_at', $request->year);
        }

        $payment_years = [];
        foreach ($paymentsQuery->get() as $row) {
            $payment_years[$row->year][$row->month] = $row->total;
        }
        krsort($payment_years);
        foreach ($payment_years as &$months) {
            ksort($months);
        }
        unset($months);

        return view('cms.base.reports', compact(
            'total_income', 'total_expense', 'total_unpaid_amount', 'selected_year', 'years', 'payment_years'
        ));
    }

    /**
     * Total amount still owed across every syndicate user — same "outstanding year" rule as
     * SubscriptionsHelper::outstandingYearsFor(), but computed as a handful of aggregate
     * queries instead of per-user lookups (the old CMS ran a query per user, per year, which
     * is why it had to raise max_execution_time to an hour).
     *
     */
    private function calculateTotalUnpaid()
    {
        $currentYear = (int) date('Y');

        $rates = SyndicatePayment::pluck('payment_amount', 'payment_year');

        $paidYearsByUser = MemberPayment::select('user_id', 'ue_year')
            ->distinct()
            ->get()
            ->groupBy('user_id')
            ->map(fn ($rows) => $rows->pluck('ue_year')->toArray());

        $total = 0;
        foreach (SyndicateUser::select(['id', 'created_at'])->get() as $user) {
            $joinYear = (int) $user->created_at->format('Y');
            $paidYears = $paidYearsByUser->get($user->id, []);

            for ($year = $joinYear; $year <= $currentYear; $year++) {
                if (in_array($year, $paidYears)) {
                    continue;
                }
                $total += $rates[$year] ?? 0;
            }
        }

        return $total;
    }

}
