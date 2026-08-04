<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
	public function verify(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'pin' => 'required|min:6|max:6',
            'verificationID' => 'required'
        ]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

        $user_exists = User::where('pin', $request->pin)->where('verificationID', $request->verificationID)->first();

        if($user_exists){
            // Revoke All User Tokens
            $user_exists->tokens()->delete();

            // Update User
            $user_exists->update([
                'pin' => null,
                'verificationID' => null
            ]);

            $response = [
                'user' => $user_exists,
                'accessToken' => $user_exists->createToken('authToken')->plainTextToken
            ];

            return parent::return_success($response);
        }

        return parent::return_error('User not found', 400, trans('messages.unauthorised_access'));
    }

}
