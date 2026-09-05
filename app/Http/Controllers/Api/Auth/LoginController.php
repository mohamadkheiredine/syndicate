<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SyndicateUser;
use App\Models\UserPush;
use App\Helpers\FilesHelper;
use App\Mail\RegistrationActivation;
use Illuminate\Support\Str;
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

    public function syndicateLogin(Request $request)
    {
        app()->setLocale('en');

        if (!$request->filled('email')) {
            return parent::return_error('Missing Email', 401, 'messages.missing_parameter');
        }
        if (!$request->filled('password')) {
            return parent::return_error('Missing Password', 401, 'messages.missing_parameter');
        }

        $user = SyndicateUser::where('email', $request->email)->first();

        if (!$user || !$user->verifyPassword($request->password)) {
            return parent::return_error('User does not exist', 404, 'messages.invalid_credentials');
        }

        $user->tokens()->delete();

        $response = $user->toArray();
        $response['token'] = $user->createToken('mobile')->plainTextToken;

        return parent::return_success($response);
    }


    public function syndicateRegister(Request $request)
    {
        app()->setLocale('en');

        $allowed_domains = ['alfamobile.com.lb', 'touch.com.lb'];

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => [
                'bail',
                'required',
                'email:filter', // old uses PHP filter_var, not RFC/DNS
                function ($attribute, $value, $fail) use ($allowed_domains) {
                    $domain = substr(strrchr((string) $value, '@'), 1);
                    if (!in_array($domain, $allowed_domains, true)) {
                        $fail('Invalid Email Domain');
                    }
                },
            ],
            'blood_type' => 'required',
            'password'   => 'required',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $map = [
                ['mobile_number', 'Required', 'Missing Mobile Number', 401, 'messages.missing_parameter'],
                ['first_name',    'Required', 'Missing First Name',    401, 'messages.missing_parameter'],
                ['last_name',     'Required', 'Missing Last Name',     401, 'messages.missing_parameter'],
                ['email',         'Required', 'Missing Email',         401, 'messages.missing_parameter'],
                ['blood_type',    'Required', 'Missing Blood Type',    401, 'messages.missing_parameter'],
                ['email',         'Email',    'Invalid Email',         402, 'messages.invalid_parameters'],
                ['email', \Illuminate\Validation\ClosureValidationRule::class, 'Invalid Email Domain', 402, 'messages.invalid_parameters'],
                ['password',      'Required', 'Missing Password',      401, 'messages.missing_parameter'],
            ];
            foreach ($map as [$field, $rule, $debugger, $code, $key]) {
                if (isset($failed[$field][$rule])) {
                    return parent::return_error($debugger, $code, $key);
                }
            }

            return parent::return_error(
                $validator->errors()->first(),
                401,
                'messages.missing_parameter'
            );
        }

        $user = SyndicateUser::where('email', $request->email)->first();
        if (!$user) {
            return parent::return_error('Syndicate User does not exist!', 403, 'messages.registration_error');
        }

        if ($user->getAttributes()['activation_code'] === 'activated') {
            return parent::return_error(
                __('messages.account_already_registered'),
                423,
                'messages.account_already_registered'
            );
        }

        $photo = $user->getAttributes()['photo'];
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $photo = FilesHelper::storeFile('user', $request->file('image'));
        }

        $activationCode = Str::random(64);

        $user->update([
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'mobile_number'   => $request->mobile_number,
            'blood_type'      => $request->blood_type,
            'password'        => $request->password,
            'photo'           => $photo,
            'activation_code' => $activationCode,
            'lang'            => 'en',
        ]);

        $activationUrl = url('/api/activate/' . $activationCode);
        try {
            Mail::to($user->email)->send(
                new RegistrationActivation($user->first_name, $user->last_name, $activationUrl)
            );
        } catch (\Throwable $e) {
            Log::warning('Syndicate registration activation email failed: ' . $e->getMessage());
        }

        return parent::return_success(['status' => __('messages.email_sent_success')]);
    }


    /**
     * Emailed activation link - ports old Api\LoginController@activate.
     * Old looked the member up by the random activation_code string it
     * emailed at registration (Users::whereActivationCode), then flipped
     * that column to the literal 'activated'. Same here. Returns the same
     * plain-text strings old did (not JSON) so an existing client that
     * just shows the body keeps working.
     *
     * NOTE: old also had a "User Already Activated" branch that fired when
     * the member had already obtained an access token. After activation
     * the code column no longer holds the emailed value, so a second click
     * of the same link now lands on 'User Not Found!' instead - the link
     * is single-use either way.
     */
    public function activate($token)
    {
        $user = $token !== 'activated'
            ? SyndicateUser::where('activation_code', $token)->first()
            : null;

        if (!$user) {
            return 'User Not Found!';
        }

        if ($user->getAttributes()['activation_code'] === 'activated') {
            return 'User Already Activated';
        }

        $user->update(['activation_code' => 'activated']);

        return 'Email Activated';
    }

    public function syndicateLogout(Request $request)
    {
        $user = Auth::guard('api')->user();

        $user->tokens()->delete();
        UserPush::where('users_id', $user->id)->delete();

        return parent::return_success(['status' => __('messages.logout_success')]);
    }

    public function syndicateDeleteAccount(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$request->filled('password')) {
            return parent::return_error('Missing Password', 401, 'messages.missing_parameter');
        }

        if (!$user->verifyPassword($request->password)) {
            return parent::return_error('Incorrect password', 401, 'Incorrect password');
        }

        $user->tokens()->delete();
        $user->delete();

        return parent::return_success(['status' => 'Account deleted']);
    }


}
