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


    public function syndicateProfile(Request $request)
    {
        $user = Auth::guard('api')->user();

        return parent::return_success($this->formatUser($user));
    }


    public function syndicateProfileUpdate(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$request->filled('mobile_number')) {
            return parent::return_error('Missing Mobile Number', 401, 'messages.missing_parameter');
        }
        if (!$request->filled('first_name')) {
            return parent::return_error('Missing First Name', 401, 'messages.missing_parameter');
        }
        if (!$request->filled('last_name')) {
            return parent::return_error('Missing Last Name', 401, 'messages.missing_parameter');
        }
        if (!$request->filled('blood_type')) {
            return parent::return_error('Missing Blood Type', 401, 'messages.missing_parameter');
        }

        $photo = $user->getAttributes()['photo'];
        if ($request->hasFile('image')) {
            if ($photo) {
                FilesHelper::deleteFileByName('user', $photo);
            }
            $photo = FilesHelper::storeFile('user', $request->file('image'));
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


    public function syndicateProfileExpenses(Request $request)
    {
        $user = Auth::guard('api')->user();

        $creationYear = (int) $user->created_at->format('Y');
        $currentYear = (int) date('Y');

        $rates = SyndicatePayment::pluck('payment_amount', 'payment_year');

        $paidYears = MemberPayment::where('user_id', $user->id)->pluck('ue_year')->toArray();

        $result = [];

        for ($year = $creationYear; $year <= $currentYear; $year++) {
            if (!in_array($year, $paidYears)) {
                $result['due_payments'][] = [
                    'year' => $year,
                    'payment' => $rates[$year] ?? 0,
                ];
            }
        }

        $futurePayments = SyndicatePayment::where('payment_year', '>', $creationYear + 1)
            ->whereNotIn('payment_year', $paidYears)
            ->get();

        foreach ($futurePayments as $payment) {
            $result['future_payments'][] = [
                'year' => $payment->payment_year,
                'payment' => $payment->payment_amount,
            ];
        }

        $lastPayment = MemberPayment::where('user_id', $user->id)
            ->orderBy('ue_year')
            ->orderBy('created_at')
            ->get()
            ->last();

        $lastPaymentYear = $lastPayment->ue_year ?? 0;
        $lastPaymentAmount = $lastPayment->amount ?? 0;

        $result['message'] = ($lastPaymentYear == $currentYear)
            ? 'You paid all your expences. Thank you.'
            : null;
        $result['last_payment'] = [
            'year' => $lastPaymentYear,
            'amount' => $lastPaymentAmount,
        ];

        return parent::return_success($result);
    }


    public function syndicateChangePassword(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$request->filled('old_password')) {
            return parent::return_error('Missing Old Password', 401, 'messages.missing_parameter');
        }
        if (!$request->filled('new_password')) {
            return parent::return_error('Missing New Password', 401, 'messages.missing_parameter');
        }

        if (!$user->verifyPassword($request->old_password)) {
            return parent::return_error('Wrong Password!', 405, 'messages.wrong_password');
        }

        $user->password = $request->new_password;
        $user->save();

        return parent::return_success($this->formatUser($user));
    }


    public function syndicateForgotPassword(Request $request)
    {
        if (!$request->filled('email')) {
            return parent::return_error('Missing Email', 401, 'messages.missing_parameter');
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return parent::return_error('Invalid Email', 402, 'messages.invalid_parameter');
        }

        $user = SyndicateUser::where('email', $request->email)->first();

        if (!$user) {
            return parent::return_error('User does not exist', 404, 'messages.invalid_credentials');
        }

        $code = mt_rand(111111, 999999);
        $user->reset_code = $code;
        $user->save();

        try {
            Mail::to($user->email)->send(new PasswordResetCode($user->first_name, $user->last_name, $user->email, $code));
        } catch (\Throwable $e) {
            Log::warning('Password reset code email failed to send: ' . $e->getMessage());
        }

        return parent::return_success(['status' => __('messages.email_sent_success')]);
    }


    public function syndicateResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reset_code' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return parent::return_error($validator->errors()->first(), 401, 'Invalid Parameters');
        }

        $user = SyndicateUser::where('reset_code', $request->reset_code)->first();

        if (!$user) {
            return parent::return_error('Wrong Code', 406, 'messages.invalid_code');
        }

        $user->password = $request->new_password;

        $user->reset_code = '';
        $user->save();

        return parent::return_success(['status' => __('messages.password_changed_success')]);
    }
}
