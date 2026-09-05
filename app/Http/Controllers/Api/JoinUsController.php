<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\JoinUsMessage;
use App\Models\JoinUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Ports the old CMS's Api\JoinUsController exactly - public (old's auth
 * block is commented out), same table (join_us, separate from
 * syndicate_join used by the website's own "Join Syndicate" sidebar box).
 */
class JoinUsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->filled('name')) {
            return parent::return_error('Missing Name', 401, 'messages.missing_parameter');
        }
        if (!$request->filled('email')) {
            return parent::return_error('Missing Email', 401, 'messages.missing_parameter');
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return parent::return_error('Invalid Email', 402, 'messages.invalid_parameters');
        }

        JoinUs::create($request->only(['name', 'email']));

        try {
            Mail::to($request->email)->send(new JoinUsMessage($request->name));
        } catch (\Throwable $e) {
            Log::warning('Join Us email failed to send: ' . $e->getMessage());
        }

        return parent::return_success(['status' => __('messages.email_sent_success')]);
    }
}
