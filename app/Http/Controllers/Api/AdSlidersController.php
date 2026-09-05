<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdSlider;

/**
 * Ports the old CMS's Api\AdSlidersController exactly - public, no auth.
 * Reuses the AdSlider model already built for the CMS module (same
 * syndicate_ad_sliders table).
 *
 */
class AdSlidersController extends Controller
{
    public function index()
    {
        $sliders = AdSlider::select(['main_image', 'text'])
            ->where('status', '1')
            ->get();

        return response()->json($sliders);
    }
}
