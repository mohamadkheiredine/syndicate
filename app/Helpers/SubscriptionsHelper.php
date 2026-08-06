<?php

namespace App\Helpers;

use App\Models\MemberPayment;
use App\Models\SyndicatePayment;
use App\Models\SyndicateUser;

class SubscriptionsHelper
{
    /**
     * Every year the syndicate user is expected to have paid for (their join year through
     * the current year) that they haven't paid yet. The join year itself IS payable/
     * outstanding here (matches the old CMS's payments() endpoint) — it's only exempt from
     * the "must pay past years first" blocking rule in MemberPaymentController@store, not
     * from being owed. Shared by the Add Payment dropdown and the Reporting totals so both
     * always agree on what's outstanding.
     *
     */
    public static function outstandingYearsFor(SyndicateUser $user)
    {
        $creationYear = (int) $user->created_at->format('Y');
        $currentYear = (int) date('Y');

        $paidYears = MemberPayment::where('user_id', $user->id)->pluck('ue_year')->toArray();

        $rates = SyndicatePayment::whereBetween('payment_year', [$creationYear, $currentYear])
            ->pluck('payment_amount', 'payment_year');

        $outstanding = [];
        for ($year = $creationYear; $year <= $currentYear; $year++) {
            if (in_array($year, $paidYears)) {
                continue;
            }
            $outstanding[$year] = $rates[$year] ?? 0;
        }

        return $outstanding;
    }
}
