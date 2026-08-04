<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
	/*
	* GET ADDRESSES
	*/
	public function get()
	{
		// Get user
		$user = Auth::guard('api')->user();

		// Get all addresses
		$addresses = $user->Addresses;

		return parent::return_success($addresses);
	}

	/*
	* ADD ADDRESS
	*/
	public function add(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'label' => 'required|string',
			'country_code' => 'required|string',
			'mobile_number' => 'required|numeric',
			'city' => 'required|string',
			'street' => 'required|string',
			'building' => 'required|string',
			'apartment' => 'required|string',
			'instructions' => 'nullable|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'google_map' => 'required|string'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Get user
		$user = Auth::guard('api')->user();

		// Check if first address to set it as default or check if no default address exists then set it as default
		$is_default = 0;
		if(count($user->Addresses) == 0 && UserAddress::where('user_id', $user->id)->where('is_default', 1)->count() == 0){
			$is_default = 1;
		}

		// Create address
		$address = UserAddress::create([
			'user_id' => $user->id,
			'label' => $request->label,
			'country_code' => $request->country_code,
			'mobile_number' => $request->mobile_number,
			'city' => $request->city,
			'street' => $request->street,
			'building' => $request->building,
			'apartment' => $request->apartment,
			'instructions' => $request->instructions,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'google_map' => $request->google_map,
			'is_default' => $is_default
		]);

		return parent::return_success($address->fresh());
	}

	/*
	* EDIT ADDRESS
	*/
	public function edit(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'address_id' => 'required',
			'label' => 'required|string',
			'country_code' => 'required|string',
			'mobile_number' => 'required|numeric',
			'city' => 'required|string',
			'street' => 'required|string',
			'building' => 'required|string',
			'apartment' => 'required|string',
			'instructions' => 'nullable|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'google_map' => 'required|string'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Get user
		$user = Auth::guard('api')->user();

		// Check if the address exists in the user addresses list
		if(!$address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->first()){
			return parent::return_error('Address does not exist', 405, 'Address does not exist');
		}

		// Update address
		$address->update([
			'label' => $request->label,
			'country_code' => $request->country_code,
			'mobile_number' => $request->mobile_number,
			'city' => $request->city,
			'street' => $request->street,
			'building' => $request->building,
			'apartment' => $request->apartment,
			'instructions' => $request->instructions,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'google_map' => $request->google_map
		]);

		return parent::return_success($address->fresh());
	}

	/*
	* DELETE ADDRESS
	*/
	public function delete(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'address_id' => 'required'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Get user
		$user = Auth::guard('api')->user();

		// Check if the address exists in the user addresses list
		if(!$address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->first()){
			return parent::return_error('Address does not exist', 405, 'Address does not exist');
		}

		// Check if the deleted address is default => set any other address
		$is_default = $address->is_default;
		$count = count($user->Addresses);
		$address->delete();
		if($is_default && $count > 1 ){
			$any_user_address = UserAddress::where('user_id', $user->id)->first();
			$any_user_address->is_default = 1;
			$any_user_address->save();
		}

		return parent::return_success($user->fresh('Addresses')->Addresses);
	}

	/*
	* SET AS DEFAULT
	*/
	public function set_as_default(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'address_id' => 'required'
		]);

		if($validator->fails()){
			return parent::return_error('Missing parameter(s)', 400, $validator->messages()->all()[0]);
		}

		// Get user
		$user = Auth::guard('api')->user();

		// Check if the address exists in the user addresses list
		if(!$address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->first()){
			return parent::return_error('Address does not exist', 405, 'Address does not exist');
		}
		UserAddress::where('id', '<>', $request->address_id)->where('is_default', 1)->update(['is_default' => 0]);
		$address->is_default = 1;
		$address->save();

		// Get all addresses
		$addresses = $user->Addresses;

		return parent::return_success($addresses);
	}
}
