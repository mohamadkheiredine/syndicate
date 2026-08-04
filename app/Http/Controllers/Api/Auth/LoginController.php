<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required_without:mobile_number|email|nullable',
			'mobile_number' => 'required_without:email|nullable'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Find user by email or mobile_number
		$user_exists = null;
		if($request->email){
			$user_exists = User::where('email', $request->email)->first();
		} else if($request->mobile_number){
			$user_exists = User::where('mobile_number', $request->mobile_number)->first();
		}

        // Generate pin / verificationID
        $pin = $this->generatePin();
        $verificationID = $this->generateVerificationID();

        if(!$user_exists){
            // Create User
            User::create([
            	'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'pin' => $pin,
                'verificationID' => $verificationID
            ]);
        } else {
            // Update User
            $user_exists->update([
                'pin' => $pin,
                'verificationID' => $verificationID
            ]);
        }

        // Send Pin

        $response = [
            'verificationID' => $verificationID
        ];

        // Only include PIN in response if not in production
        if(config('app.env') !== 'production'){
            $response['pin'] = $pin;
        }

        return parent::return_success($response);
    }

}
