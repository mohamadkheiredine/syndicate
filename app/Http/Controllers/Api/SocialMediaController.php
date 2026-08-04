<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\SocialMedia;

class SocialMediaController extends Controller
{
	public function index()
	{
        $social_medias = SocialMedia::GeneralScope()->get();

		return parent::return_success($social_medias);
	}
}
