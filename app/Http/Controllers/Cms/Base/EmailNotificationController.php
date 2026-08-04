<?php

namespace App\Http\Controllers\Cms\Base;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\FilesHelper;
use App\Models\User;
use Config;
use Mail;

class EmailNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:email_notifications-create', ['only' => ['index', 'bulk', 'targeted']]);
    }

    public function page_info(){
        $page_info = [
            'title' => 'Email Notifications',
            'link' => 'email-notifications'
        ];
        return $page_info;
    }

    public function index()
    {
        $page_info = $this->page_info();

        // Get Users For Targeted Push
        $users = User::select(['id', 'full_name', 'email'])->get();

        return view('cms.base.'.$page_info['link'].'.index', compact('page_info', 'users'));
    }

    public function bulk_email(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'bulk_image' => 'mimes:png,jpg,jpeg|max:250',
            'bulk_subject' => 'required',
            'bulk_message' => 'required'
        ]);

        $image_path = null;
        if($request->bulk_image){
            $image_path = FilesHelper::storeFile($page_info['link'], $request->bulk_image);
        }

        // Get All Users
        $users = User::select(['id', 'email'])->get();

        foreach($users as $user){
            // Send email to each users
            try {
                Mail::send('emails.email-notifications', [
                    'image' => $image_path,
                    'subject' => $request->bulk_subject,
                    'msg' => $request->bulk_message
                ], function($message) use ($user, $request) {
                    $message->from(Config::get('mail.from'));
                    $message->to($user->email)->subject($request->bulk_subject);
                });
            } catch(\Exception $e) {
                return redirect()->back()->withError('Something went wrong with the SMTP!');
            }
        }

        return redirect()->back()->withSuccess('Email notification sent to '.$users->count().' users');
    }

    public function single_email(Request $request)
    {
        $page_info = $this->page_info();

        $this->validate($request, [
            'users' => 'required|array',
            'targeted_image' => 'mimes:png,jpg,jpeg|max:250',
            'targeted_subject' => 'required',
            'targeted_message' => 'required'
        ]);

        $image_path = null;
        if($request->targeted_image){
            $image_path = FilesHelper::storeFile($page_info['link'], $request->targeted_image);
        }

        // Get selected users
        $users = User::select('id', 'email')->whereIn('id', $request->users)->get();

        foreach($users as $user){
            // Send email to each users
            try {
                Mail::send('emails.email-notifications', [
                    'image' => $image_path,
                    'subject' => $request->targeted_subject,
                    'msg' => $request->targeted_message
                ], function($message) use ($user, $request) {
                    $message->from(Config::get('mail.from'));
                    $message->to($user->email)->subject($request->targeted_subject);
                });
            } catch(\Exception $e) {
                return redirect()->back()->withError('Something went wrong with the SMTP!');
            }
        }

        return redirect()->back()->withSuccess('Email notification sent to '.$users->count().' users');
    }

}

