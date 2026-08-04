<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\FixedSection;

class FixedSectionController extends Controller
{
    public function about()
    {
        $section = FixedSection::where('slug', 'about')->firstOrFail();

        return parent::return_success($section);
    }

    public function terms()
    {
        $section = FixedSection::select(['title', 'text'])->where('slug', 'terms-and-conditions')->firstOrFail();

        return parent::return_success($section);
    }

    public function privacy()
    {
        $section = FixedSection::select(['title', 'text'])->where('slug', 'privacy-policy')->firstOrFail();

        return parent::return_success($section);
    }
}
