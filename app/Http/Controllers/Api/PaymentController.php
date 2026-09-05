<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MemberPayment;
use App\Models\SyndicatePayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{
    public function view(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$request->has('currency')) {
            return parent::return_error('Currency is required!', 400, 'invalid input parameter');
        }

        $creationYear = (int) $user->created_at->format('Y');
        $currentYear = (int) date('Y');

        $rates = SyndicatePayment::whereBetween('payment_year', [$creationYear, $currentYear])
            ->pluck('payment_amount', 'payment_year');

        $due_year = null;
        $due_amount = null;
        for ($year = $creationYear; $year <= $currentYear; $year++) {
            $hasPayment = MemberPayment::where('ue_year', $year)->where('user_id', $user->id)->exists();
            if (!$hasPayment && $year == (int) $request->year) {
                $due_year = $year;
                $due_amount = $rates[$year] ?? 0;
            }
        }

        // Payment Gateway Setup
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'amount' => $due_amount,
            'year' => $due_year,
        ]);

        if ($request->currency == 'USD') {
            $data = [
                'txtAmount' => $due_amount,
                'txtCurrency' => '840',
                'txtIndex' => 'SYN_' . $transaction->id,
                'txtMerchNum' => env('txtMerchNum'),
                'txthttp' => route('payment-return'),
            ];
        } elseif ($request->currency == 'LBP') {
            $data = [
                'txtAmount' => $due_amount * 1500,
                'txtCurrency' => '422',
                'txtIndex' => 'SYN_' . $transaction->id,
                'txtMerchNum' => env('txtMerchNum'),
                'txthttp' => route('payment-return'),
            ];
        } else {
            return parent::return_error('Currency is required!', 400, 'invalid input parameter');
        }

        $shaString = '';
        foreach ($data as $value) {
            $shaString .= "$value";
        }

        $signature = hash('sha256', $shaString . env('sha_key'));

        return view('webview.payment.view', compact('data', 'signature', 'user'));
    }

    public function payment_return(Request $request)
    {
       
        $transaction_id = explode('SYN_', $request->txtIndex)[1];
        $transaction = Transaction::find($transaction_id);

        if ($request->RespVal == 1) {
            $transaction->status = 1;
            $transaction->save();

            MemberPayment::create([
                'admin_id' => 0,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount,
                'ue_year' => $transaction->year,
                'status' => '1',
                'publish_status' => '1',
                'receipt' => 'SYN_' . $transaction->id,
            ]);

            $result['success'] = 1;
            $result['message'] = $request->RespMsg . ' Order ID: SYN_' . $transaction->id . ' | Order Amount: ' . $transaction->amount;
        } else {
            $result['success'] = 0;
            $result['message'] = $request->RespMsg . ' Order ID: SYN_' . $transaction->id . ' | Order Amount: ' . $transaction->amount;
        }
        return redirect()->route('payment-status', ['success=' . $result['success'], 'message=' . $result['message']]);
    }

    // Do not remove this function - matches old's real empty stub exactly.
    public function payment_status()
    {
    }
}
