<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SyndicateOthersAdvertisement;
use App\Models\SyndicateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private function sidebarData()
    {
        return [
            'right_up_ads' => SyndicateOthersAdvertisement::where('position', 1)->where('status', '1')->get(),
            'right_down_ads' => SyndicateOthersAdvertisement::where('position', 2)->where('status', '1')->get(),
        ];
    }

    public function show()
    {
        $page_title = 'Sign In';

        return view('web.pages.user-login', array_merge(['page_title' => $page_title], $this->sidebarData()));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = SyndicateUser::where('email', $request->email)
            ->where('activation_code', 'activated')
            ->first();

        if(!$user || !$user->verifyPassword($request->password)){
            return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => 'Email address and password mismatch']);
        }

        Auth::guard('web_user')->login($user);

        return redirect()->route('web.user-profile');
    }


    public function logout()
    {
        Auth::guard('web_user')->logout();

        return redirect()->route('web.home');
    }
}
