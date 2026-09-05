<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PushInbox;
use App\Models\UserPush;
use App\Models\UserPushInbox;
use App\Models\UsersPush;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PushController extends Controller
{

	public function set_player_id(Request $request)
	{
		$user = Auth::guard('api')->user();

		$validator = Validator::make($request->all(), [
			'player_id' => 'required'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		if($user_push = UsersPush::where('user_id', $user->id)->first()){
			$user_push->player_id = $request->player_id;
			$user_push->save();
		} else {
			UsersPush::create([
				'user_id' => $user->id,
				'player_id' => $request->player_id
			]);
		}

		// Exact old typo, preserved deliberately.
		return parent::return_success(['message' => 'Submited successfully!']);
	}


	public function syndicateSetUserPush(Request $request)
	{

		if(!$request->bearerToken()){

			$user_push = UserPush::create([
				'registration_id' => $request->push_token
			]);

			return response(['id' => $user_push->id], 200);
		}

		if(!$user = Auth::guard('api')->user()){
			return parent::return_error('Invalid Access Token', 101, 'messages.invalid_access_token');
		}

		if(!$request->has('push_token')){
			return parent::return_error('Push Token required!', 400, 'messages.missing_parameter');
		}
		if(!$request->has('push_id')){
			return parent::return_error('Push ID required!', 400, 'messages.missing_parameter');
		}

		$user_push = UserPush::find($request->push_id);

		if($user_push){
			$user_push->users_id = $user->id;
			$user_push->registration_id = $request->push_token;
			$user_push->save();
		}else{
			$user_push = UserPush::create([
				'registration_id' => $request->push_token,
				'users_id' => $user->id
			]);
		}

		return response(['id' => $user_push->id], 200);
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
