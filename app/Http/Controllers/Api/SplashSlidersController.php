<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SplashSlider;

/**
 * Ports the old CMS's Api\SplashSlidersController exactly - public, no
 * auth. Reuses the SplashSlider model already built for the CMS module
 * (same syndicate_splash_sliders table).
 *
 */
class SplashSlidersController extends Controller
{
    public function index()
    {
        $sliders = SplashSlider::select(['main_image', 'title', 'subtitle'])
            ->where('status', '1')
            ->get();

        return response()->json($sliders);
    }
}
