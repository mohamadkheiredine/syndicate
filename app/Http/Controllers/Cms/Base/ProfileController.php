<?php

namespace App\Http\Controllers\Cms\Base;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('cms.base.profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $this->validate($request, [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:admins,email,'.$admin->id
        ]);

        $admin->update($request->all());

        return back()->withStatus('Profile successfully updated.');
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(Request $request)
    {
        Auth::guard('admin')->user()->update([
            'password' => Hash::make($request->get('password'))
        ]);

        return back()->withStatus('Password successfully updated.');
    }

}
