<?php

namespace App\Http\Controllers\Api;
use App;
use App\Helpers\OneSignalHelper;
use App\Http\Controllers\Controller;
use App\Models\PushInbox;
use App\Models\UserPush;
use App\Models\UserPushInbox;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PushController extends Controller
{
	/*
	* Set Player ID
	*/
	public function set_player_id(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'player_id' => 'required'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Player ID Validation
		if(!OneSignalHelper::validate_player_id($request->player_id)){
			return parent::return_error('Invalid Player ID', 401, 'Invalid parameters');
		}

		// Get User
		$user = Auth::guard('api')->user();

		// Get OneSignal user details
		$user_onesignal = OneSignalHelper::oneSignalViewUser($request->player_id);

		if($user_push = UserPush::where('user_id', $user->id)->first()){
			// Store the new player_id
			$user_push->update([
				'player_id' => $request->player_id,
				'device_model' => isset($user_onesignal['error']) ? null : $user_onesignal['device_model'],
				'device_type' => isset($user_onesignal['error']) ? null : $user_onesignal['device_type'],
				'identifier' => isset($user_onesignal['error']) ? null : $user_onesignal['identifier'],
				'response' => isset($user_onesignal['error']) ? $user_onesignal['error'] : json_encode($user_onesignal),
				'language' => App::getLocale()
			]);
		} else {
			// Create new user player_id
			UserPush::create([
				'user_id' => $user->id,
				'player_id' => $request->player_id,
				'device_model' => isset($user_onesignal['error']) ? null : $user_onesignal['device_model'],
				'device_type' => isset($user_onesignal['error']) ? null : $user_onesignal['device_type'],
				'identifier' => isset($user_onesignal['error']) ? null : $user_onesignal['identifier'],
				'response' => isset($user_onesignal['error']) ? $user_onesignal['error'] : json_encode($user_onesignal),
				'language' => App::getLocale()
			]);
		}

		return parent::return_success(['message' => 'Your request has been submitted successfully']);
	}

	/*
	* Get Inbox
	*/
	public function inbox()
	{
		// Get user
		$user = Auth::guard('api')->user();

		$inbox = PushInbox::select([
			'id',
			'subject',
			'message',
			'image',
			'created_at'
		])->where(function($query) use ($user){
			$query->where('user_id', $user->id)->where('type', 'single');
		})->orWhere('type', 'bulk')
		->orderBy('id', 'desc')
		->get()
		->map(function($value, $key) use ($user) {
			UserPushInbox::updateOrCreate([
				'user_id' => $user->id,
				'push_inbox_id' => $value->id
			]);
			return $value;
		})->values();

		return parent::return_success($inbox);
	}

	/*
	* Get Unread Inbox Count
	*/
	public function unread_count()
	{
		// Get user
		$user = Auth::guard('api')->user();

		$unread_count = PushInbox::doesnthave('PushRead')
		->where(function($query) use($user) {
			$query->where(function($query) use ($user){
				$query->where('user_id', $user->id)->where('type', 'single');
			})->orWhere('type', 'bulk');
		})->count();

		$data['unread_count'] = $unread_count;

		return parent::return_success($data);
	}
}
