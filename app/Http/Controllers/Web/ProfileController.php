<?php

namespace App\Http\Controllers\Web;

use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateOthersAdvertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * The two ad slots shown in the sidebar on every page that uses it -
     * matches the old site's right-panel.php (positions 1 = Right Up,
     * 2 = Right Down). Same as AboutController::sidebarData().
     *
     */
    private function sidebarData()
    {
        return [
            'right_up_ads' => SyndicateOthersAdvertisement::where('position', 1)->where('status', '1')->get(),
            'right_down_ads' => SyndicateOthersAdvertisement::where('position', 2)->where('status', '1')->get(),
        ];
    }

    /**
     * Show the logged-in member's own profile. Uses their stored language
     * (set at registration) - unlike the registration form, there's no
     * language switcher here, matching the old site.
     *
     */
    public function show()
    {
        $user = Auth::guard('web_user')->user();
        App::setLocale($user->lang === 'ar' ? 'ar' : 'en');
        $page_title = 'User Profile';

        return view('web.pages.user-profile', array_merge(['user' => $user, 'page_title' => $page_title], $this->sidebarData()));
    }

    /**
     * Update the logged-in member's own profile. DOB is intentionally never
     * touched here - matches the old site, which makes it read-only after
     * registration. The password field is only applied if the member
     * actually typed a new one - the correct version of what the old site's
     * placeholder-text trick was going for.
     *
     */
    public function update(Request $request)
    {
        $user = Auth::guard('web_user')->user();

        // Required fields matched to the CMS's own SyndicateUserController::update()
        // exactly (first/fathers/last name, email, mobile, blood type, company) -
        // mothers_name is not required there, so it isn't required here either.
        // profile_link/facebook/linkedin/any_file don't exist in the CMS form at
        // all, so those stay governed by the public site's own rules.
        $request->validate([
            'first_name' => 'required|string|max:255',
            'fathers_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mothers_name' => 'nullable|string|max:255',
            'mobile_number' => 'required|string|max:255',
            'home_number' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:syndicate_user,email,' . $user->id,
            'password' => 'nullable|min:6',
            'company' => 'required|in:Alfa,Touch',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'profile_link' => 'nullable|in:FB,LINKEDIN,BOTH',
            'facebook_fb' => 'required_if:profile_link,FB|nullable|string|max:255',
            'linkedin_li' => 'required_if:profile_link,LINKEDIN|nullable|string|max:255',
            'facebook_both' => 'required_if:profile_link,BOTH|nullable|string|max:255',
            'linkedin_both' => 'required_if:profile_link,BOTH|nullable|string|max:255',
            'photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'any_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $facebook = '';
        $linkedin = '';
        if($request->profile_link === 'FB'){
            $facebook = $request->facebook_fb;
        } elseif($request->profile_link === 'LINKEDIN'){
            $linkedin = $request->linkedin_li;
        } elseif($request->profile_link === 'BOTH'){
            $facebook = $request->facebook_both;
            $linkedin = $request->linkedin_both;
        }

        $photo = $user->getAttributes()['photo'];
        if($request->hasFile('photo')){
            if($photo){
                FilesHelper::deleteFileByName('syndicate-users', $photo);
            }
            $photo = FilesHelper::storeFile('syndicate-users', $request->file('photo'));
        }

        $anyFile = $user->getAttributes()['any_file'];
        if($request->hasFile('any_file')){
            if($anyFile){
                FilesHelper::deleteFileByName('syndicate-users', $anyFile);
            }
            $anyFile = FilesHelper::storeFile('syndicate-users', $request->file('any_file'));
        }

        $updateData = [
            'first_name' => $request->first_name,
            'fathers_name' => $request->fathers_name,
            'last_name' => $request->last_name,
            'mothers_name' => $request->mothers_name ?? '',
            'mobile_number' => $request->mobile_number,
            'home_number' => $request->home_number ?? '',
            'email' => $request->email,
            'company' => $request->company,
            'blood_type' => $request->blood_type,
            'profile_link' => $request->profile_link ?? '',
            'facebook' => $facebook,
            'linkedin' => $linkedin,
            'photo' => $photo,
            'any_file' => $anyFile,
        ];

        if($request->filled('password')){
            $updateData['password'] = $request->password;
        }

        $user->update($updateData);

        return redirect()->route('web.user-profile')->with('profile_status', 'You have been successfully edited.');
    }
}
