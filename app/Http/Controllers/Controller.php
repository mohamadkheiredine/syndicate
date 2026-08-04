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
        $error['error']['message'] = $message;

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
}
