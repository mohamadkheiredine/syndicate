<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return Success Response Form APIs
     *
     */
    public function return_success($data = [], $code = 200){
        return response()->json($data)->setStatusCode($code);
    }

    /**
     * Return Error Error Form APIs
     *
     */
    public function return_error($debugger, $code, $message){
        $error['error'] = [];
        $error['error']['debugger'] = $debugger;
        $error['error']['code'] = $code;
        // Old's real return_error() resolves this through Lang::get()
        // before sending it, so the response body actually contains real
        // text ("Invalid Credentials"), not the raw lang key
        // ("messages.invalid_credentials") - trans() falls back to the
        // key unchanged if it's not a real key, so this is a safe,
        // additive fix for every endpoint using return_error().
        $error['error']['message'] = trans($message);

        return response()->json($error)->setStatusCode(400);
    }

    /**
     * Generate PIN for authentication
     *
     */
    protected function generatePin(){
        if (config('app.env') == 'production'){
            return rand(100000, 999999);
        } else {
            return 111111;
        }
    }

    /**
     * Generate Verification ID
     *
     */
    protected function generateVerificationID(){
        return Str::random(32);
    }

    /**
     * Read the access token from the request - matches the old CMS's
     * valid_access_token() exactly: a header literally named `token`,
     * not the Authorization: Bearer convention.
     *
     */
    protected function valid_access_token($request)
    {
        return $request->header('token') ?: false;
    }

    /**
     * Resolve a SyndicateUser from an access token - same two-step shape
     * as the old CMS's valid_access_token()/get_user_by_access_token()
     * pair, backed by Sanctum's token storage instead of the old
     * users_access table. findToken() accepts the same plaintext token
     * string a client already gets back from createToken(), so nothing
     * about the token format changes - only which header carries it.
     *
     */
    protected function get_user_by_access_token($token)
    {
        $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

        if (!$accessToken || !($accessToken->tokenable instanceof \App\Models\SyndicateUser)) {
            return false;
        }

        return $accessToken->tokenable;
    }
}
