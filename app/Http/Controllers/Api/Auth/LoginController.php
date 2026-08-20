<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SyndicateUser;
use App\Helpers\FilesHelper;
use App\Mail\RegistrationActivation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

	/**
	 * Syndicate member login (email + password) - ports the old CMS's
	 * LoginController@login to SyndicateUser + Sanctum tokens instead of
	 * the old hand-rolled users_access table. Reuses verifyPassword()
	 * (bcrypt-first, legacy-MD5 fallback) already used by the website login.
	 *
	 */
	public function syndicateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return parent::return_error('Missing parameter(s)', 401, $validator->messages()->all()[0]);
        }

        $user = SyndicateUser::where('email', $request->email)->first();

        // Same generic error for "no user" and "bad password" - avoids leaking
        // which emails are registered (matches old CMS behavior).
        if (!$user || !$user->verifyPassword($request->password)) {
            return parent::return_error('Invalid credentials', 401, 'messages.invalid_credentials');
        }

        if ($user->activation_code !== 'activated') {
            return parent::return_error('Account locked', 410, 'messages.account_locked');
        }

        // Wipe any previous tokens - same intent as the old CMS deleting
        // prior users_access rows on every fresh login.
        $user->tokens()->delete();

        $response = $user->toArray();
        $response['token'] = $user->createToken('mobile')->plainTextToken;

        return parent::return_success($response);
    }


    /**
     * Syndicate member self-registration - ports the old CMS's
     * LoginController@register (same fields: mobile_number, first_name,
     * last_name, email restricted to the Alfa/Touch domains, blood_type,
     * password, optional photo).
     *
     * Unlike the old mobile API (which activated the account immediately),
     * this goes through the same activation_code email-gate as the website
     * registration - one single activation mechanism app-wide, so a mobile
     * signup can't bypass admin/activation-link approval. No token is
     * returned here; the client must call /login after the user activates.
     *
     */
    public function syndicateRegister(Request $request)
    {
        $domain_to_company = [
            'alfamobile.com.lb' => 'Alfa',
            'touch.com.lb' => 'Touch',
        ];

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string|max:255',
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                'max:255',
                'unique:syndicate_user,email',
                function ($attribute, $value, $fail) use ($domain_to_company) {
                    $domain = strtolower(substr(strrchr($value, '@'), 1));
                    if (!array_key_exists($domain, $domain_to_company)) {
                        $fail('Email domain is not allowed.');
                    }
                },
            ],
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'password'   => 'required|min:6',
            'image'      => 'nullable|image|max:5120',
        ]);

        if ($validator->fails()) {
            return parent::return_error('Missing parameter(s)', 401, $validator->messages()->all()[0]);
        }

        $domain = strtolower(substr(strrchr($request->email, '@'), 1));

        $photo = $request->hasFile('image') ? FilesHelper::storeFile('syndicate-users', $request->file('image')) : '';

        $user = SyndicateUser::create([
            'mobile_number' => $request->mobile_number,
            'first_name'    => $request->first_name,
            'fathers_name'  => '',
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'company'       => $domain_to_company[$domain],
            'blood_type'    => $request->blood_type,
            'password'      => $request->password,
            'photo'         => $photo,
            'lang'          => 'en',
        ]);

        $activationUrl = route('web.activate-account', ['user_id' => base64_encode($user->id)]);

        // A missing/invalid mail configuration should never break the
        // registration itself - the account above already exists regardless.
        try {
            Mail::to($user->email)->send(new RegistrationActivation($user->first_name, $activationUrl));
        } catch (\Throwable $e) {
            Log::warning('Registration activation email failed to send: ' . $e->getMessage());
        }

        return parent::return_success([
            'message' => 'You have completed the registration. Please activate your account through the activation link sent to your email.',
        ]);
    }

    /**
     * Revokes the caller's current Sanctum token and clears their push
     * tokens - the modern equivalent of the old CMS deleting the matching
     * users_access and user_push rows on logout.
     */
    public function syndicateLogout()
    {
        $user = Auth::guard('sanctum')->user();

        $user->currentAccessToken()->delete();
        $user->pushTokens()->delete();

        return parent::return_success(['status' => 'Logged out successfully']);
    }

}
