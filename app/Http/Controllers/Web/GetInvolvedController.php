<?php

namespace App\Http\Controllers\Web;

use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Mail\ContactMessage;
use App\Mail\RegistrationActivation;
use App\Models\SyndicateAdvertisement;
use App\Models\SyndicateOthersAdvertisement;
use App\Models\SyndicateUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GetInvolvedController extends Controller
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
     * The advertising rate card - matches the old site's page-donate.php
     * exactly (same table already used by the "Get Involved Advertise
     * Manager" CMS module). No sidebar on this page, matching the old site.
     *
     */
    public function advertise()
    {
        $ads = SyndicateAdvertisement::where('status', '1')->orderBy('id', 'asc')->get();
        $page_title = 'Advertise';

        return view('web.pages.page-donate', compact('ads', 'page_title'));
    }

    /**
     * Show the member self-registration form. Supports the old site's
     * English/Arabic language switch, via ?lang=en|ar and Laravel's own
     * locale/translation system (resources/lang/en.json, ar.json).
     *
     */
    public function registerForm(Request $request)
    {
        $lang = $request->query('lang', 'en') === 'ar' ? 'ar' : 'en';
        App::setLocale($lang);
        $page_title = 'Fill Your Info';

        return view('web.pages.form', array_merge(['page_title' => $page_title], $this->sidebarData()));
    }

    /**
     * Handle member self-registration - creates a real row in syndicate_user
     * (the same table the CMS "Syndicate Users" module manages), then emails
     * an activation link. The account stays pending (activation_code empty)
     * until that link is clicked, which is also the exact field the CMS
     * admin's own approve/block toggle uses and the login page checks - one
     * single gate, not two.
     *
     */
    public function register(Request $request)
    {
        $lang = $request->input('lang') === 'ar' ? 'ar' : 'en';

        $request->validate([
            'first_name' => 'required|string|max:255',
            'fathers_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'mobile_number' => 'required|string|max:255',
            'home_number' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:syndicate_user,email',
            'password' => 'required|min:6',
            'company' => 'required|in:Alfa,Touch',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'profile_link' => 'nullable|in:FB,LINKEDIN,BOTH',
            'facebook_fb' => 'required_if:profile_link,FB|nullable|string|max:255',
            'linkedin_li' => 'required_if:profile_link,LINKEDIN|nullable|string|max:255',
            'facebook_both' => 'required_if:profile_link,BOTH|nullable|string|max:255',
            'linkedin_both' => 'required_if:profile_link,BOTH|nullable|string|max:255',
            'photo' => 'required|image|max:512',
            'any_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        // Minimum age check - matches the old site's rule exactly (5 years).
        $dob = Carbon::parse($request->dob);
        if($dob->diffInYears(now()) < 5){
            return redirect()->back()->withInput()->withErrors(['dob' => 'The user should be at least 5 years of age.']);
        }

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

        $photo = FilesHelper::storeFile('user', $request->file('photo'));
        $anyFile = $request->hasFile('any_file') ? FilesHelper::storeFile('upload_file', $request->file('any_file')) : '';

        $user = SyndicateUser::create([
            'first_name' => $request->first_name,
            'fathers_name' => $request->fathers_name,
            'last_name' => $request->last_name,
            'mothers_name' => $request->mothers_name,
            'dob' => $dob->format('Y-m-d'),
            'mobile_number' => $request->mobile_number,
            'home_number' => $request->home_number ?? '',
            'email' => $request->email,
            'password' => $request->password,
            'company' => $request->company,
            'blood_type' => $request->blood_type,
            'profile_link' => $request->profile_link ?? '',
            'facebook' => $facebook,
            'linkedin' => $linkedin,
            'photo' => $photo,
            'any_file' => $anyFile,
            'lang' => $lang,
        ]);

        $activationUrl = route('web.activate-account', ['user_id' => base64_encode($user->id)]);

        // A missing/invalid mail configuration should never break the
        // registration itself - the account above already exists regardless.
        try {
            Mail::to($user->email)->send(new RegistrationActivation($user->first_name, $user->last_name, $activationUrl));
        } catch (\Throwable $e) {
            Log::warning('Registration activation email failed to send: ' . $e->getMessage());
        }

        return redirect()->route('web.form', ['lang' => $lang])->with('register_success', 'You have completed the registration. Please activate your account through the Activation Link sent to your email.');
    }

    /**
     * Handle the emailed activation link - sets activation_code to
     * 'activated' once. This is the exact same field (and value) the CMS
     * admin's own approve/block toggle uses and the login page requires -
     * a single real gate, not the old site's disconnected status/
     * activation_code pair.
     *
     */
    public function activate(Request $request)
    {
        $userId = base64_decode($request->query('user_id'), true);
        $user = $userId !== false ? SyndicateUser::find($userId) : null;

        if($user && $user->getAttributes()['activation_code'] !== 'activated'){
            $user->update(['activation_code' => 'activated']);

            return redirect()->route('web.user-login')->with('activated', true);
        }

        return redirect()->route('web.form');
    }

    public function contact()
    {
        $page_title = 'Contact Us';

        return view('web.pages.contact', array_merge(['page_title' => $page_title], $this->sidebarData()));
    }

    /**
     * Handle the contact form submission - emails the same address the old
     * site used (rima@pf-agency.com), per explicit instruction.
     *
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to('rima@pf-agency.com')->send(new ContactMessage($request->name, $request->email, $request->message));
            $status = 'Thank you. We Will Contact You Soon';
            $statusType = 'success';
        } catch (\Throwable $e) {
            Log::warning('Contact message email failed to send: ' . $e->getMessage());
            $status = "Sorry, the query can't be sent at this moment.";
            $statusType = 'error';
        }

        return redirect()->route('web.contact')->with('contact_status', $status)->with('contact_status_type', $statusType);
    }
}
