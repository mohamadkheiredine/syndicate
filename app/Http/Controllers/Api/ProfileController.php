<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCode;
use App\Models\MemberPayment;
use App\Models\SyndicateUser;
use App\Models\SyndicatePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    private function formatUser(SyndicateUser $user)
    {
        return $user->only([
            'id', 'has_id', 'first_name', 'last_name', 'registration_date',
            'mobile_number', 'kaza', 'city', 'street', 'building', 'floor',
            'registration_fees', 'email', 'department', 'unit', 'date_employment',
            'blood_type', 'photo', 'created_at', 'deleted_at',
        ]);
    }

    /**
     * Syndicate member profile - ports the old CMS's ProfileController@get.
     *
     */
    public function syndicateProfile()
    {
        $user = Auth::guard('sanctum')->user();

        return parent::return_success($this->formatUser($user));
    }

    /**
     * Update profile - ports the old CMS's ProfileController@set (same
     * required fields: mobile_number, first_name, last_name, blood_type,
     * optional image), photo storage routed through FilesHelper instead of
     * a raw public_path() move.
     *
     */
    public function syndicateProfileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($validator->fails()) {
            return parent::return_error('Missing parameter(s)', 401, $validator->messages()->all()[0]);
        }

        $user = Auth::guard('sanctum')->user();

        $photo = $user->getAttributes()['photo'];
        if ($request->hasFile('image')) {
            if ($photo) {
                FilesHelper::deleteFileByName('syndicate-users', $photo);
            }
            $photo = FilesHelper::storeFile('syndicate-users', $request->file('image'));
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_number' => $request->mobile_number,
            'blood_type' => $request->blood_type,
            'photo' => $photo,
        ]);

        return parent::return_success($this->formatUser($user->fresh()));
    }

    /**
     * Payment/expenses summary - ports the old CMS's
     * ProfileController@get_expences. Reuses the same due-amount formula
     * already established in MemberPaymentController::calculateDueAmount()
     * (sum paid per year vs the configured rate per year) rather than the
     * old mobile API's own version, which filtered user_expences by
     * `status = 1` as an integer - on this table status is an
     * enum('0','1') column, so an integer comparison silently matches the
     * enum's internal index instead of the value (the same bug class this
     * project has hit and fixed before). The already-verified CMS version
     * doesn't filter by status at all, so that's what this reuses.
     *
     */
    public function syndicateProfileExpenses()
    {
        $user = Auth::guard('sanctum')->user();

        $creationYear = (int) $user->created_at->format('Y');
        $currentYear = (int) date('Y');

        $paidByYear = MemberPayment::where('user_id', $user->id)
            ->where('ue_year', '>=', $creationYear)
            ->selectRaw('ue_year, SUM(amount) as total')
            ->groupBy('ue_year')
            ->pluck('total', 'ue_year');

        $rates = SyndicatePayment::whereBetween('payment_year', [$creationYear, $currentYear])
            ->pluck('payment_amount', 'payment_year');

        $duePayments = [];
        for ($year = $creationYear; $year <= $currentYear; $year++) {
            $expected = $rates[$year] ?? 0;
            $paid = $paidByYear[$year] ?? 0;
            $due = max($expected - $paid, 0);
            if ($due > 0) {
                $duePayments[] = ['year' => $year, 'payment' => $due];
            }
        }

        $lastPayment = MemberPayment::where('user_id', $user->id)
            ->orderByDesc('ue_year')
            ->orderByDesc('created_at')
            ->first();

        return parent::return_success([
            'due_payments' => $duePayments,
            'message' => null,
            'last_payment' => [
                'year' => $lastPayment->ue_year ?? 0,
                'amount' => $lastPayment->amount ?? 0,
            ],
        ]);
    }

    /**
     * Change password - ports the old CMS's ProfileController@changePassword.
     * Old password check reuses verifyPassword() (bcrypt-first, legacy-MD5
     * fallback) instead of a raw md5() comparison.
     *
     */
    public function syndicateChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return parent::return_error('Missing parameter(s)', 401, $validator->messages()->all()[0]);
        }

        $user = Auth::guard('sanctum')->user();

        if (!$user->verifyPassword($request->old_password)) {
            return parent::return_error('Wrong Password!', 405, 'messages.wrong_password');
        }

        $user->password = $request->new_password;
        $user->save();

        return parent::return_success($this->formatUser($user));
    }

    /**
     * Send a password reset code by email - ports the old CMS's
     * ProfileController@sendPasswordCode.
     *
     */
    public function syndicateForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return parent::return_error('Missing parameter(s)', 401, $validator->messages()->all()[0]);
        }

        $user = SyndicateUser::where('email', $request->email)->first();

        if (!$user) {
            return parent::return_error('User does not exist', 404, 'messages.invalid_credentials');
        }

        $code = mt_rand(111111, 999999);
        $user->reset_code = $code;
        $user->save();

        try {
            Mail::to($user->email)->send(new PasswordResetCode($user->first_name, $code));
        } catch (\Throwable $e) {
            Log::warning('Password reset code email failed to send: ' . $e->getMessage());
        }

        return parent::return_success(['status' => 'An email has been sent with your reset code.']);
    }

    /**
     * Reset password using the emailed code - ports the old CMS's
     * ProfileController@reset_password.
     *
     */
    public function syndicateResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reset_code' => 'required',
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return parent::return_error('Missing parameter(s)', 401, $validator->messages()->all()[0]);
        }

        $user = SyndicateUser::where('reset_code', $request->reset_code)->first();

        if (!$user) {
            return parent::return_error('Wrong Code', 406, 'messages.invalid_code');
        }

        $user->password = $request->new_password;
        // NOT NULL with no DB default - clear to '' (same pattern used
        // elsewhere on this model), not null.
        $user->reset_code = '';
        $user->save();

        return parent::return_success(['status' => 'Your password has been changed successfully.']);
    }
}
