<?php

namespace App\Http\Controllers\Cms\Base;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\WhatsappHelper;
use App\Models\User;

class WhatsappNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:sms_notifications-create', ['only' => ['index', 'bulk', 'targeted']]);
    }

    public function page_info(){
        $page_info = [
            'title' => 'Whatsapp Notifications',
            'link' => 'whatsapp-notifications'
        ];
        return $page_info;
    }

    public function index()
    {
        $page_info = $this->page_info();

        // Get Users For Targeted Push
        $users = User::select(['id', 'full_name'])->get();

        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'users'));
    }

    public function bulk_whatsapp(Request $request)
    {
        $this->validate($request, [
            'bulk_message' => 'required'
        ]);

        // Get All Users
        $users = User::select(['id', 'mobile_number'])->get();

        $data = WhatsappHelper::twilio();

        // if($data['success'])
        //     return redirect()->back()->withSuccess($data['message']);
        // else
        //     return redirect()->back()->withError($data['message']);
    }

    // public function single_whatsapp(Request $request)
    // {
    //     $this->validate($request, [
    //         'users' => 'required|array',
    //         'targeted_message' => 'required'
    //     ]);

    //     // Get selected users
    //     $users = User::select('mobile_number')->whereIn('id', $request->users)->get();

    //     $data = $this->send_whatsapp($users, $request->targeted_message);

    //     // if($data['success'])
    //     //     return redirect()->back()->withSuccess($data['message']);
    //     // else
    //     //     return redirect()->back()->withError($data['message']);
    // }

    // private function send_whatsapp($users, $message)
    // {
    //     $mobile_numbers = [];
    //     foreach($users as $user){
    //         $mobile_numbers[] = $user->mobile_number;
    //     }

    //     // Send sms to each users
    //     $result = WhatsappHelper::whatsappSend(json_encode($mobile_numbers));
    //     dd($result);
    //     // $result = json_decode($result, true)[0];
    //     // $ErrorCode = $result['ErrorCode'];

    //     // if($ErrorCode == 0){
    //     //     $data = [
    //     //         'success' => true,
    //     //         'message' => 'SMS notification sent to '.$users->count().' users'
    //     //     ];
    //     // } elseif($ErrorCode == -1){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'No Text Message Specified'
    //     //     ];
    //     // } elseif($ErrorCode == -2){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'No Source'
    //     //     ];
    //     // } elseif($ErrorCode == -3){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'No Destination'
    //     //     ];
    //     // } elseif($ErrorCode == -4){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'Invalid Destination'
    //     //     ];
    //     // } elseif($ErrorCode == -5){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'Invalid Credentials'
    //     //     ];
    //     // } elseif($ErrorCode == -6){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'No Credit'
    //     //     ];
    //     // } elseif($ErrorCode == -7){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'Invalid Data Coding'
    //     //     ];
    //     // } elseif($ErrorCode == -8){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'Invalid Source'
    //     //     ];
    //     // } elseif($ErrorCode == -10){
    //     //     $data = [
    //     //         'success' => false,
    //     //         'message' => 'Unknown Error'
    //     //     ];
    //     // }

    //     // return $data;
    // }

}

