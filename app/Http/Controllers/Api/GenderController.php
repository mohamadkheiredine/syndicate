<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Gender;

class GenderController extends Controller
{
    public function index()
    {
        $genders = Gender::active()->ordered()->get();

        return parent::return_success($genders);
    }
}
