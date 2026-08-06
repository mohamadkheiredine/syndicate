<?php

namespace App\Helpers;

use App\Models\MemberPayment;
use App\Models\SyndicatePayment;
use App\Models\SyndicateUser;

class SubscriptionsHelper
{
    /**
     * Every year from the syndicate user's join year onward that has a configured rate in
     * syndicate_payments and hasn't been paid yet — matches the old CMS's payments() endpoint
     * exactly, including having no upper bound: a year already configured in the rate card but
     * still in the future (e.g. next year's dues already set up) shows as payable in advance,
     * same as the old site always allowed. The join year itself IS payable/outstanding here —
     * it's only exempt from the "must pay past years first" blocking rule in
     * MemberPaymentController@store, not from being owed. Shared by the Add Payment dropdown
     * and that blocking check so both always agree on what's outstanding.
     *
     */
    public static function outstandingYearsFor(SyndicateUser $user)
    {
        $creationYear = (int) $user->created_at->format('Y');

        $paidYears = MemberPayment::where('user_id', $user->id)->pluck('ue_year')->toArray();

        $outstanding = SyndicatePayment::where('payment_year', '>=', $creationYear)
            ->whereNotIn('payment_year', $paidYears)
            ->pluck('payment_amount', 'payment_year')
            ->toArray();

        ksort($outstanding);

        return $outstanding;
    }
}
