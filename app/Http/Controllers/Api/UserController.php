<?php

namespace App\Http\Controllers\Api;
use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Models\UserPush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	/*
	* GET USER PROFILE
	*/
	public function profile()
	{
		// Get User
		$user = Auth::guard('api')->user();

		return parent::return_success($user);
	}

	/*
	* UPDATE USER PROFILE
	*/
	public function update(Request $request)
	{
		// Get User
		$user = Auth::guard('api')->user();

		// Check if the user exists
		$validator = Validator::make($request->all(), [
			'image' => 'mimes:png,jpg,jpeg',
			'first_name' => 'nullable|string',
			'last_name' => 'nullable|string',
			'full_name' => 'nullable|string',
			'email' => 'nullable|email|unique:users,email,'.$user->id,
			'dob' => 'nullable|date',
			'country_code' => 'nullable|string'
        ]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Check if the image exists
        $image_path = $user->getAttributes()['image'];
        if($request->image){
            $image_path = FilesHelper::storeFile('users', $request->image);
        }

        // Calculate age if dob is provided
        $age = null;
        if($request->dob) {
            $age = \Carbon\Carbon::parse($request->dob)->age;
        }

        // Update user info
		$user->update([
			'image' => $image_path,
			'first_name' => $request->first_name ?? $user->first_name,
			'last_name' => $request->last_name ?? $user->last_name,
			'full_name' => $request->full_name ?? $user->full_name,
			'email' => $request->email ?? $user->email,
			'country_code' => $request->country_code ?? $user->country_code,
			'dob' => $request->dob ?? $user->dob,
			'age' => $age ?? $user->age
		]);

		return parent::return_success($user);
	}

	/*
	* DELETE USER ACCOUNT VIA PASSWORD
	*/
	public function delete_account_password(Request $request)
	{
		// Check if the user exists
		$validator = Validator::make($request->all(), [
			'password' => 'required'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Get User
		$user = Auth::guard('api')->user();

		if(Hash::check($request->password, $user->password)) {
			$user->delete();
			return parent::return_success(['message' => 'Account successfully deleted!']);
		} else {
			return parent::return_error('Wrong Password', 400, 'Wrong password!');
		}
	}

	/*
	* DELETE USER ACCOUNT VIA PIN
	*/
	public function delete_account_pin()
	{
		// Get User
		$user = Auth::guard('api')->user();

		// Generate pin / deleteID
		$pin = rand(1000,9999);
		$deleteID = Str::random(32);

		$user->update([
			'pin' => $pin,
			'deleteID' => $deleteID
		]);

        // Send Pin

		$response = [
            'pin' => $pin, // To be removed
            'deleteID' => $deleteID
        ];

        return parent::return_success($response);
    }

	/*
	* DELETE USER ACCOUNT AFTER PIN
	*/
	public function delete_account(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'pin' => 'required|min:4|max:4',
			'deleteID' => 'required'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Get User
		$user = Auth::guard('api')->user();

		if($user->pin == $request->pin && $user->deleteID == $request->deleteID){
			$user->update([
				'pin' => null,
				'deleteID' => null
			]);

			$user->delete();
			return parent::return_success(['message' => 'Account successfully deleted!']);
		}

		return parent::return_error('Something went wrong!', 400, 'Something went wrong!');
	}

	/*
	* USER LOGOUT
	*/
	public function logout()
	{
		// Get User
		$user = Auth::guard('api')->user();

		// Remove user player_id when logout
		if($user_push = UserPush::where('user_id', $user->id)->first()){
			$user_push->delete();
		}

		// Revoke Token
		$user->tokens()->delete();

		return parent::return_success(['message' => 'User logged out!']);
	}
}
